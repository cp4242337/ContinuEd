<?php
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) . '/../..' );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');
$cfg =& JFactory::getConfig();
$db  =& JFactory::getDBO();
$user = &JFactory::getUser();

$courseid = JRequest::getVar('course');
$qid  = JRequest::getVar('question');
$qans  = JRequest::getVar('qans');
$token  = JRequest::getVar('token');
$userid = $user->id;
$session =& JFactory::getSession();


//save answer, remove old answer if there
$queryd = 'DELETE FROM #__ce_evalans WHERE question= '.$qid.' && userid="'.$userid.'" && course="'.$courseid.'"';
$db->setQuery($queryd);
$db->query();
$qc = 'INSERT INTO #__ce_evalans (userid,course,question,part,answer,sessionid,tokenid,ans_area) ';
$qc .= 'VALUES ('.$userid.','.$courseid.','.$qid.',1,"'.$qans.'","'.$session->getId().'","'.$tokenid.'","inter")';
$db->setQuery( $qc );
$db->query();

//show results
$query = 'SELECT * FROM #__ce_questions ';
$query .= 'WHERE id = '.$qid;
$db->setQuery( $query );
$qdata = $db->loadObject();
$anscor=false;
echo '<p><b>'.$qdata->qtext.'</b><br /><br />';
switch ($qdata->qtype) {
case 'multi':
	echo '<table width="100%" border="0">'; 
	$qnum = 'SELECT count(question) FROM #__ce_evalans WHERE question = '.$qid.' GROUP BY question';
	$db->setQuery( $qnum );
	$qnums = $db->loadAssoc();
	$numr=$qnums['count(question)'];
	$query  = 'SELECT o.* FROM #__ce_questions_opts as o ';
	$query .= 'WHERE o.question = '.$qid.' ORDER BY ordering ASC';
	$db->setQuery( $query );
	$qopts = $db->loadObjectList();
	foreach ($qopts as &$o) {
		$qa = 'SELECT count(*) FROM #__ce_evalans WHERE question = '.$qid.' && answer = '.$o->id.' GROUP BY answer';
		$db->setQuery($qa);
		$o->anscount = $db->loadResult();
		if ($o->anscount == "") $o->anscount = 0;
	}
	$ans = JRequest::getVar('q'.$ques['q_id']);
	$barc=1; $gper=0; $ansper=0; $gperid = 0;
	//$cbg = "#FFFFFF";
	foreach ($qopts as $opts) {
		if ($numr != 0) $per = $opts->anscount/$numr; else $per=1;
		echo '<tr><td valign="center" align="left">';
		if ($qans == $opts->id && $opts->correct) {
			$anscor=true;
		}
		
		if ($opts->correct) echo '<b>'.$opts->opttxt.'</b>';
		else echo $opts->opttxt;
		echo '</td>';
		echo '<td valign="center" width="320"><img src="media/com_continued/bar_'.$barc.'.jpg" height="15" width="'.($per*300).'" align="absmiddle"> ';
		echo '<b>'.$opts->anscount.'</b></td></tr>';
		$barc = $barc + 1;
		if ($barc==5) $barc=1;
		if ($gper < $per) { $gper = $per; $gperid = $opts->id; }
		if ($qans==$opts->id) {
			if ($qdata->q_expl) $expl=$qdata->q_expl;
			else $expl=$opts->optexpl;
		}
	}
	echo '</table></p>';
	break;
	
}
if ($expl) {
	echo '<div class="continued_mat_qexpl">'.$expl.'</div>';
}


