<?php
/**
 * MVid plugin for Joomla! 1.5
 * @license http://www.gnu.org/licenses/gpl.html GNU/GPL.
 * @by Mike Amundsen
 * @Copyright (C) 2008 
  */
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgContinuedCeQu extends JPlugin
{

	public function onContinuEdPrepare(&$text) {
		$cecfg = ContinuEdHelper::getConfig();
		if ($cecfg->mams) {
			$regex = "#{ceq}(.*?){/ceq}#s";
			$plugin =&JPluginHelper::getPlugin('continued', 'cequ');
			if (!$plugin->published){
				//plugin not published
			}else  {
				//plugin published
			}
			$matched = preg_match_all( $regex, $text, $matches, PREG_SET_ORDER );
			if ($matches) {
				foreach ($matches as $match) {
						
					$matcheslist =  explode(',',$match[1]);
						
					$qid = trim($matcheslist[0]);
					$ans = $this->getQuestionAnswer($qid);
					
					if ($ans) {
						$qtext=$this->getAnswerData($qid);
					} else {
						$qtext=$this->getQuestionData($qid);
					}
					
					$text = preg_replace("|$match[0]|", $qtext, $text, 1);
					
				}
			}
		}
	}
	
	protected function getQuestionAnswer($qid) {
		$db  =& JFactory::getDBO();
		$user=JFactory::getUser();
		$aquery = 'SELECT * FROM #__ce_evalans WHERE ans_area = "inter" && userid="'.$user->id.'" ';
		$aquery .= ' && question="'.$qid.'" ORDER BY anstime DESC';
		$db->setQuery($aquery);
		return $db->loadObject();
	}
	
	protected function getQuestionData ( $qid ) {
		$user=JFactory::getUser();
		$token = JRequest::getVar('tokenid');
		$cecfg = ContinuEdHelper::getConfig();
	
		//Get question
		$db  =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_id = '.$qid;
		$db->setQuery( $query );
		$qdata = $db->loadObject();
		$numans=0;
		$output='';
	
		$output .= '<div id="qi_'.$qid.'" class="continued_mat_q">';
		$output .= '<p><form name="qf_'.$qid.'">'."\n";
		$output .= '<b>'.$qdata->q_text.'</b><br>'."\n";
		$output .= '<div id="q'.$qdata->q_id.'_msg" class="error_msg"></div>';
		switch ($qdata->q_type) {
			case 'multi':
				$query  = 'SELECT * FROM #__ce_questions_opts ';
				$query .= 'WHERE opt_question = '.$qid.' ORDER BY ordering ASC';
				$db->setQuery( $query );
				$qopts = $db->loadAssocList();
				foreach ($qopts as $opts) {
					$numans++;
					$output .= ' <label><input type="radio" name="q'.$qid.'" value="'.$opts['opt_id'].'" id="q'.$qid.'"';
					$output .= '> '.$opts['opt_text'].'</label><br>'."\n";
				}
				break;
		}
		$output .= '<br><a href="javascript:void(0);" id="qf'.$qid.'sub" onclick="CEQ_'.$qid.'()" class="cebutton">';
		$output .= 'Submit</a>';
		if ($cecfg->INTER_REQMSG && $qdata->q_req) $output .=' <span style="color:#800000"><strong>'.$cecfg->INTER_REQMSG.'</strong></span>';
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
		$output .= '		var queryString = "?" + "token='.$token.'&course='.$qdata->q_course.'&question='.$qid.'&qans=" + getCheckedValue(ev.q'.$qid.');'."\n";
		$output .= '		ajaxRequest.open("GET", "components/com_continued/interq.php" + queryString, true);'."\n";
		$output .= '		ajaxRequest.send(null);'."\n";
		if (!$havans) $output .= '		numans++;'."\n";
		if ($qdata->qreq) {
			$output .= "		document.continued_material.req".$qdata->q_id."d.value=1;\n";
		}
		$output .= '	}'."\n";
		$output .= '	return !errs;'."\n";
		$output .= '}'."\n";
		$output .= '</script>'."\n";
	
		return $output;
	}
	
	protected function getAnswerData($qid ) {
	
		$token = JRequest::getVar('tokenid');
		$cecfg = ContinuEdHelper::getConfig();
		$output='';
	
		//Get question
		$db  =& JFactory::getDBO();
		//show results
		$query = 'SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_id = '.$qid;
		$db->setQuery( $query );
		$qdata = $db->loadObject();
		$anscor=false;
		$output .= '<p><b>'.$qdata->q_text.'</b>';
		switch ($qdata->q_type) {
			case 'multi':
				$output .= '<table width="680" border="0">';
				$qnum = 'SELECT count(question) FROM #__ce_evalans WHERE question = '.$qid.' GROUP BY question';
				$db->setQuery( $qnum );
				$qnums = $db->loadAssoc();
				$numr=$qnums['count(question)'];
				$query  = 'SELECT o.*,COUNT(r.answer) FROM #__ce_questions_opts as o ';
				$query .= 'LEFT JOIN #__ce_evalans as r ON o.opt_id = r.answer ';
				$query .= 'WHERE o.opt_question = '.$qid.' GROUP BY o.opt_id ORDER BY ordering ASC';
				$db->setQuery( $query );
				$qopts = $db->loadAssocList();
				$barc=1; $gper=0; $ansper=0; $gperid = 0;
				//$cbg = "#FFFFFF";
				$expl='';
				foreach ($qopts as $opts) {
					if ($numr != 0) $per = $opts['COUNT(r.answer)']/$numr; else $per=1;
					$output .= '<tr><td valign="center" align="left" width="340">';
					if ($opts['opt_correct']) {
						if ($qdata->q_expl) $expl=$qdata->q_expl;
						else $expl.=$opts['opt_expl'];
					}
						
					if ($opts['opt_correct']) $output .= '<b>'.$opts['opt_text'].'</b>';
					else $output .= $opts['opt_text'];
					$output .= '</td>';
					$output .= '<td valign="center" width="340"><img src="media/com_continued/bar_'.$barc.'.jpg" height="15" width="'.($per*320).'" align="absmiddle"> ';
					$output .= '<b>'.$opts['COUNT(r.answer)'].'</b></td></tr>';
					$barc = $barc + 1;
					if ($barc==5) $barc=1;
					if ($gper < $per) {
						$gper = $per; $gperid = $opts['opt_id'];
					}
				}
				$output .= '</table></p>';
				break;
	
		}
		if ($expl) {
			$output .= '<div class="continued_mat_qexpl">'.$expl.'</div>';
		}
		return $output;
	}
	
}

?>
