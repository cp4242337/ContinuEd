<?php
// link http://127.0.0.1/mdev/index.php?option=com_continued&view=interq&course=1&question=3
defined('_JEXEC') or die('Restricted access');

$db  =& JFactory::getDBO();
$user = &JFactory::getUser();
$courseid = JRequest::getVar('course');
$qid  = JRequest::getVar('question');
$userid = $user->id;
$session =& JFactory::getSession();
$token = "interq";
global $cecfg;

//Get question
$query = 'SELECT * FROM #__ce_questions ';
$query .= 'WHERE q_id = '.$qid;
$db->setQuery( $query );
$qdata = $db->loadObject();
$numans=0;
$output='';


$aquery = 'SELECT * FROM #__ce_evalans WHERE ans_area = "inter" && userid="'.$user->id.'" ';
$aquery .= ' && question="'.$qid.'" ORDER BY anstime DESC';
$db->setQuery($aquery); 
$havans = $db->loadObject();

if (!$havans) {
	$output .= '<div id="qi_'.$qid.'" class="continued_mat_q">';
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
	$output .= '<p><form name="qf_'.$qid.'" id="qf_'.$qid.'" action="'.JURI::base( true ).'/components/com_continued/interq.php" method="post">'."\n";
	$output .= '<b>'.$qdata->q_text.'</b><br /><br />'."\n";
	$output .= '<div id="q'.$qdata->q_id.'_msg" class="error_msg"></div>';
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
	if ($user->id) {
		$output .= '<input type="submit" id="qf'.$qid.'sub" name="qf'.$qid.'sub" value="Submit" class="cebutton">';
	} else {
		$output .= '<br /><span style="color:#800000"><b>Please log in to answer</b></span>';
	}
	$output .= '<input type="hidden" name="token" value="'.$token.'">';
	$output .= '<input type="hidden" name="course" value="'.$qdata->q_course.'">';
	$output .= '<input type="hidden" name="question" value="'.$qid.'">';
	$output .= '<br></form></p></div>'."\n";
} else {
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
				if ($gper < $per) {
					$gper = $per; $gperid = $opts->id;
				}
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
}
echo $output;
?>
