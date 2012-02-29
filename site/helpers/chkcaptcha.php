<?php
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) . '/../../..' );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

include_once JPATH_BASE.DS.'components'.DS.'com_continued'.DS.'lib'.DS .'securimage'.DS .'securimage.php';

$session=JFactory::getSession();

$securimage = new Securimage();
$securimage->session_name = $session->getName();

$mainframe =& JFactory::getApplication('site');

$data = JRequest::getVar('jform', array(), 'post', 'array'); 
if ($securimage->check($data['captcha']) == false) {
	echo 'false';
} else {
	echo 'true';
}


