<?php
namespace GDO\Friends\tpl\listitem;
use GDO\Friends\GDO_Friendship;
use GDO\Table\GDT_ListItem;
use GDO\UI\GDT_Button;
/** @var $gdo GDO_Friendship **/
$friend = $gdo->getFriend();
$li = GDT_ListItem::make()->gdo($gdo);
$li->creatorHeader('friend_friend');
$li->title('friend_relation_since', [$gdo->displayRelation(), tt($gdo->getCreated())]);
$li->actions()->addField(GDT_Button::make()->icon('delete')->href(href('Friends', 'Remove', '&friend='.$friend->getID())));
echo $li->render();
