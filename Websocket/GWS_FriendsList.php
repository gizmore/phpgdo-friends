<?php
namespace GDO\Friends\Websocket;

use GDO\Friends\GDO_Friendship;
use GDO\Friends\Module_Friends;
use GDO\Table\GDT_PageMenu;
use GDO\User\GDO_User;
use GDO\Websocket\Server\GWS_Command;
use GDO\Websocket\Server\GWS_Commands;
use GDO\Websocket\Server\GWS_Message;

/**
 * WebSocket command for friend requests.
 *
 * 1. Map websocket to friend request form.
 * 2. Hook friend requests and send websocket packet to notify friend target.
 *
 * @author gizmore
 */
class GWS_FriendsList extends GWS_Command
{

	public function execute(GWS_Message $msg)
	{
		$user = GDO_User::findById($msg->read32u());

		$page = $msg->read16u();

		$reason = '';
		if (!Module_Friends::instance()->canViewFriends($user, $reason))
		{
			return $msg->replyErrorMessage($msg->cmd(), t('err_not_allowed', [$reason]));
		}

		$query = GDO_Friendship::table()->select('user_id')->joinObject('friend_friend')->where('friend_user=' . $user->getID());
		$pagemenu = GDT_PageMenu::make()->query($query);
		$pagemenu->page($page);
		$pagemenu->paginateQuery($query);
		$result = $query->exec();
		$payload = $this->pagemenuToBinary($pagemenu);
		while ($friendid = $result->fetchVar())
		{
			$payload .= $msg->write32($friendid);
		}

		return $msg->replyBinary($msg->cmd(), $payload);
	}

}

GWS_Commands::register(0x0603, new GWS_FriendsList());
