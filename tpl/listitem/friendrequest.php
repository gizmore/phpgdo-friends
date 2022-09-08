<?php
namespace GDO\Friends\tpl\listitem;
use GDO\Friends\GDO_FriendRequest;
use GDO\Table\GDT_ListItem;
use GDO\UI\GDT_AddButton;
use GDO\User\GDO_User;
use GDO\User\Method\Profile;
use GDO\UI\GDT_DeleteButton;
/** @var $gdo GDO_FriendRequest **/

$friendship = $gdo; # gdo means friendship
$me = GDO_User::current();
$friend = $friendship->getOtherUser($me);

$li = GDT_ListItem::make()->gdo($gdo);
$li->content($gdo->getMessageColumn());

if ($friendship->isFrom($me))
{
	$li->creatorHeader('frq_friend');
	$li->title('friend_request_to', [
		$friend->renderUserName(),
		$friendship->displayRelation(),
		tt($friendship->getCreated()),
	]);
	$li->actions()->addFields(
		GDT_DeleteButton::make()->icon('delete')
			->href(href('Friends', 'RemoveTo', '&friend='.$friend->getID()))
			->confirmText('ask_remove_friendship', [$friend->renderUserName()]));
}
else
{
	$li->creatorHeader('frq_user');
	$li->title('friend_request_from', [
		$friend->renderUserName(),
		$friendship->displayRelation(),
		tt($friendship->getCreated()),
	]);
	$li->actions()->addFields(
		GDT_AddButton::make()->icon('add')->href(href('Friends', 'AcceptFrom', '&user='.$friend->getID())),
		GDT_DeleteButton::make()->icon('block')
			->href(href('Friends', 'RemoveFrom', '&user='.$friend->getID()))
			->confirmText('ask_deny_friendship'));
}

$li->subtitle('user_info_subtitle', [
	$friend->renderUserName(),
	Profile::getHighestPermission($friend),
	sitename(),
	$friend->getLevel(),
]);

echo $li->render();
