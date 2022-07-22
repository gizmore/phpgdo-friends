<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Core\Website;
use GDO\Date\Time;
use GDO\Friends\GDO_FriendRequest;
use GDO\User\GDO_User;
use GDO\User\GDT_User;
use GDO\Util\Common;

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
	
	public function execute()
	{
		$user = GDO_User::current();
		$request = GDO_FriendRequest::findById($user->getID(), Common::getRequestString('friend'));
		$request->saveVar('frq_denied', Time::getDate());
		
		return $this->redirectMessage('msg_request_revoked', null, href('Friends', 'Requesting'));
	}
}
