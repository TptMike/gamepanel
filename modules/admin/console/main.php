<?php


namespace IPS\gamepanel\modules\admin\console;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * main
 */
class _main extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'main_manage' );
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
		$api = new \IPS\gamepanel\MulticraftAPI('http://mcp.cardinalnetworks.com/api.php', 'NetworkAdmin', 'P55R@CEciRwmiL');
		//$response = $api->getServerOwner(1);
		//$data = implode(",", $response);
		//print_r($api->getServerStatus(1, TRUE));

		/* Build form */
		$form = new \IPS\Helpers\Form;
		$form->add(new \IPS\Helpers\Form\TextArea('console', false));
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'console' )->consoleMain($form);
	}
	
	// Create new methods with the same name as the 'do' parameter which should execute it
}