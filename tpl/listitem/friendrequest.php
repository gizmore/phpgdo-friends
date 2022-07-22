<?php
namespace GDO\Friends\tpl\listitem;

use GDO\Friends\GDO_FriendRequest;
use GDO\Table\GDT_ListItem;
use GDO\UI\GDT_Button;
use GDO\User\GDO_User;

/** @var $gdo GDO_FriendRequest **/

$user = GDO_User::current();
$friend = $gdo->getFriend();
$friendship = $gdo; # gdo is friendship

$li = GDT_ListItem::make()->gdo($gdo);
$li->content($gdo->getMessageColumn());

if ($friendship->isFrom($user))
{
	$li->creatorHeader('frq_friend');
	$li->actions()->addFields(
		GDT_Button::make()->icon('delete')->href(href('Friends', 'RemoveTo', '&friend='.$friend->getID())),
	);
}
else
{
	$li->creatorHeader('frq_user');
	$li->actions()->addFields(
		GDT_Button::make()->icon('person_add')->href(href('Friends', 'AcceptFrom', '&user='.$friendship->getUser()->getID())),
		GDT_Button::make()->icon('block')->href(href('Friends', 'RemoveFrom', '&user='.$friendship->getUser()->getID()))
	);
}

echo $li->render();
