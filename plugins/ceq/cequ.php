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
		$token = JRequest::getVar('token');
		$cecfg = ContinuEdHelper::getConfig();
	
		//Get question
		$db  =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_id = '.$qid;
		$db->setQuery( $query );
		$qdata = $db->loadObject();
		$numans=0;
		$output='';
		$output .= '<script type="text/javascript">'."\n";
		$output .= 'jQuery(document).ready(function() {'."\n";
		$output .= '	jQuery.metadata.setType("attr", "validate");'."\n";
		$output .= '	jQuery("#qf_'.$qid.'").validate({'."\n";
		$output .= '		errorPlacement: function(error, element) {'."\n";
		$output .= '			error.appendTo("#q'.$qdata->q_id.'_msg");'."\n";
		$output .= '		},'."\n";
		$output .= '		submitHandler: function(form) {'."\n";
		$output .= '			jQuery.post( "'.JURI::base( true ).'/components/com_continued/interq.php", jQuery("#qf_'.$qid.'").serialize(),function( data ) {'."\n";
		$output .= '       			jQuery( "#qi_'.$qid.'" ).empty().append( data );'."\n";
		$output .= '   			});'."\n";
		$output .= '		}'."\n";
		$output .= '	});'."\n";
		$output .= '});'."\n";
		$output .= '</script>'."\n";
		$output .= '<div id="qi_'.$qid.'" class="continued_mat_q">';
		$output .= '<p><form name="qf_'.$qid.'" id="qf_'.$qid.'" action="'.JURI::base( true ).'/components/com_continued/interq.php" method="post">'."\n";
		$output .= '<input type="hidden" name="token" value="'.$token.'">';
		$output .= '<input type="hidden" name="course" value="'.$qdata->q_course.'">';
		$output .= '<input type="hidden" name="question" value="'.$qid.'">';
		$output .= '<b>'.$qdata->q_text.'</b><br>'."\n";
		$output .= '<div id="q'.$qdata->q_id.'_msg" class="ceq_error"></div>';
		switch ($qdata->q_type) {
			case 'multi':
				$query  = 'SELECT * FROM #__ce_questions_opts ';
				$query .= 'WHERE opt_question = '.$qid.' ORDER BY ordering ASC';
				$db->setQuery( $query );
				$qopts = $db->loadAssocList();
				$first = true;
				foreach ($qopts as $opts) {
					$numans++;
					$output .= ' <label><input type="radio" name="q'.$qid.'" value="'.$opts['opt_id'].'" id="q'.$qid.'"';
					if ($qdata->q_req && $first) {
						$output .= ' validate="{required:true, messages:{required:\'This Field is required\'}}"'; $first=false;
					}
					$output .= '> '.$opts['opt_text'].'</label><br>'."\n";
				}
				break;
		}
		$output .= '<input type="submit" id="qf'.$qid.'sub" name="qf'.$qid.'sub" value="Submit" class="cebutton">';
		if ($cecfg->INTER_REQMSG && $qdata->q_req) $output .=' <span style="color:#800000"><strong>'.$cecfg->INTER_REQMSG.'</strong></span>';
		$output .= '<br></form></p></div>'."\n";

		
		
	
		return $output;
	}
	
	protected function getAnswerData($qid ) {
	
		$token = JRequest::getVar('token');
		$cecfg = ContinuEdHelper::getConfig();
		$output='';
	
		//Get question
		$db  =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_id = '.$qid;
		$db->setQuery( $query );
		$qdata = $db->loadObject();
		$anscor=false;
		$output .=  '<div class="continued-ceq-question">';
		$output .=  '<div class="continued-ceq-question-text">'.$qdata->q_text.'</div>';
		switch ($qdata->q_type) {
		case 'multi':
			$qnum = 'SELECT count(question) FROM #__ce_evalans WHERE question = '.$qid.' GROUP BY question';
			$db->setQuery( $qnum );
			$qnums = $db->loadAssoc();
			$numr=$qnums['count(question)'];
			$query  = 'SELECT o.* FROM #__ce_questions_opts as o ';
			$query .= 'WHERE o.opt_question = '.$qid.' ORDER BY ordering ASC';
			$db->setQuery( $query );
			$qopts = $db->loadObjectList();
			$tph=0;
			foreach ($qopts as &$o) {
				$qa = 'SELECT count(*) FROM #__ce_evalans WHERE question = '.$qid.' && answer = '.$o->opt_id.' GROUP BY answer';
				$db->setQuery($qa);
				$o->anscount = $db->loadResult();
				if ($o->anscount == "") $o->anscount = 0;
				$tph = $tph + $o->prehits;
			}
			$barc=1; $gper=0; $ansper=0; $gperid = 0;
			foreach ($qopts as $opts) {
				if ($numr != 0) $per = ($opts->anscount+$opts->prehits)/($numr+$tph); else $per=1;
				if ($qans == $opts->id && $opts->correct) {
					$anscor=true;
				}
				$output .=  '<div class="continued-ceq-opt">';
				$output .=  '<div class="continued-ceq-opt-text">';
				if ($opts->opt_correct) $output .=  '<div class="continued-ceq-opt-correct"><b>'.$opts->opt_text.'</b></div>';
				else $output .=  $opts->opt_text;
				$output .=  '</div>';
				$output .=  '<div class="continued-ceq-opt-count">';
				$output .=  ($opts->anscount+$opts->prehits);
				$output .=  '</div>';
				$output .=  '<div class="continued-ceq-opt-bar-box"><div class="continued-ceq-opt-bar-bar" style="width:'.($per*100).'%"></div></div>';
				$output .=  '</div>';
				
				
				$barc = $barc + 1;
				if ($barc==5) $barc=1;
				if ($gper < $per) { $gper = $per; $gperid = $opts->id; }
				if ($qans==$opts->opt_id) {
					if ($qdata->q_expl) $expl=$qdata->q_expl;
					else $expl=$opts->opt_expl;
				}
			}
			
			break;
			
		}
		$output .=  '</div>';
		if ($expl) {
			$output .=  '<div class="continued-ceq-qexpl">'.$expl.'</div>';
		}
		$output .= '<div style="clear:both"></div>';
		return $output;
	}
	
}

?>
