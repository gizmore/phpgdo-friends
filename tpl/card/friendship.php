<?php
namespace GDO\Friends\tpl\card;

use GDO\Friends\GDO_Friendship;
use GDO\Table\GDT_ListItem;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_DeleteButton;
use GDO\User\Method\Profile;

/** @var GDO_Friendship $gdo * */
// $user = GDO_User::current();
$friend = $gdo->getFriend();
$li = GDT_Card::make()->gdo($gdo);
$li->creatorHeader('friend_friend');
$li->title('friend_relation_since', [
	$friend->renderUserName(),
	$gdo->displayRelation(),
	tt($gdo->getCreated()),
]);
$li->subtitle('user_info_subtitle', [
	$friend->renderUserName(),
	Profile::getHighestPermission($friend),
	sitename(),
	$friend->getLevel(),
]);
$li->actions()->addField(GDT_DeleteButton::make()
	->confirmText('ask_remove_friendship', [$friend->renderUserName()])
	->href(href('Friends', 'Remove', '&friend=' . $friend->getID())));
echo $li->render();
