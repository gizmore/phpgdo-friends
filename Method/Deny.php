<?php
declare(strict_types=1);
namespace GDO\Friends\Method;

use GDO\Core\GDO_DBException;
use GDO\Core\GDT;
use GDO\Core\GDT_Template;
use GDO\Date\Time;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\MethodFriendRequest;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\User\GDO_User;

final class Deny extends MethodFriendRequest
{

	/**
	 * @throws GDO_DBException
	 */
	public function executeWithRequest(GDO_FriendRequest $request): GDT
	{
		$request->saveVar('frq_denied', Time::getDate());

		$this->sendMail($request);

		Module_Friends::instance()->renderTabs();
		$this->message('msg_friends_denied');
		return $this->redirect(href('Friends', 'Requests'));
	}

	private function sendMail(GDO_FriendRequest $request): void
	{
		$sitename = sitename();
		$user = GDO_User::current();
		$username = $user->renderUserName();
		$friend = $request->getFriend();

		$mail = Mail::botMail();
		$mail->setSubject(tusr($friend, 'mail_subj_frq_denied', [$sitename, $username]));

		$tVars = [
			'user' => $user,
			'friend' => $friend,
		];
		$body = GDT_Template::phpUser($friend, 'Recovery', 'mail/friend_denied.php', $tVars);
		$mail->setBody($body);

		$mail->sendToUser($friend);
	}

}
