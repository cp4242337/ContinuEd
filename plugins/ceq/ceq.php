<?php
/**
 * MVid plugin for Joomla! 1.5
 * @license http://www.gnu.org/licenses/gpl.html GNU/GPL.
 * @by Mike Amundsen
 * @Copyright (C) 2008 
  */
defined( '_JEXEC' ) or die( 'Restricted access' );

class  plgContinuedCeQu extends JPlugin
{
	function plgContinuedCeQu (& $subject) {
		parent::__construct($subject);
	}
}

$mainframe->registerEvent( 'onInsertQuestion', 'CeQu' );
$mainframe->registerEvent( 'onInsertQuestionA', 'CeQuA' );

function CeQu(&$row) {
	$regex = "#{ceq}(.*?){/ceq}#s";
	$plugin =&JPluginHelper::getPlugin('continued', 'cequ');
	if (!$plugin->published){ 
		//plugin not published 
	}else  { 
		//plugin published 
	}
	$row = preg_replace_callback( $regex, 'CEQReplacer', $row );
	return $row;
}

function CeQuA(&$row) {
	$regex = "#{ceq}(.*?){/ceq}#s";
	$plugin =&JPluginHelper::getPlugin('continued', 'cequ');
	if (!$plugin->published){ 
		//plugin not published 
	}else  { 
		//plugin published 
	}
	$row = preg_replace_callback( $regex, 'CEQReplacerA', $row );
	return $row;
}

/**
* @param array An array of matches
* @return string
*/
function CEQReplacer ( &$matches ) {
	$plugin =&JPluginHelper::getPlugin('continued', 'ceq');
	$pluginParams = new JParameter( $plugin->params );
	$qid = $matches[1];	
	$user=JFactory::getUser();
	$token = JRequest::getVar('tokenid');
	global $cecfg;
	
	//Get question
	$db  =& JFactory::getDBO();
	$query = 'SELECT * FROM #__ce_questions ';
	$query .= 'WHERE id = '.$qid;
	$db->setQuery( $query );
	$qdata = $db->loadObject();
	$numans=0;
	$output='';
	
	
	$aquery = 'SELECT * FROM #__ce_evalans WHERE ans_area = "inter" && userid="'.$user->id.'" ';
	$aquery .= ' && question="'.$qid.'" ORDER BY anstime DESC';
	$db->setQuery($aquery); 
	$havans = $db->loadObject();
	
	$output .= '<div id="qi_'.$qid.'" class="continued_mat_q">';
	$output .= '<p><form name="qf_'.$qid.'">'."\n";
	$output .= '<b>'.$qdata->qtext.'</b><br>'."\n";
	$output .= '<div id="q'.$qdata->id.'_msg" class="error_msg"></div>';
	switch ($qdata->qtype) {
		case 'multi':
			$query  = 'SELECT * FROM #__ce_questions_opts ';
			$query .= 'WHERE question = '.$qid.' ORDER BY ordering ASC';
			$db->setQuery( $query );
			$qopts = $db->loadAssocList();
			foreach ($qopts as $opts) {
				$numans++;
				$output .= ' <label><input type="radio" name="q'.$qid.'" value="'.$opts['id'].'" id="q'.$qid.'"';
				if ($havans->answer==$opts['id']) $output.=' checked="checked"';
				$output .= '> '.$opts['opttxt'].'</label><br>'."\n";
			}
			break;
		}
	$output .= '<br><a href="#" id="qf'.$qid.'sub" onclick="CEQ_'.$qid.'()">';
	$output .= '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_eval.png" alt="Submit" border="0" /></a>';
	if ($cecfg['INTER_REQMSG'] && $qdata->qreq) $output .=' <span style="color:#800000"><strong>'.$cecfg['INTER_REQMSG'].'</strong></span>';
	$output .= '<br></form></p></div>'."\n";
	$output .= '<script type="text/javascript">'."\n";
	$output .= 'function CEQ_'.$qid.'(){'."\n";
	$output .= '	var ev = document.qf_'.$qid.';'."\n";
	$output .= "	erMsg = '<span style=\"color:#800000\"><b>Answer is Required</b></span>';\n";
	$output .= '	errs = false;';
	$output .= '	if(isNCheckedR(ev.q'.$qid.', erMsg,'.$numans.',"q'.$qid.'"+"_msg")) { errs=true; }'."\n";
	$output .= '	if (!errs) {'."\n";
	$output .= '		var ajaxRequest; '."\n";
	$output .= '		try{ajaxRequest = new XMLHttpRequest();'."\n";
	$output .= '		} catch (e){';
	$output .= '			try{ ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");'."\n";
	$output .= '			} catch (e) {'."\n";
	$output .= '				try{ ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP"); } '."\n";
	$output .= '				catch (e){ alert("Your browser broke!"); return false;}'."\n";
	$output .= '			}'."\n";
	$output .= '		}'."\n";
	$output .= '		ajaxRequest.onreadystatechange = function(){'."\n";
	$output .= '			if(ajaxRequest.readyState == 4){'."\n";
	$output .= '				var ajaxDisplay = document.getElementById("qi_'.$qid.'");'."\n";
	$output .= '				ajaxDisplay.innerHTML = ajaxRequest.responseText;'."\n";
	$output .= '			}'."\n";
	$output .= '		}'."\n";
	$output .= '		var queryString = "?" + "token='.$token.'&course='.$qdata->course.'&question='.$qid.'&qans=" + getCheckedValue(ev.q'.$qid.');'."\n";
	$output .= '		ajaxRequest.open("GET", "components/com_continued/interq.php" + queryString, true);'."\n";
	$output .= '		ajaxRequest.send(null);'."\n"; 
	if (!$havans) $output .= '		numans++;'."\n";
	if ($qdata->qreq) { $output .= "		document.continued_material.req".$qdata->id."d.value=1;\n"; }
	$output .= '	}'."\n";
	$output .= '	return !errs;'."\n";
	$output .= '}'."\n";
	$output .= '</script>'."\n";

	return $output;
}

