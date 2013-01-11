<?php
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) . '/../../..' );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');
$db  =& JFactory::getDBO();
$user = &JFactory::getUser();

$cid = JRequest::getInt('courseid',0); 
$matdone=true;

//Check Mat Pages
$q  = 'SELECT mat_id FROM #__ce_material ';
$q .= 'WHERE published = 1 && mat_course = '.$cid;
$db->setQuery( $q );
$matarr = $db->loadResultArray();

if ($matarr && count($matarr) > 1) {
	$query  = 'SELECT * ';
	$query .= 'FROM #__ce_matuser ';
	$query .= 'WHERE mu_user = '.$user->id;
	$query .= ' && mu_mat IN ('.implode(",",$matarr).')';
	$db->setQuery( $query );
	$matlist = $db->loadObjectList();
	$mlist = array();
	foreach ($matlist as $m) {
		$mlist[$m->mu_mat]=$m;
	}
	foreach ($matarr as $mpd) {
		if ($mlist[$mpd]) {
			if ($mlist[$mpd]->mu_status != 'complete') $matdone=false;
		} else {
			$matdone=false;
		}
	}
}


// Check Inter Questions
$q  = 'SELECT q_id FROM #__ce_questions ';
$q .= 'WHERE published >= 1 && q_area="inter" && q_req = 1 && q_course = '.$cid;
$db->setQuery( $q );
$qarr = $db->loadAssoc();
$treq=count($qarr);

if ($qarr) {
	$query  = 'SELECT count(*) ';
	$query .= 'FROM #__ce_evalans ';
	$query .= 'WHERE course = '.$cid.' && userid = '.$user->id;
	$query .= ' && question IN ('.implode(",",$qarr).')';
	$query .= ' GROUP BY course';
	$db->setQuery( $query );
	$tans = (int)$db->loadResult();
	if ($tans != $treq) {
		$matdone=false;
	} 
	
}


if (!$matdone) {
	echo 'false';
} else {
	echo 'true';
}


