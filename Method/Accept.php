<?php
namespace GDO\Friends\Method;

use GDO\Core\GDT_Hook;
use GDO\Core\GDT_Template;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\GDT_FriendRelation;
use GDO\Friends\MethodFriendRequest;
use GDO\Mail\Mail;

final class Accept extends MethodFriendRequest
{

	public function getMethodTitle(): string
	{
		return t('mt_friends_accept');
	}

	public function executeWithRequest(GDO_FriendRequest $request)
	{
		GDO_Friendship::blank([
			'friend_user' => $request->getUserID(),
			'friend_friend' => $request->getFriendID(),
			'friend_relation' => $request->getRelation(),
		])->insert();
		GDO_Friendship::blank([
			'friend_user' => $request->getFriendID(),
			'friend_friend' => $request->getUserID(),
			'friend_relation' => GDT_FriendRelation::reverseRelation($request->getRelation()),
		])->insert();
		GDT_Hook::callWithIPC('FriendsAccept', $request->getUserID(), $request->getFriendID());
		$this->sendMail($request);
		$response = $this->message('msg_friends_accepted', [$request->getUser()->renderUserName()]);
		$request->delete();
		return $response;
	}

	protected function sendMail(GDO_FriendRequest $request)
	{
		$sitename = sitename();
		$user = $request->getFriend();
		$username = $user->renderUserName();
		$friend = $request->getUser();
		$mail = Mail::botMail();
		$mail->setSubject(tusr($friend,
			'mail_subj_frq_accepted', [
				$sitename, $username,
				$request->displayRelation()]));
		$tVars = [
			'user' => $user,
			'friend' => $friend,
			'relation' => $request->displayRelationISO($friend->getLangISO()),
		];
		$body = GDT_Template::phpUser(
			$friend, 'Friends',
			'mail/friend_accepted.php', $tVars);
		$mail->setBody($body);
		$mail->sendToUser($friend);
	}

}
