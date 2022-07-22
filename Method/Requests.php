<?php
namespace GDO\Friends\Method;

use GDO\Friends\GDO_FriendRequest;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;
use GDO\Friends\WithFriendTabs;

/**
 * Show a list of friend requests.
 * 
 * @author gizmore
 */
final class Requests extends MethodQueryList
{
	use WithFriendTabs;
	
	public function gdoTable() { return GDO_FriendRequest::table(); }
	
	public function gdoDecorateList(GDT_List $list)
	{
		$list->title('list_friends_requests', [$list->countItems()]);
	}
	
	public function getQuery()
	{
		$user = GDO_User::current();
		return $this->gdoTable()->select()->where("frq_friend={$user->getID()} AND frq_denied IS NULL");
	}
	
}
