<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\Module_Friends;
use GDO\Mail\Mail;
use GDO\User\GDO_User;
use GDO\Core\GDT_Hook;
use GDO\Core\Application;
use GDO\Core\GDT_Template;
use GDO\User\GDT_User;

/**
 * Remove a friend request.
 * 
 * @author gizmore
 * @version 7.0.1
 */
final class Remove extends Method
{
	public function isAlwaysTransactional() : bool { return true; }
	
	public function gdoParameters() : array
	{
		return [
			GDT_User::make('friend')->notNull(),
		];
	}
	
	public function getFriend() : GDO_User
	{
		return $this->gdoParameterValue('friend');
	}
	
	public function onRenderTabs() : void
	{
		Module_Friends::instance()->renderTabs();
	}
	
	public function execute()
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
		
		if (Application::instance()->isHTML())
		{
			$this->redirect(href('Friends', 'FriendList'));
		}
	}
	
	private function sendMail(GDO_Friendship $friendship)
	{
		$user = GDO_User::current();
		$friend = $friendship->getFriend();

		$mail = Mail::botMail();
		$mail->setSubject(tusr($friend, 'mail_subj_friend_removed', [sitename(), $user->renderUserName()]));
		
		$tVars = array(
			'user' => $user,
			'friend' => $friend,
		);
		$body = GDT_Template::phpUser($friend, 'Friends', 'mail/friend_removed.php', $tVars);
		$mail->setBody($body);
		
		$mail->sendToUser($friend);
	}
}