function CEQReplacerA(&$matches ) {
	
	$plugin =&JPluginHelper::getPlugin('continued', 'ceq');
	$pluginParams = new JParameter( $plugin->params );
	$qid = $matches[1];	
	$token = JRequest::getVar('tokenid');
	global $cecfg;
	$output='';
	
	//Get question
	$db  =& JFactory::getDBO();
	//show results
	$query = 'SELECT * FROM #__ce_questions ';
	$query .= 'WHERE id = '.$qid;
	$db->setQuery( $query );
	$qdata = $db->loadObject();
	$anscor=false;
	$output .= '<p><b>'.$qdata->qtext.'</b>';
	switch ($qdata->qtype) {
	case 'multi':
		$output .= '<table width="680" border="0">'; 
		$qnum = 'SELECT count(question) FROM #__ce_evalans WHERE question = '.$qid.' GROUP BY question';
		$db->setQuery( $qnum );
		$qnums = $db->loadAssoc();
		$numr=$qnums['count(question)'];
		$query  = 'SELECT o.*,COUNT(r.answer) FROM #__ce_questions_opts as o ';
		$query .= 'LEFT JOIN #__ce_evalans as r ON o.id = r.answer ';
		$query .= 'WHERE o.question = '.$qid.' GROUP BY o.id ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$ans = JRequest::getVar('q'.$ques['q_id']);
		$barc=1; $gper=0; $ansper=0; $gperid = 0;
		//$cbg = "#FFFFFF";
		$expl='';
		foreach ($qopts as $opts) {
			if ($numr != 0) $per = $opts['COUNT(r.answer)']/$numr; else $per=1;
			$output .= '<tr><td valign="center" align="left" width="340">';
			if ($opts['correct']) {
				if ($qdata->q_expl) $expl=$qdata->q_expl;
				else $expl.=$opts['optexpl'];
			}
			
			if ($opts['correct']) $output .= '<b>'.$opts['opttxt'].'</b>';
			else $output .= $opts['opttxt'];
			$output .= '</td>';
			$output .= '<td valign="center" width="340"><img src="media/com_continued/bar_'.$barc.'.jpg" height="15" width="'.($per*320).'" align="absmiddle"> ';
			$output .= '<b>'.$opts['COUNT(r.answer)'].'</b></td></tr>';
			$barc = $barc + 1;
			if ($barc==5) $barc=1;
			if ($gper < $per) { $gper = $per; $gperid = $opts['id']; }
		}
		$output .= '</table></p>';
		break;
		
	}
	if ($expl) {
		$output .= '<div class="continued_mat_qexpl">'.$expl.'</div>';
	}
	return $output;
}
?>
