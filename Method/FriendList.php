<?php
namespace GDO\Friends\Method;

use GDO\Friends\GDO_Friendship;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;
use GDO\Friends\WithFriendTabs;

/**
 * Show all friends.
 * 
 * @author gizmore
 */
final class FriendList extends MethodQueryList
{
	use WithFriendTabs;
	
	public function gdoTable() { return GDO_Friendship::table(); }
	
	public function gdoDecorateList(GDT_List $list)
	{
		$list->title('list_friends', [$list->countItems()]);
	}
	
	public function getQuery()
	{
		$user = GDO_User::current();
		return $this->gdoTable()->select()->where("friend_user={$user->getID()}");
	}
	
}
