<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Date\Time;
use GDO\Friends\GDO_FriendRequest;
use GDO\User\GDO_User;
use GDO\User\GDT_User;

/**
 * Remove your friend request to...
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 6.9.0
 */
final class RemoveTo extends Method
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
	
	public function execute()
	{
		$user = GDO_User::current();
		$friend = $this->getFriend();
		$request = GDO_FriendRequest::findById($user->getID(), $friend->getID());
		$request->saveVar('frq_denied', Time::getDate());
		return $this->redirectMessage('msg_request_revoked', null, href('Friends', 'Requesting'));
	}
	
}
