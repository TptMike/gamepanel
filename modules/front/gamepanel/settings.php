<?php


namespace IPS\gamepanel\modules\front\gamepanel;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * settings
 */
class _settings extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Output::i()->sidebar['enabled'] = FALSE;
		parent::execute();
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage()
	{
		/* Only logged in members */
		if ( !\IPS\Member::loggedIn()->member_id )
		{
			\IPS\Output::i()->error( 'no_module_permission_guest', '2C122/1', 403, '' );
		}
		
		/* Work out output */
		$area = \IPS\Request::i()->area ?: 'overview';
		if ( method_exists( $this, "_{$area}" ) )
		{
			$output = call_user_func( array( $this, "_{$area}" ) );
		}
		
		/* Display */
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('module__gamepanel_settings');
		\IPS\Output::i()->breadcrumb[] = array( NULL, \IPS\Member::loggedIn()->language()->addToStack('module__gamepanel_settings') );
		
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'settings' )->index();
	}

		/**
	 * Overview
	 *
	 * @return	string
	 */
	protected function _overview()
	{
		$services = array();

		foreach ( \IPS\core\ProfileSync\ProfileSyncAbstract::services() as $key => $class )
		{
			$services[$key] = new $class( \IPS\Member::loggedIn() );
		}
		
		$nextStep = NULL;
		if ( $completed = \IPS\Member::loggedIn()->profileCompletion() AND count( $completed['suggested'] ) )
		{
			foreach( $completed['suggested'] AS $id => $complete )
			{
				if ( !$complete )
				{
					$nextStep = \IPS\Member\ProfileStep::load( $id );
					break;
				}
			}
		}
				
		return \IPS\Theme::i()->getTemplate( 'settings' )->settingsOverview();
	}
	// Create new methods with the same name as the 'do' parameter which should execute it
}