<?php
namespace GDO\Friends;

use GDO\Core\GDO;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_Template;
use GDO\User\GDT_User;
use GDO\User\GDO_User;

final class GDO_Friendship extends GDO
{
	public function gdoCached() : bool { return false; }
	public function gdoColumns() : array
	{
		return array(
			GDT_User::make('friend_user')->primary(),
			GDT_User::make('friend_friend')->primary(),
			GDT_FriendRelation::make('friend_relation')->notNull(),
			GDT_CreatedAt::make('friend_created'),
		);
	}
	
	public function getUser() : GDO_User { return $this->gdoValue('friend_user'); }
	public function getUserID() : string { return $this->gdoVar('friend_user'); }
	
	/**
	 * @return GDO_User
	 */
	public function getFriend() : GDO_User { return $this->gdoValue('friend_friend'); }
	public function getFriendID() : string { return $this->gdoVar('friend_friend'); }

	public function getCreated() { return $this->gdoVar('friend_created'); }
	public function getRelation() { return $this->gdoVar('friend_relation'); }

	public function displayRelation() { return GDT_FriendRelation::displayRelation($this->getRelation()); }
	
	public function renderList() : string { return GDT_Template::php('Friends', 'listitem/friendship.php', ['gdo' => $this]); }
	public function renderCard() : string { return GDT_Template::php('Friends', 'card/friendship.php', ['gdo' => $this]); }
	
	##############
	### Static ###
	##############
	public static function getRelationBetween(GDO_User $user, GDO_User $friend)
	{
		return self::table()->select('friend_relation')->
			where("friend_user={$user->getID()} AND friend_friend={$friend->getID()}")->exec()->fetchValue();
	}
	
	public static function areRelated(GDO_User $user, GDO_User $friend)
	{
		return self::getRelationBetween($user, $friend) !== null;
	}
	
	public static function count(GDO_User $user)
	{
		if (null === ($cached = $user->tempGet('gdo_friendship_count')))
		{
			$cached = self::queryCount($user);
			$user->tempSet('gdo_friendship_count', $cached);
// 			$user->recache();
		}
		return $cached;
	}
	
	private static function queryCount(GDO_User $user)
	{
		return self::table()->countWhere('friend_user='.$user->getID());
	}
	
	public function gdoAfterCreate(GDO $gdo) : void
	{
		$user = $this->getUser();
		$user->tempUnset('gdo_friendship_count');
// 		$user->recache();
	}
	
	### Friends
	public static function getFriendsQuery(GDO_User $user)
	{
		return GDO_Friendship::table()->select('*')->joinObject('friend_friend')->where("friend_user={$user->getID()}");
	}
	public static function getFriends(GDO_User $user)
	{
		$query = self::getFriendsQuery($user);
		$result = $query->exec();
		$table = GDO_User::table();
		return $table->fetchAll($result);
	}
}
