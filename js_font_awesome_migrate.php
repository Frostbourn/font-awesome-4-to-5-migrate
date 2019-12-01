<?php
/**
 * @copyright	Copyright (c) 2018 JS Network Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemJs_Font_Awesome_Migrate extends JPlugin {

	function __construct( &$subject, $params )
	{
		parent::__construct($subject, $params);

	}

	function onBeforeCompileHead() 
	{	
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		JHtml::_('jquery.framework');

		if ($app->isSite()) {
			
			$item_params = $this->params->get('items_fa'); 
			$item_url = $this->params->get('url_fa'); 
			$document->addScript('/media/js_font_awesome_migrate/js/fontawesome4to5.js');
			$document->addStyleSheet($item_url, 'https://use.fontawesome.com/releases/v5.5.0/css/all.css');

        	$json = json_decode($item_params, true);

        	if (is_array($json)) {
            if (!function_exists('items')) { // <--change
                function items($array)
                { // <--change
                    $result = array();
                    foreach ($array as $sub) {
                        foreach ($sub as $k => $v) {
                            $result[$k][] = $v;
                        }
                    }
                    return $result;
                }
            }
            $items_array = items($json); // <--change
            $items     = $items_array; // this is default items array
        } 

        if ($items) { 
        	$missing_icons = ' jQuery(document).ready(function($){ $.fontmigrate({ ';
        	 	foreach ($items as $key => $value) { $missing_icons .= '"' .$value[0]. '": "' .$value[1]. '",'; } 
        	$missing_icons .= '});});';
		}

		$document->addScriptDeclaration($missing_icons);

        return;

        }
	}
}