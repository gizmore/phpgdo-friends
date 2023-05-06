<?php
declare(strict_types=1);
namespace GDO\Friends\Method;

use GDO\Core\Application;
use GDO\Core\GDT;
use GDO\Core\GDT_Hook;
use GDO\Core\GDT_Template;
use GDO\Core\Method;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\User\GDO_User;
use GDO\User\GDT_User;

/**
 * Remove a friend request.
 *
 * @version 7.0.3
 * @author gizmore
 */
final class Remove extends Method
{

	public function isTrivial(): bool
	{
		return false;
	}

	public function isAlwaysTransactional(): bool { return true; }

	public function gdoParameters(): array
	{
		return [
			GDT_User::make('friend')->notNull(),
		];
	}

	public function onRenderTabs(): void
	{
		Module_Friends::instance()->renderTabs();
	}

	public function execute(): GDT
	{
		$user = GDO_User::current();
		$friend = $this->getFriend();
		$uid = $user->getID();
		$fid = $friend->getID();

		# Delete Friendships
		$friendship = GDO_Friendship::findById($fid, $uid);
		$friendship->delete();
		$friendship = GDO_Friendship::findById($uid, $fid);
		$friendship->delete();

		# Call hook
		GDT_Hook::callWithIPC('FriendsRemove', $uid, $fid);

		# Send mail notes
		$this->sendMail($friendship);

		# Render and redirect

		$this->message('msg_friendship_deleted', [$friendship->getFriend()->renderUserName()]);

		return $this->redirect(href('Friends', 'FriendList'));
	}

	public function getFriend(): GDO_User
	{
		return $this->gdoParameterValue('friend');
	}

	private function sendMail(GDO_Friendship $friendship): void
	{
		$user = GDO_User::current();
		$friend = $friendship->getFriend();

		$mail = Mail::botMail();
		$mail->setSubject(tusr($friend, 'mail_subj_friend_removed', [sitename(), $user->renderUserName()]));

		$tVars = [
			'user' => $user,
			'friend' => $friend,
		];
		$body = GDT_Template::phpUser($friend, 'Friends', 'mail/friend_removed.php', $tVars);
		$mail->setBody($body);

		$mail->sendToUser($friend);
	}

}
