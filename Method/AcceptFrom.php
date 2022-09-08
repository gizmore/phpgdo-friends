<?php
namespace GDO\Friends\Method;

use GDO\Core\Method;
use GDO\Friends\GDO_FriendRequest;
use GDO\User\GDO_User;
use GDO\User\GDT_User;
use GDO\UI\GDT_Redirect;
use GDO\Friends\WithFriendTabs;

final class AcceptFrom extends Method
{
	use WithFriendTabs;
	
	public function isAlwaysTransactional() : bool { return true; }
	
	public function getMethodTitle() : string
	{
		return t('mt_friends_accept');
	}
	
	public function gdoParameters() : array
	{
		return [
			GDT_User::make('user')->notNull(),
		];
	}
	
	public function execute()
	{
		$user = GDO_User::current();
		$friend = $this->gdoParameterValue('user');
		$fromId = $friend->getID();
		if (!($request = GDO_FriendRequest::table()->getById($fromId, $user->getID())))
		{
			return $this->error('err_friend_request');
		}
		
		Accept::make()->executeWithRequest($request);
		
		return GDT_Redirect::make()->redirectMessage('msg_friends_accepted', [$friend->renderUserName()], href('Friends', 'Requests'));
	}
}
