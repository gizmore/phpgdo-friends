<?php
namespace GDO\Friends;

use GDO\Core\GDO_Module;
use GDO\Date\GDT_Duration;
use GDO\UI\GDT_Link;
use GDO\Core\GDT_Checkbox;
use GDO\User\GDO_User;
use GDO\UI\GDT_Page;
use GDO\User\GDT_Level;

/**
 * GDO_Friendship and user relation module
 * 
 * @author gizmore
 * @version 6.10
 * @since 5.0
 */
final class Module_Friends extends GDO_Module
{
	##############
	### Module ###
	##############
	public int $priority = 40;
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
		return array(
			GDT_ACL::make('friendship_who')->initial('acl_all'),
			GDT_ACL::make('friendship_visible')->initial('acl_noone'),
			GDT_Level::make('friendship_level')->initial('0'),
		);
	}
	
	public function getConfig() : array
	{
		return array(
			GDT_Checkbox::make('friendship_friendslink')->initial('0'),
			GDT_Checkbox::make('friendship_guests')->initial('0'),
			GDT_Checkbox::make('friendship_relations')->initial('1'),
			GDT_Duration::make('friendship_cleanup_age')->initial('1d'),
		);
	}
	public function cfgFriendsLink() { return $this->getConfigValue('friendship_friendslink'); }
	public function cfgGuestFriendships() { return $this->getConfigValue('friendship_guests'); }
	public function cfgRelations() { return $this->getConfigValue('friendship_relations'); }
	public function cfgCleanupAge() { return $this->getConfigValue('friendship_cleanup_age'); }
	
	##############
	### Render ###
	##############
	public function renderTabs()
	{
		return $this->responsePHP('tabs.php');
	}

	public function onInitSidebar() : void
	{
		if ($this->cfgFriendsLink())
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
		 * @var \GDO\Friends\GDT_ACL $setting
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
		 * @var \GDO\Friends\GDT_ACL $setting
		 */
		$setting = $module->userSetting($from, 'friendship_visible');
		return $setting->hasAccess($user, $from, $reason);
	}
}
