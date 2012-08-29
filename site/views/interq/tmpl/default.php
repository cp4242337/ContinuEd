<?php
// link http://127.0.0.1/mdev/index.php?option=com_continued&view=interq&course=1&question=3
defined('_JEXEC') or die('Restricted access');

$db  =& JFactory::getDBO();
$user = &JFactory::getUser();
$courseid = JRequest::getVar('course');
$qid  = JRequest::getVar('question');
$userid = $user->id;
$session =& JFactory::getSession();
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

$output .= '<div id="qi_'.$qid.'" class="continued_mat_q">';
$output .= '<p><form name="qf_'.$qid.'">'."\n";
$output .= '<b>'.$qdata->q_text.'</b><br /><br />'."\n";
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
			if ($havans->answer==$opts['opt_id']) $output.=' checked="checked"';
			$output .= '> '.$opts['opt_text'].'</label><br>'."\n";
		}
		break;
	}
if ($user->id) {
	$output .= '<br /><div id="sb_'.$qdata->q_id.'"><a href="javascript:CEQ_'.$qid.'()" id="qf'.$qid.'sub" class="cebutton">';
	$output .= 'Submit</a></div>';
} else {
	$output .= '<br /><span style="color:#800000"><b>Please log in to answer</b></span>';
}
$output .= '<br></form></p></div>'."\n";
$output .= '<script type="text/javascript">'."\n";
$output .= 'function CEQ_'.$qid.'(){'."\n";
$output .= '	var ev = document.qf_'.$qid.';'."\n";
$output .= "	erMsg = '<span style=\"color:#800000\"><b>Answer is Required</b></span>';\n";
$output .= '	errs = false;';
$output .= '	if(isNCheckedR(ev.q'.$qid.', erMsg,'.$numans.',"q'.$qid.'"+"_msg")) { errs=true; }'."\n";
$output .= '	if (!errs) {'."\n";
$output .= '		document.getElementById("sb_'.$qid.'").innerHTML="Loading results";';
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
$output .= '		var queryString = "?" + "course='.$qdata->q_course.'&question='.$qid.'&q'.$qid.'=" + getCheckedValue(ev.q'.$qid.');'."\n";
$output .= '		ajaxRequest.open("GET", "components/com_continued/interq.php" + queryString, true);'."\n";
$output .= '		ajaxRequest.send(null);'."\n"; 
$output .= '	}'."\n";
$output .= '}'."\n";
$output .= '</script>'."\n";
echo $output;
?>
<script type="text/javascript">  
			function getCheckedValue(radioObj) {
				if(!radioObj)
					return "";
				var radioLength = radioObj.length;
				if(radioLength == undefined)
					if(radioObj.checked)
						return radioObj.value;
					else
						return "";
				for(var i = 0; i < radioLength; i++) {
					if(radioObj[i].checked) {
						return radioObj[i].value;
					}
				}
				return "";
			}
			
			function isNCheckedR(elem, helperMsg,cnt,msgl){
				var isit = false;
				for (var i=0; i<cnt; i++) {
					if(elem[i].checked){ isit = true; }
				}
				if (isit == false) {
					document.getElementById(msgl).innerHTML = helperMsg;
					elem[0].focus(); // set the focus to this input
					return true;
				}
				document.getElementById(msgl).innerHTML = '';
					return false;
			}
			function isDone() {
				var lyr = document.getElementById('cbError');
				var tbl = document.getElementById('agreet');
				if (numans>=numtohave) {
					lyr.style.display='none'; 
					tbl.style.border='none';
					return true;
				} else { 
					lyr.style.display='inline'; 
					tbl.style.border='thick #880000 solid';
					return false; 
				}
			}
			

 
		</script> 
