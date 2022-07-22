<?php
namespace GDO\Friends\lang;
return [
	'cfg_friendship_who' => 'Wer darf Ihnen Freundesanfragen schicken?',
	'cfg_friendship_visible' => 'Wer darf Ihre Freunde sehen?',
	'cfg_friendship_level' => 'Was ist der minimal Nutzerlevel Ihrer Freunde?',
	'cfg_friendship_guests' => 'Freundschaften zwischen Gästen erlauben?',
	'cfg_friendship_relations' => 'Erweiterter Freundschaftsstatus nutzen?',
	'cfg_friendship_cleanup_age' => 'Gelöschte Anfragen aufräumen nach...',
	##################################################
	'gdo_friendrequest' => 'Freundschaftsanfrage',
	##################################################
	'enum_acl_all' => 'Alle',
	'enum_acl_members' => 'Mitglieder',
	'enum_acl_friends' => 'Freunde',
	'enum_acl_noone' => 'Niemand',
	'visibility' => 'Sichtbarkeit',
	##################################################
	'err_only_member_access' => 'Nur Mitglieder dürfen darauf zugreifen.',
	'err_only_friend_access' => 'Nur seine/ihre Freunde dürfen darauf zugreifen.',
	'err_only_private_access' => 'Nur der/die Nutzerin selbst darf darauf zugreifen.',
	'err_unknown_acl_setting' => 'Unbekannte ACL Einstellung: %s.',
	##################################################
	'link_friends' => 'Freunde (%s)',
	'link_add_friend' => 'Freund(in) hinzufügen',
	'link_incoming_friend_requests' => 'Eingehende Anfragen(%s)',
	'link_pending_friend_requests' => 'Gesendete Anfragen',
	##################################################
	'ft_friends_request' => 'Freund(in) hinzufügen',
	'frq_friend' => 'Name des Nutzers',
	'err_friend_self' => 'Sie können Sich nicht selbst befreunden.',
	'err_already_pending_denied' => 'Eine Anfrage an %s wurde erst kürzlich abgelehnt.',
	'err_already_pending' => 'Es gibt schon eine Anfrage an %s.',
	'err_requesting_denied' => 'Sie können diesen Nutzer nicht als Freund hinzufügen: %s.',
	'msg_friend_request_sent' => 'Ihre Anfrage wurde gesendet.',
	'err_already_related' => 'Sie und %s sind bereits befreundet.',
	##################################################
	'list_friends_friendlist' => 'Ihre Freunde (%s)',
	'friend_relation_since' => 'Ist ihr(e) %s seit %s',
	'err_friend_request' => 'Die Anfrage konnte nicht gefunden werden.',
	'msg_friends_accepted' => 'Ihr Freundschaftsstatus mit %s wurde akzeptiert.',
	'msg_friendship_deleted' => 'Ihr Freundschaftsstatus mit %s wurde gelöscht.',
	##################################################
	'list_friends_requests' => 'Freundschaftsanfragen(%s)',
	'friend_request_from' => 'Anfrage als %s von %s',
	##################################################
	'list_friends_requesting' => 'Ihre offenen Freundschafsanfragen (%s)',
	'friend_request_to' => 'Requested to be %s at %s',
	'msg_request_revoked' => 'Sie haben Ihre Anfrage zurückgezogen.',
	##################################################
	'friend_relation' => 'Beziehung',
	'enum_friend' => 'Freund',
	'enum_bestfriend' => 'Beste Freunde',
	'enum_coworker' => 'Arbeitskollegen',
	'enum_husband' => 'Ehemann',
	'enum_wife' => 'Ehefrau',
	##################################################
	'mail_subj_friend_request' => '[%s] Beziehung mit %s',
	'mail_subj_frq_denied' => '[%s] %s hat Ihre Anfrage abgelehnt',
	'mail_subj_frq_accepted' => '[%s] %s ist nun Ihr %s',
	'mail_subj_friend_removed' => '[%s] %s kündigt Ihre Freundschaft',
];
