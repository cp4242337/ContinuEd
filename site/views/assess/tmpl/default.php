<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
echo '<h2 class="componentheading">'.$this->cinfo->course_name.'</h2>';
$assess = '<p>Assessment Question Score</p>';
$assess .= '<div align="center"><table align="center" width="90%"><tr><td><b>Question & Explanation</b></td><td align="right"><b>Your Answer</b></td><td align="right"><b>Assessment</b></td></tr>';
$assess .= '<tr><td colspan="3"><hr size="1" /></td></tr>';
$numcorrect = 0;
$totq = 0;
if ($this->cinfo->course_evaltype == 'assess' && $this->cinfo->course_haseval) {
	foreach ($this->qanda as $qanda) {
		$assess .= '<tr><td align="left">';
		$assess .= $qanda->ordering.'. <b>'.$qanda->q_text;
		$assess .= '</b></td><td align="right"><b>';
		if ($qanda->q_type == 'multi') {
			if ($qanda->q_cat == 'assess' && $qanda->opt_correct == 1) {
				$assess .='<span style="color:#008000">'.$qanda->opt_text.'</span></b></td><td align="right"><b><span style="color:#008000">Correct'; $numcorrect++;
			}
			if ($qanda->q_cat == 'assess' && $qanda->opt_correct == 0) {
				$assess .= '<span style="color:#800000">'.$qanda->opt_text.'</span></b></td><td align="right"><b><span style="color:#800000">Incorrect';
			}
			if ($qanda->q_cat == 'eval') $assess .= $qanda->opt_text.'</b></td><td><b><span>';
			$totq++;
			$assess .= '</span>';
		}
		else $assess .= $qanda->answer;
		$assess .= '</b></td></tr>';
		if ($cecfg->EVAL_EXPL) {
			if ($qanda->opt_expl) $assess .= '<tr><td colspan="3">'.$qanda->opt_expl.'</td></tr>';
			if ($qanda->q_expl) $assess .= '<tr><td colspan="3">'.$qanda->q_expl.'</td></tr>';
		}
	}
}
$assess .= '<tr><td colspan="3"><hr size="1" /></td></tr>';
if ($totq != 0) $score = ($numcorrect / $totq) * 100;
else $score = 101;

if ($score >= $cecfg->EVAL_PERCENT) $pass = 1; else $pass = 0;
$assess .= '<tr><td colspan="3" align="center">Score: '.(int)$score.'% You Have: ';
if ($pass) $assess .= '<span style="color:#008000">Passed</span>';
else  $assess .= '<span style="color:#800000">Failed</span>';
$assess .= '</td></tr></table></div>';
if ($score != 101) {
	echo  $assess;
} else {
	$pass=1;

}
if ($pass) {
	if ($this->canrate) {
		echo '<form name="courserate" method="post" action="">';
		echo '<p align="center">Give this Program a Rating: '.JHTML::_('select.integerlist',1,5,1,'addrating','',5);
		echo ' <input type="submit" name="ratecourse" value="Submit Rating" class="cebutton">></p>';
		echo '</form>';
	}
	if ($this->cinfo->course_passmsg) echo '<p>'.$this->cinfo->course_passmsg.'</p><p align="center">';
	else echo '<p align="center">Thank you for completing: '.$this->cinfo->course_name.'.<br /><br/>';
	if ($this->cinfo->course_hascertif) echo 'Select the Get Certificate button to receive your '.$this->usercertif.'.<br />';
	echo 'Select the return button below to return to the list.</p>';
	echo '<p align="center">';
	if (!$this->cando && $this->cinfo->course_hascertif) {
		if ($this->usercertif == '') echo 'Please complete your <a href="index.php?option=com_comprofiler&task=userProfile">profile</a> to get the proper certificate<br />';
		else echo 'This program is ineligible for a: <b><em>'.$this->usercertif.'</em></b><br>however you may still receive a: <b><em>'.$this->defaultcertif.'</em></b><br>';
	}
	if ($this->cinfo->course_hascertif) {
		echo '<a href="'.JURI::base( true ).'/components/com_continued/gencert.php?course='.$this->cinfo->course_id.'" target="_blank" class="cebutton">';
		echo 'Get Certificate</a>';
	}
	echo '<a href="'.$this->redirurl.'" class="cebutton">Return</a></p>';
}
else
{
	if ($this->cinfo->course_failmsg) echo '<p>'.$this->cinfo->course_failmsg.'</p>';
	echo '<p align="center"><a href="index.php?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->courseid.'" class="cebutton_red">';
	echo 'Take Again</a>';
	echo '<a href="'.$this->redirurl.'" class="cebutton">Return</a>';
}
?>
</div>
