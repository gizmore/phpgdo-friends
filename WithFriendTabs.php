<?php
namespace GDO\Friends;

/**
 * Add friends tabs to a method.
 * 
 * @author gizmore
 *
 */
trait WithFriendTabs
{
	public function isGuestAllowed() : bool { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	public function onRenderTabs() : void
	{
		Module_Friends::instance()->renderTabs();
	}
	
}