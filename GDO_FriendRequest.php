<?php
namespace GDO\Friends;

use GDO\Core\GDO;
use GDO\Core\GDT_CreatedAt;
use GDO\Date\GDT_DateTime;
use GDO\Core\GDT_Template;
use GDO\User\GDT_User;
use GDO\User\GDO_User;
use GDO\UI\GDT_Message;

final class GDO_FriendRequest extends GDO
{
	public function gdoCached() : bool { return false; }
	public function gdoColumns() : array
	{
		return array(
			GDT_User::make('frq_user')->primary(),
			GDT_User::make('frq_friend')->primary(),
			GDT_FriendRelation::make('frq_relation')->initial('friend'),
			GDT_Message::make('frq_message'),
			GDT_CreatedAt::make('frq_created'),
			GDT_DateTime::make('frq_denied'),
		);
	}
	
	public function gdoHashcode() : string { return self::gdoHashcodeS($this->gdoVars(['frq_user', 'frq_friend', 'frq_relation'])); }
	
	public function getRelation() { return $this->gdoVar('frq_relation'); }
	public function getReverseRelation() { return GDT_FriendRelation::reverseRelation($this->getRelation()); }
	public function getCreated() { return $this->gdoVar('frq_created'); }
	public function getDenied() { return $this->gdoVar('frq_denied'); }
	public function isDenied() { return $this->getDenied() !== null; }
	
	public function displayRelation() { return GDT_FriendRelation::displayRelation($this->getRelation()); }
	
	public function isFrom(GDO_User $user) { return $this->getUserID() === $user->getID(); }
	
	public function getUser() : GDO_User { return $this->gdoValue('frq_user'); }
	public function getUserID() { return $this->gdoVar('frq_user'); }
	
	public function getFriend() : GDO_User { return $this->gdoValue('frq_friend'); }
	public function getFriendID() { return $this->gdoVar('frq_friend'); }
	
	public function renderCard() : string { return GDT_Template::php('Friends', 'card/friendrequest.php', ['gdo' => $this]); }
	public function renderList() : string { return GDT_Template::php('Friends', 'listitem/friendrequest.php', ['gdo' => $this]); }
	
	public function getMessageColumn() : GDT_Message { return $this->gdoColumn('frq_message'); }
	
	public function renderMessage() : string
	{
		$gdt = $this->getMessageColumn();
		return $gdt->render();
	}
	
	##############
	### Static ###
	##############
	public static function getPendingFor(GDO_User $user, GDO_User $friend)
	{
		return self::getById($user->getID(), $friend->getID());
	}
	
	public static function countIncomingFor(GDO_User $user)
	{
		if (null === ($cached = $user->tempGet('gdo_friendrequest_count')))
		{
			$cached = self::table()->countWhere("frq_friend={$user->getID()} AND frq_denied IS NULL");
			$user->tempSet('gdo_friendrequest_count', $cached);
			$user->recache();
		}
		return $cached;
	}
	
	public function gdoAfterCreate(GDO $gdo) : void
	{
		$user = $this->getFriend();
		$user->tempUnset('gdo_friendrequest_count');
		$user->recache();
	}
	
}
