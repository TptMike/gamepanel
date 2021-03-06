<?php
/**
 * @brief		Gamepanel Application Class
 * @author		<a href='https://github.com/TptMike'>TptMike</a>
 * @copyright	(c) 2017 TptMike
 * @package		Invision Community
 * @subpackage	Gamepanel
 * @since		21 Oct 2017
 * @version		
 */
 
namespace IPS\gamepanel;

/**
 * Gamepanel Application Class
 */
class _Application extends \IPS\Application
{
	
	/**
	 * [Node] Get Icon for tree
	 *
	 * @note	Return the class for the icon (e.g. 'globe')
	 * @return	string|null
	 */
	protected function get__icon()
	{
		return 'gamepad';
	}
}