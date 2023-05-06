<?php
declare(strict_types=1);
namespace GDO\Friends\Method;

use GDO\Core\GDT;
use GDO\Core\Method;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\Module_Friends;
use GDO\User\GDO_User;
use GDO\User\GDT_User;

/**
 * Remove a friend request from a user.
 *
 * @version 7.0.3
 * @since 6.2.0
 * @author gizmore
 */
final class RemoveFrom extends Method
{

	public function isTrivial(): bool
	{
		return false;
	}

	public function isAlwaysTransactional(): bool { return true; }

	public function gdoParameters(): array
	{
		return [
			GDT_User::make('user')->notNull(),
		];
	}

	public function onRenderTabs(): void
	{
		Module_Friends::instance()->renderTabs();
	}

	public function execute(): GDT
	{
		$user = GDO_User::current();
		$from = $this->getFriend();
		if (!($request = GDO_FriendRequest::getById($from->getID(), $user->getID())))
		{
			return $this->error('err_friend_request');
		}

		Deny::make()->executeWithRequest($request);

		return $this->redirectMessage('msg_request_denied', null, href('Friends', 'Requests'));
	}

	public function getFriend(): GDO_User
	{
		return $this->gdoParameterValue('user');
	}

}
