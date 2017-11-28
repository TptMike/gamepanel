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
		if ( !\IPS\Request::i()->isAjax() )
		{
			if ( \IPS\Request::i()->service )
			{
				$area = "{$area}_" . \IPS\Request::i()->service;
			}
            
            \IPS\Output::i()->cssFiles	= array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'styles/settings.css' ) );
            
            if ( \IPS\Theme::i()->settings['responsive'] )
            {
                \IPS\Output::i()->cssFiles	= array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'styles/settings_responsive.css' ) );
            }
            
            if ( $output )
            {
				\IPS\Output::i()->output .= $this->_wrapOutputInTemplate( $area, $output );
			}
		}
		elseif ( $output )
		{
			\IPS\Output::i()->output .= $output;
		}
		//\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'settings' )->index();
	}

/**
	 * Wrap output in template
	 *
	 * @param	string	$area	Active area
	 * @param	string	$output	Output
	 * @return	string
	 */
	protected function _wrapOutputInTemplate( $area, $output )
	{
		/* What can we do? */
		$canChangeEmail = FALSE;
		$canChangePassword = FALSE;
		$canChangeUsername = FALSE;
		$canConfigureMfa = FALSE;
		foreach ( \IPS\Login::handlers( TRUE ) as $k => $handler )
		{
			if ( \IPS\Member::loggedIn()->group['g_dname_changes'] and $handler->canChange( 'username', \IPS\Member::loggedIn() ) )
			{
				$canChangeUsername = TRUE;
			}
			if ( $handler->canChange( 'email', \IPS\Member::loggedIn() ) )
			{
				$canChangeEmail = TRUE;
			}
			if ( $handler->canChange( 'password', \IPS\Member::loggedIn() ) )
			{
				$canChangePassword = TRUE;
			}
		}
		foreach ( \IPS\MFA\MFAHandler::handlers() as $handler )
		{
			if ( $handler->isEnabled() and $handler->memberCanUseHandler( \IPS\Member::loggedIn() ) )
			{
				$canConfigureMfa = TRUE;
				break;
			}
		}

		$sigLimits = explode( ":", \IPS\Member::loggedIn()->group['g_signature_limits'] );
		$canChangeSignature = (bool) ( \IPS\Settings::i()->signatures_enabled && !$sigLimits[0]	);
				
		/* Add sync services */
		$services = \IPS\core\ProfileSync\ProfileSyncAbstract::services();
		
		/* Return */
		return \IPS\Theme::i()->getTemplate( 'settings' )->settings( $area, $output, $canChangeEmail, $canChangePassword, $canChangeUsername, $canChangeSignature, $services, $canConfigureMfa );
	}

		/**
	 * Overview
	 *
	 * @return	string
	 */
	protected function _overview()
	{
		$services = array();
		$row = NULL;
		
		//Load player data
		try
		{
			$row = \IPS\Db::i()->select("*", "gamepanel_players",array('member_id=?', \IPS\Member::loggedIn()->member_id))->first();
			
		}
		catch(\UnderFlowException $e)
		{
			//!?
		}
		//Does it exist?
		if($row == NULL || $row == "")
		{
			$player = new \IPS\gamepanel\Player;
			$player['ign'] = "";
		}
		else
		{
			$player = \IPS\gamepanel\Player::load($row['id']);
		}

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
				
		return \IPS\Theme::i()->getTemplate( 'settings' )->settingsOverview($player);
	}
	/**
	 * Name
	 * @return string
	 */
	protected function _name()
	{
		$row = NULL;
		//$player = \IPS\Gamepanel::load();

		//Load player data
		try
		{
			$row = \IPS\Db::i()->select("*", "gamepanel_players",array('member_id=?', \IPS\Member::loggedIn()->member_id))->first();
		}
		catch(\UnderFlowException $e)
		{
			//!?
		}
		//Does it exist?
		if($row == NULL || $row == "")
		{
			//nope, add to db
			$row = \IPS\Db::i()->insert('gamepanel_players', array('ign' => NULL, 'uuid' => NULL, 'member_id' => \IPS\Member::loggedIn()->member_id));
		}

		$player = \IPS\gamepanel\Player::load(8);
		/* Build form */
		$form = new \IPS\Helpers\Form;
		$form->add( new \IPS\Helpers\Form\Text( 'gp_ign', $player->ign, TRUE));

		/* Handle Submissions */
		if ( $values = $form->values() )
		{
			$player->ign = $values['gp_ign'];
			$player->uuid = \IPS\gamepanel\MojangAPI::getUuid($values['gp_ign']);
			$player->save();
			//We're done (?)
			\IPS\Output::i()->redirect( \IPS\Http\Url::internal( 'app=gamepanel&module=gamepanel&controller=settings', 'front', 'settings' ));			
		}

		return \IPS\Theme::i()->getTemplate( 'settings' )->settingsName($form, $player);
	}
	// Create new methods with the same name as the 'do' parameter which should execute it
}