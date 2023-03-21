<?php
namespace GDO\Friends;

use GDO\Core\GDO;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_Template;
use GDO\Date\GDT_Timestamp;
use GDO\Language\Trans;
use GDO\UI\GDT_Message;
use GDO\User\GDO_User;
use GDO\User\GDT_User;

/**
 * Relationship request.
 *
 * @version 7.0.1
 * @author gizmore
 */
final class GDO_FriendRequest extends GDO
{

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

	public function gdoCached(): bool { return false; }

	public function gdoColumns(): array
	{
		return [
			GDT_User::make('frq_user')->primary(),
			GDT_User::make('frq_friend')->primary(),
			GDT_FriendRelation::make('frq_relation')->initial('friend')->label('friend_relation'),
			GDT_Message::make('frq_message'),
			GDT_CreatedAt::make('frq_created'),
			GDT_Timestamp::make('frq_denied'),
		];
	}

	public function gdoHashcode(): string { return self::gdoHashcodeS($this->gdoVars(['frq_user', 'frq_friend', 'frq_relation'])); }

	public function renderCard(): string { return GDT_Template::php('Friends', 'card/friendrequest.php', ['gdo' => $this]); }

	public function renderList(): string { return GDT_Template::php('Friends', 'listitem/friendrequest.php', ['gdo' => $this]); }

	public function gdoAfterCreate(GDO $gdo): void
	{
		$user = $this->getFriend();
		$user->tempUnset('gdo_friendrequest_count');
		$user->recache();
	}

	public function getFriend(): GDO_User { return $this->gdoValue('frq_friend'); }

	public function getReverseRelation() { return GDT_FriendRelation::reverseRelation($this->getRelation()); }

	public function getRelation() { return $this->gdoVar('frq_relation'); }

	public function getCreated() { return $this->gdoVar('frq_created'); }

	public function isDenied() { return $this->getDenied() !== null; }

	public function getDenied() { return $this->gdoVar('frq_denied'); }

	public function displayRelation() { return $this->displayRelationISO(Trans::$ISO); }

	public function displayRelationISO(string $iso) { return GDT_FriendRelation::displayRelationISO($iso, $this->getRelation()); }

	public function isFrom(GDO_User $user) { return $this->getUser() === $user; }

	public function getUser(): GDO_User { return $this->gdoValue('frq_user'); }

	public function getUserID() { return $this->gdoVar('frq_user'); }

	public function getFriendID() { return $this->gdoVar('frq_friend'); }

	##############
	### Static ###
	##############

	public function getOtherUser(GDO_User $user)
	{
		$one = $this->getUser();
		return $one === $user ? $this->getFriend() : $one;
	}

	public function renderMessage(): string
	{
		$gdt = $this->getMessageColumn();
		return $gdt->render();
	}

	public function getMessageColumn(): GDT_Message { return $this->gdoColumn('frq_message'); }

}
