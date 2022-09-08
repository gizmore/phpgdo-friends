<?php
namespace GDO\Friends\lang;
return [
	'friend_who' => 'Who may send you friend requests?',
	'friends_show' => 'Who may see your friends?',
	'friends_level' => 'What is your friend\'s minimum level?',
	'cfg_friendship_guests' => 'Allow relations between guests?',
	'cfg_friendship_relations' => 'Use extend relation states?',
	'cfg_friendship_cleanup_age' => 'Clear deleted requests after N',
	##################################################
	'gdo_friendrequest' => 'Friendrequest',
	##################################################
	'link_friends' => 'Friends (%s)',
	'link_add_friend' => 'Add a friend',
	'link_incoming_friend_requests' => 'Incoming requests (%s)',
	'link_pending_friend_requests' => 'Sent requests',
	##################################################
	'mt_friends_request' => 'Add a friend',
	'frq_friend' => 'Name of your friend',
	'err_friend_self' => 'You cannot befriend yourself here.',
	'err_already_pending_denied' => 'A request for %s has been denied or cancelled recently.',
	'err_already_pending' => 'There is already a pending request for %s.',
	'err_requesting_denied' => 'You cannot request a friendship with this user: %s.',
	'msg_friend_request_sent' => 'Your request has been sent.',
	'err_already_related' => 'You and %s are already related.',
	##################################################
	'list_friends_friendlist' => 'Your friends (%s)',
	'friend_relation_since' => '%s is listed as your %s since %s',
	'err_friend_request' => 'The relationship request could not been found.',
	'msg_friends_accepted' => 'Your relationship status with %s has been accepted.',
	'msg_friendship_deleted' => 'Your relationship status with %s has been deleted.',
	##################################################
	'list_friends_requests' => 'Friend Requests (%s)',
	'friend_request_from' => '%s requested you to be their %s on %s.',
	##################################################
	'list_friends_requesting' => 'Pending requests from you (%s)',
	'friend_request_to' => 'You asked %s to be your %s on %s.',
	'msg_request_revoked' => 'You have revoked your friend request. You cannot re-request until a cleanup.',
	##################################################
	'friend_relation' => 'Relation',
	'enum_friend' => 'Friend',
	'enum_bestfriend' => 'Best Friend',
	'enum_coworker' => 'Coworker',
	'enum_husband' => 'Husband',
	'enum_wife' => 'Wife',
	'enum_aunt' => 'Aunt',
	'enum_nephew' => 'Nephew',
	
	##################################################
	'mail_subj_friend_request' => '[%s] Relationship with %s',
	'mail_subj_frq_denied' => '[%s] %s denied the relationship',
	'mail_subj_frq_accepted' => '[%s] %s is now your %s',
	'mail_subj_friend_removed' => '[%s] %s removed your relationship',
	##################################################
	'mt_friends_friendlist' => 'Friendlist',
	'mt_friends_requesting' => 'Friend requesting',
	'mt_friends_requests' => 'Friend requests',
	'mt_friends_deny' => 'Deny Friend Request',
	'mt_friends_remove' => 'Remove Friend',
	'mt_friends_removeto' => 'Revoke Request',
	'mt_friends_removefrom' => 'Revoke Request',
	'mt_friends_accept' => 'Accept Friend Request',
	
	'ask_remove_friendship' => 'Do you wanna end your relationship with %s?',
	'ask_deny_friendship' => 'Do you want to deny the request?',
];
