<?php


namespace IPS\gamepanel\modules\front\gamepanel;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * index
 */
class _index extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		
		parent::execute();
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage()
	{
		// This is the default method if no 'do' parameter is specified
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('__app_gamepanel');
		/*if(!\IPS\Member::loggedIn()->canAccessModule(\IPS\Application\Module::get('gamepanel', 'gamepanel')))
		{
		    return FALSE;
		}*/
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'index' )->index();
	}
	
	// Create new methods with the same name as the 'do' parameter which should execute it
}