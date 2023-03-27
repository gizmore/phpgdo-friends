<?php
namespace GDO\Friends\Method;

use GDO\Core\Application;
use GDO\Cronjob\MethodCronjob;
use GDO\Date\Time;
use GDO\DB\Database;
use GDO\Friends\GDO_FriendRequest;
use GDO\Friends\Module_Friends;

final class Cleanup extends MethodCronjob
{

	public function run(): void
	{
		$module = Module_Friends::instance();
		$cut = Time::getDate(Application::$TIME - $module->cfgCleanupAge());
		GDO_FriendRequest::table()->deleteWhere("frq_denied < '$cut'");
		if ($affected = Database::instance()->affectedRows())
		{
			$this->logNotice(sprintf('Deleted %s old denied friend requests.', $affected));
		}
	}

}
