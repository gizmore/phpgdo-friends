<?php
namespace GDO\Friends;

use GDO\Core\GDO_Module;
use GDO\Date\GDT_Duration;
use GDO\UI\GDT_Link;
use GDO\Core\GDT_Checkbox;
use GDO\User\GDO_User;
use GDO\User\GDT_ACL;
use GDO\UI\GDT_Page;
use GDO\User\GDT_Level;
use GDO\UI\GDT_Bar;

/**
 * GDO_Friendship and user relation module
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 5.0.0
 */
final class Module_Friends extends GDO_Module
{
	##############
	### Module ###
	##############
	public int $priority = 30;
	public function onLoadLanguage() : void { $this->loadLanguage('lang/friends'); }
	public function getClasses() : array
	{
		return [
			GDO_Friendship::class,
			GDO_FriendRequest::class,
		];
	}

	##############
	### Config ###
	##############
	public function getUserSettings()
	{
		return [
			GDT_ACL::make('friend_who')->initial('acl_all')->aclcapable(),
			GDT_ACL::make('friends_show')->initial('acl_noone')->aclcapable(),
			GDT_Level::make('friend_level')->initial('0'),
		];
	}
	
	public function getConfig() : array
	{
		return [
			GDT_Checkbox::make('hook_sidebar')->initial('1'),
			GDT_Checkbox::make('friendship_guests')->initial('0'),
			GDT_Checkbox::make('friendship_relations')->initial('1'),
			GDT_Duration::make('friendship_cleanup_age')->initial('1d'),
		];
	}
	public function cfgHookSidebar() { return $this->getConfigValue('hook_sidebar'); }
	public function cfgGuestFriendships() { return $this->getConfigValue('friendship_guests'); }
	public function cfgRelations() { return $this->getConfigValue('friendship_relations'); }
	public function cfgCleanupAge() { return $this->getConfigValue('friendship_cleanup_age'); }
	
	##############
	### Render ###
	##############
	public function renderTabs()
	{
		$nav = GDT_Page::instance()->topResponse();
		$user = GDO_User::current();
		$bar = GDT_Bar::make()->horizontal();
		$friends = GDO_Friendship::count($user);
		$incoming = GDO_FriendRequest::countIncomingFor($user);
		$link3 = GDT_Link::make('link_incoming_friend_requests')->label('link_incoming_friend_requests', [$incoming])->href(href('Friends', 'Requests'));
		if ($incoming)
		{
			$link3->icon('alert');
		}
		$bar->addFields(
			GDT_Link::make('link_add_friend')->icon('add')->href(href('Friends', 'Request')),
			GDT_Link::make('link_friends')->label('link_friends', [$friends])->icon('group')->href(href('Friends', 'FriendList')),
			$link3,
			GDT_Link::make('link_pending_friend_requests')->icon('wait')->href(href('Friends', 'Requesting')),
			);
		$nav->addField($bar);
	}

	public function onInitSidebar() : void
	{
		if ($this->cfgHookSidebar())
		{
		    $user = GDO_User::current();
		    if ($user->isAuthenticated())
		    {
		        $count = GDO_Friendship::count($user);
		        $link = GDT_Link::make('link_friends')->label('link_friends', [$count])->href(href('Friends', 'FriendList'));
		        if (GDO_FriendRequest::countIncomingFor($user))
		        {
		            $link->icon('alert');
		        }
		        GDT_Page::$INSTANCE->rightBar()->addField($link);
		    }
		}
	}
	
	#####################
	### Setting Perms ###
	#####################
	public function canRequest(GDO_User $to, &$reason)
	{
		$user = GDO_User::current();
		$module = Module_Friends::instance();
		
		# Check level
		$level = $module->userSettingVar($to, 'friendship_level');
		if ($level > $user->getLevel())
		{
			$reason = t('err_level_required', [$level]);
			return false;
		}
		
		# Check user
		/**
		 * @var GDT_ACL $setting
		 */
		$setting = $module->userSetting($to, 'friendship_who');
		return $setting->hasAccess($user, $to, $reason);
	}
	
	public function canViewFriends(GDO_User $from, &$reason)
	{
		$module = Module_Friends::instance();

		# Self
		$user = GDO_User::current();
		if ($user === $from)
		{
			return true;
		}

		# Other
		/**
		 * @var GDT_ACL $setting
		 */
		$setting = $module->userSetting($from, 'friendship_visible');
		return $setting->hasAccess($user, $from, $reason);
	}
}
