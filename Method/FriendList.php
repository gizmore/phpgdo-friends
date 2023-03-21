<?php
namespace GDO\Friends\Method;

use GDO\Core\GDO;
use GDO\DB\Query;
use GDO\Friends\GDO_Friendship;
use GDO\Friends\WithFriendTabs;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;

/**
 * Show all friends.
 *
 * @author gizmore
 */
final class FriendList extends MethodQueryList
{

	use WithFriendTabs;

	public function gdoDecorateList(GDT_List $list)
	{
		$list->title('list_friends', [$list->countItems()]);
	}

	public function gdoTable(): GDO { return GDO_Friendship::table(); }


	public function getQuery(): Query
	{
		$user = GDO_User::current();
		return $this->gdoTable()->select()->where("friend_user={$user->getID()}");
	}

}
