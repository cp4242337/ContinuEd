<?php
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) . '/../..' );
define( 'DS', DIRECTORY_SEPARATOR );

require_once(JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once(JPATH_BASE.DS.'includes'.DS.'framework.php' );
require_once('helpers'.DS.'continued.php');
require_once('helpers'.DS.'paypal.php');

$app =& JFactory::getApplication('site');
$db  =& JFactory::getDBO();
$cfg=ContinuedHelper::getConfig();
$paypal = new PayPalAPI($cfg->paypal_mode,$cfg->paypal_username,$cfg->paypal_password,$cfg->paypal_signature);
$paypal->ipnResponse();






