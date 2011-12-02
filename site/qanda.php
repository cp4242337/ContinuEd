<?php
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) . '/../..' );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');
$db  =& JFactory::getDBO();
$user = &JFactory::getUser();

$courseid = $db->getEscaped(JRequest::getVar('courseid'));
$qtext  = $db->getEscaped(JRequest::getVar('qtext'));
$session =& JFactory::getSession();
$qn = 'SELECT ordering FROM #__ce_questions WHERE q_course = '.$courseid.' && q_area="qanda" ORDER BY ordering DESC LIMIT 1';
$db->setQuery($qn); $nextnum = (int)$db->loadResult()+1;
if ($user->id) {
	$qc = 'INSERT INTO #__ce_questions (q_text,q_course,q_part,q_area,q_addedby,published,q_type,q_cat,ordering) ';
	$qc .= 'VALUES ("'.$qtext.'","'.$courseid.'",1,"qanda","'.$user->id.'",0,"qanda","qanda","'.$nextnum.'")';
	$db->setQuery( $qc );
	if ($db->query()) echo 'Question Submitted';
}


