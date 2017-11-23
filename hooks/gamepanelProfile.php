//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class gamepanel_hook_gamepanelProfile extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'hovercard' => 
  array (
    0 => 
    array (
      'selector' => 'div.ipsPad_half.cUserHovercard > div.cUserHovercard_data > ul.ipsDataList.ipsDataList_reducedSpacing',
      'type' => 'add_inside_end',
      'content' => '{template="hover" group="global" location="front" app="gamepanel" params="$member"}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
