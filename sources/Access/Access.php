<?php
/**
 * @brief		Access Model
 * @author		<a href='https://www.github.com/TptMike'>TptMike.</a>
 * @copyright	(c) TptMike
 * @license		MIT
 * @package		Gamepanel
 * @since		27 Nov 2017
 */

 namespace IPS\gamepanel;

class _Access extends \IPS\Patterns\ActiveRecord
{
    public static $application = 'gamepanel';
    public static $databaseTable = 'gamepanel_access';
    public static $databaseColumnId = 'id';
    protected static $databaseIdFields = array( 'uuid' );    
    
    	/**
	 * Load Record
	 * We override it so we return a guest object for a non-existant member
	 *
	 * @see		\IPS\Db::build
	 * @param	int|string	$id					ID
	 * @param	string		$idField			The database column that the $id parameter pertains to (NULL will use static::$databaseColumnId)
	 * @param	mixed		$extraWhereClause	Additional where clause (see \IPS\Db::build for details)
	 * @return	static
	 */
	public static function load( $id, $idField=NULL, $extraWhereClause=NULL )
	{
		try
		{
			if( $id === NULL OR $id === 0 OR $id === '' )
			{
				$classname = get_called_class();
				return new $classname;
			}
			else
			{
				$access = parent::load( $id, $idField, $extraWhereClause );
				return $access;
			}
		}
		catch ( \OutOfRangeException $e )
		{
			$classname = get_called_class();
			return new $classname;
		}
	}
	/**
	 * [ActiveRecord] Save Changed Columns
	 *
	 * @return	void
	 * @note	We have to be careful when upgrading in case we are coming from an older version
	 */
	public function save()
	{
		parent::save();
	}
}