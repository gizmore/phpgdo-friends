<?php
namespace GDO\Friends\Method;

use GDO\Core\GDO;
use GDO\DB\Query;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\WithFriendTabs;
use GDO\Table\GDT_List;
use GDO\Table\MethodQueryList;
use GDO\User\GDO_User;

/**
 * Show a list of friend requests.
 *
 * @author gizmore
 */
final class Requests extends MethodQueryList
{

	use WithFriendTabs;

	public function gdoDecorateList(GDT_List $list)
	{
		$list->title('list_friends_requests', [$list->countItems()]);
	}

	public function gdoTable(): GDO { return GDO_FriendRequest::table(); }


	public function getQuery(): Query
	{
		$user = GDO_User::current();
		return $this->gdoTable()->select()->where("frq_friend={$user->getID()} AND frq_denied IS NULL");
	}

}
