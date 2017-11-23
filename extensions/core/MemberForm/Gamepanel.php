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
		$form->add( new \IPS\Helpers\Form\Text( 'Minecraft IGN' ) );
		$form->add( new \IPS\Helpers\Form\Text( 'UUID' ) );
		$form->add( new \IPS\Helpers\Form\YesNo( 'Whitelist' ) );
		$form->add( new \IPS\Helpers\Form\YesNo( 'Build' ) );
		$form->add( new \IPS\Helpers\Form\YesNo( 'Dark Portal' ) );
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