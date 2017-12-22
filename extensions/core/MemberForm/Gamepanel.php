<?php
/**
 * @brief		Admin CP Member Form
 * @author		<a href='https://www.invisioncommunity.com'>Invision Power Services, Inc.</a>
 * @copyright	(c) Invision Power Services, Inc.
 * @license		https://www.invisioncommunity.com/legal/standards/
 * @package		Invision Community
 * @subpackage	Gamepanel
 * @since		22 Nov 2017
 */

namespace IPS\gamepanel\extensions\core\MemberForm;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Admin CP Member Form
 */
class _Gamepanel
{
	/**
	 * Action Buttons
	 *
	 * @param	\IPS\Member	$member	The Member
	 * @return	array
	 */
	public function actionButtons( $member )
	{
		return array(
			'id'	=> array(
				'title'		=> 'Whitelist',
				'icon'		=> 'check',
				'link'		=> \IPS\Http\Url::internal( "app=gamepanel&module=&controller=&do=&id={$member->member_id}" ),
				'class'		=> ''
			)
		);
	}

	/**
	 * Process Form
	 *
	 * @param	\IPS\Helpers\Form		$form	The form
	 * @param	\IPS\Member				$member	Existing Member
	 * @return	void
	 */
	public function process( &$form, $member )
	{	
		try
		{
			$row = \IPS\Db::i()->select("*", "gamepanel_players",array('member_id=?', $member->member_id))->first();
		}
		catch(\UnderFlowException $e)
		{
			//!?
		}
		if($row != NULL)
		{
			$player = \IPS\gamepanel\Player::load($row['id']);
		}

		if($player != NULL)
		{
			$access = \IPS\Db::i()->select("*", "gamepanel_access", array('uuid=?', $row['uuid']))->first();
			$access = \IPS\gamepanel\Access::load($access['id']);
			$form->addHeader("Player Information");
			$form->add( new \IPS\Helpers\Form\Text( 'Minecraft IGN', $player->ign, FALSE ) );
			$form->add( new \IPS\Helpers\Form\Text( 'UUID', $player->uuid, FALSE, array('disabled' => TRUE) ) );
			$form->addHeader("Whitelist");
			$form->add( new \IPS\Helpers\Form\YesNo( 'Main', $access->main, FALSE ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'Test', $access->test, FALSE ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'Games', $access->games, FALSE ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'Build', $access->build, FALSE ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'Dark Portal', $access->darkportal, FALSE ) );
		}
		else
		{
			$form->addHeader("Player Information");
			$form->add( new \IPS\Helpers\Form\Text( 'Minecraft IGN' ) );
			$form->add( new \IPS\Helpers\Form\Text( 'UUID' ) );
			$form->addHeader("Whitelist");
			$form->add( new \IPS\Helpers\Form\YesNo( 'Main' ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'Test' ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'Games' ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'Build' ) );
			$form->add( new \IPS\Helpers\Form\YesNo( 'Dark Portal' ) );	
		}
	}
	
	/**
	 * Save
	 *
	 * @param	array				$values	Values from form
	 * @param	\IPS\Member			$member	The member
	 * @return	void
	 */
	public function save( $values, &$member )
	{
		$member->example = $values['example'];
	}
}