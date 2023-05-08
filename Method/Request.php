<?php
namespace GDO\Friends\Method;

use GDO\Core\GDT;
use GDO\Core\GDT_Hook;
use GDO\Core\GDT_Template;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\GDT_Validator;
use GDO\Form\MethodForm;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\GDT_FriendRelation;
use GDO\Friends\Module_Friends;
use GDO\Friends\WithFriendTabs;
use GDO\Mail\Mail;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\User\GDT_User;

/**
 * Send a friend request.
 *
 * @version 7.0.1
 * @since 6.9.0
 * @author gizmore
 */
final class Request extends MethodForm
{

	use WithFriendTabs;

	protected function createForm(GDT_Form $form): void
	{
		$gdo = GDO_FriendRequest::table();
		$friend = GDT_User::make('frq_friend')->notNull();
		$form->addFields(
			$friend,
			$gdo->gdoColumn('frq_message'),
			GDT_Validator::make()->validator($form, $friend, [$this, 'validate_NoRelation']),
			GDT_Validator::make()->validator($form, $friend, [$this, 'validate_CanRequest']),
		);
		if (Module_Friends::instance()->cfgRelations())
		{
			$form->addField($gdo->gdoColumn('frq_relation'));
		}
		$form->addFields(
			GDT_AntiCSRF::make(),
		);
		$form->actions()->addField(GDT_Submit::make());
	}

	public function formValidated(GDT_Form $form): GDT
	{
		$user = GDO_User::current();
		$data = $form->getFormVars();
		if (!Module_Friends::instance()->cfgRelations())
		{
			$data['frq_relation'] = 'friends';
		}

		$request = GDO_FriendRequest::blank($data)->setVar('frq_user', $user->getID())->insert();

		$this->sendMail($request);

		GDT_Hook::callWithIPC('FriendsRequest', $request);

		return $this->message('msg_friend_request_sent');
	}

	private function sendMail(GDO_FriendRequest $request)
	{
		$mail = Mail::botMail();

		$user = $request->getUser();
		$friend = $request->getFriend();
		$relation = GDT_FriendRelation::displayRelation($request->getRelation());
		$append = "&from={$user->getID()}&for={$friend->getID()}&token={$request->gdoHashcode()}";
		$linkAccept = GDT_Link::anchor(url('Friends', 'Accept', $append));
		$linkDeny = GDT_Link::anchor(url('Friends', 'Deny', $append));

		$tVars = [
			'user' => $user,
			'friend' => $friend,
			'message' => $request->renderMessage(),
			'relation' => $relation,
			'link_accept' => $linkAccept,
			'link_deny' => $linkDeny,
		];
		$body = GDT_Template::phpUser($friend, 'Friends', 'mail/friend_request.php', $tVars);

		$mail->setSubject(tusr($friend, 'mail_subj_friend_request', [sitename(), $user->renderUserName()]));
		$mail->setBody($body);

		$mail->sendToUser($friend);
	}

	public function validate_NoRelation(GDT_Form $form, GDT_User $field)
	{
		if ($friend = $field->getUser())
		{
			$user = GDO_User::current();
			if ($friend === $user)
			{
				return $field->error('err_friend_self');
			}
			if (GDO_Friendship::areRelated($user, $friend))
			{
				return $field->error('err_already_related', [$friend->renderUserName()]);
			}
			if ($request = GDO_FriendRequest::getPendingFor($user, $friend))
			{
				if ($request->isDenied())
				{
					return $field->error('err_already_pending_denied', [$friend->renderUserName()]);
				}
				else
				{
					return $field->error('err_already_pending', [$friend->renderUserName()]);
				}
			}
		}
		return true;
	}

	public function validate_canRequest(GDT_Form $form, GDT_User $field)
	{
		if ($user = $field->getValue())
		{
			$reason = '';
			if (!(Module_Friends::instance()->canRequest($user, $reason)))
			{
				return $field->error('err_requesting_denied', [$reason]);
			}
		}
		return true;
	}

}
