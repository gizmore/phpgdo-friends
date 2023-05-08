<?php
namespace GDO\Friends;

use GDO\Core\GDO_Exception;
use GDO\Core\GDT_Enum;
use GDO\Language\Trans;

/**
 * Friendship relation.
 *
 * @version 7.0.1
 * @since 6.7.1
 * @author gizmore
 */
final class GDT_FriendRelation extends GDT_Enum
{

	public static array $TYPES = [
		'friend' => 'friend',
		'bestfriend' => 'bestfriend',
		'coworker' => 'coworker',
		'husband' => 'wife',
		'aunt' => 'nephew',
	];

	protected function __construct()
	{
		parent::__construct();
		$this->label('friend_relation');
		$this->enumValues(...array_unique(array_merge(array_keys(self::$TYPES), array_values(self::$TYPES))));
	}

	public static function displayRelation($relation)
	{
		return self::displayRelationISO(Trans::$ISO, $relation);
	}

	public static function displayRelationISO(string $iso, string $relation)
	{
		return tiso($iso, 'enum_' . $relation);
	}

	public static function reverseRelation($relation)
	{
		if (isset(self::$TYPES[$relation]))
		{
			return self::$TYPES[$relation];
		}
		elseif (false !== ($index = array_search($relation, self::$TYPES, true)))
		{
			return $index;
		}
		else
		{
			throw new GDO_Exception('err_reverse_friends_relation');
		}
	}

}
