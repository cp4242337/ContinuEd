<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
$db =& JFactory::getDBO();
echo '<div class="componentheading">'.$this->cinfo['cname'].'</div>';
$cpart = 0;
echo '<p>Please check your answers before proceeding. You may go back to a part by clicking the Change button for each part.</p>';
if ($this->cinfo['course_compcourse']) {
	echo '<p align="right"><a href="index.php?option=com_continued&view=answers&course='.$this->cinfo['course_compcourse'].'&Itemid='.JRequest::getVar('Itemid').'" target="_blank">';
	echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_compans.png" alt="Compare Answers" border="0"></a></p>';
}
echo '<div align="center"><table align="center" width="90%"><tr><td align="left"><b>Question</b></td><td align="right"><b>Your Answer</b></td></tr>';
if ($this->haspre) { foreach ($this->preqa as $preqa) {
	if ($preqa['qsec'] != $cpart) {
		$cpart = $preqa['qsec'];
		echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
		echo '<tr><td align="left"><b>Pretest Part '.$cpart;
		if ($preqa['part_name']) echo ' - '.$preqa['part_name'];
		echo '</b></td><td align="right">';
		if ($this->cinfo['course_changepre']) {
			echo '<form method="post" action="" name="editp'.$cpart.'">';
			echo '<input type="hidden" name="token" value="'.$this->token.'">';
			echo '<input type="hidden" name="editpart" value="'.$cpart.'">';
			echo '<input type="hidden" name="editarea" value="pre">';
			echo '<input type="image" name="edit" value="Edit" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_change.png">';
			echo '</form>';
		}
		echo '</td></tr>';
	}
	echo '<tr><td valign="top" align="left">';
	if ($preqa['qcat'] == 'assess') { if ($cecfg['EVAL_ASSD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_ASSI'].'" alt="required">';}
	if ($preqa['qreq']) { if ($cecfg['EVAL_REQD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_REQI'].'" alt="required"> ';}
	echo $preqa['ordering'].'. '.$preqa['qtext'];
	echo '</td><td align="right"><b>';
	if ($preqa['qtype'] == 'multi' || $preqa['qtype'] == 'dropdown') {
		echo $preqa['opttxt'];
	}
	if ($preqa['qtype'] == 'textbox') echo $preqa['answer'];
	if ($preqa['qtype'] == 'textar') echo $preqa['answer'];
	if ($preqa['qtype'] == 'cbox') {
		if ($preqa['answer'] == 'on') echo 'Checked';
		else echo 'Unchecked';
	}
	if ($preqa['qtype'] == 'mcbox') {
		$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$preqa['ques_id'].' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$answers = explode(' ',$preqa['answer']);
		foreach ($qopts as $opts) {
			if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'<br />'; }
		}
	}
	if ($preqa['qtype'] == 'yesno') echo $preqa['answer'];

	echo '</b></td></tr>';
}}
$cpart=0;
/*if ($this->hasinter && $this->haspre) echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
if ($this->hasinter) { foreach ($this->intera as $intera) {
	if ($intera['qsec'] != $cpart) {
		$cpart = $intera['qsec'];
		echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
		echo '<tr><td align="left"><b>';
		echo '</b></td><td align="right">';
		echo '&nbsp;</td></tr>';
	}
	echo '<tr><td valign="top" align="left">';
	if ($intera['qcat'] == 'assess') { if ($cecfg['EVAL_ASSD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_ASSI'].'" alt="required">';}
	if ($intera['qreq']) { if ($cecfg['EVAL_REQD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_REQI'].'" alt="required"> ';}
	echo $intera['ordering'].'. '.$intera['qtext'];
	echo '</td><td align="right"><b>';
	if ($intera['qtype'] == 'multi' || $intera['qtype'] == 'dropdown') {
		echo $intera['opttxt'];
	}
	if ($intera['qtype'] == 'textbox') echo $intera['answer'];
	if ($intera['qtype'] == 'textar') echo $intera['answer'];
	if ($intera['qtype'] == 'cbox') {
		if ($intera['answer'] == 'on') echo 'Checked';
		else echo 'Unchecked';
	}
	if ($intera['qtype'] == 'mcbox') {
		$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$intera['ques_id'].' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$answers = explode(' ',$intera['answer']);
		foreach ($qopts as $opts) {
			if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'<br />'; }
		}
	}
	if ($intera['qtype'] == 'yesno') echo $intera['answer'];

	echo '</b></td></tr>';
}}
$cpart=0;*/
if ($this->haseval && $this->haspre) echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
if ($this->haseval) { foreach ($this->qanda as $qanda) {
	if ($qanda['qsec'] != $cpart) {
		$cpart = $qanda['qsec'];
		echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
		echo '<tr><td align="left"><b>Posttest Part '.$cpart;
		if ($qanda['part_name']) echo ' - '.$qanda['part_name'];
		echo '</b></td><td align="right">';
		echo '<form method="post" action="" name="editp'.$cpart.'">';
		echo '<input type="hidden" name="token" value="'.$this->token.'">';
		echo '<input type="hidden" name="editpart" value="'.$cpart.'">';
		echo '<input type="hidden" name="editarea" value="post">';
		echo '<input type="image" name="edit" value="Edit" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_change.png">';
		echo '</form></td></tr>';
	}
	echo '<tr><td valign="top" align="left">';
	if ($qanda['qcat'] == 'assess') { if ($cecfg['EVAL_ASSD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_ASSI'].'" alt="required">';}
	if ($qanda['qreq']) { if ($cecfg['EVAL_REQD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_REQI'].'" alt="required"> ';}
	echo $qanda['ordering'].'. '.$qanda['qtext'];
	echo '</td><td align="right"><b>';
	if ($qanda['qtype'] == 'multi' || $qanda['qtype'] == 'dropdown') {
		//if ($qanda['qcat'] == 'assess' && $qanda['correct'] == 1) echo '<span style="color:#008000">';
		//if ($qanda['qcat'] == 'assess' && $qanda['correct'] == 0) echo '<span style="color:#800000">';
		//if ($qanda['qcat'] == 'eval') echo '<span>';
		echo $qanda['opttxt'];
		//echo '</span>';
	}
	if ($qanda['qtype'] == 'textbox') echo $qanda['answer'];
	if ($qanda['qtype'] == 'textar') echo $qanda['answer'];
	if ($qanda['qtype'] == 'cbox') {
		if ($qanda['answer'] == 'on') echo 'Checked';
		else echo 'Unchecked';
	}
	if ($qanda['qtype'] == 'mcbox') {
		$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$qanda['ques_id'].' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$answers = explode(' ',$qanda['answer']);
		foreach ($qopts as $opts) {
			if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'<br />'; }
		}
	}
	if ($qanda['qtype'] == 'yesno') echo $qanda['answer'];

	echo '</b></td></tr>';
}}
?>
<tr>
	<td colspan="2">
	<hr size="1" />
	</td>
</tr>
<tr>
	<td colspan="2"><?php if ($cecfg['EVAL_REQD']) { ?><img
		src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_REQI']; ?>"
		alt="required"> - Required <?php } if ($cecfg['EVAL_ASSD'])  { ?><img
		src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_ASSI']; ?>"
		alt="Assess"> - Assessment Question<?php } ?></td>
</tr>
</table>
</div>
<?php if ($this->hasallreq) { ?>
<form name="form1" method="post" action=""
	onsubmit="return isChecked(this.compagree);">
<div align="center">
<table id="agreet" style="border: medium none; padding: 5px;" border="0"
	cellpadding="0" cellspacing="0" width="500" align="center">
	<tbody>
		<tr>
			<td colspan="2" align="center">
			<div id="cbError"
				style="color: rgb(136, 0, 0); font-size: 10pt; font-weight: bold; display: none; position: relative;">You
			must agree to the following statement:<br>
			<br>
			</div>
			</td>

		</tr>
		<tr>
			<td align="left" valign="top" width="30"><input name="compagree"
				id="compagree" value="true" type="checkbox"></td>
			<td valign="top" width="470" align="left"><span class="style2"><?php echo $cecfg['EV_TEXT']; ?></span></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br>
			<input name="certifme" id="certifme" value="Continue" type="image"
				src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'; ?>btn_continue.png"></td>
		</tr>

	</tbody>
</table>
<br>
</div>
</form>
<script type="text/javascript">
<!--
function isChecked(elem) {
	var lyr = document.getElementById('cbError');
	var tbl = document.getElementById('agreet');
	if (elem.checked) {
		lyr.style.display='none'; 
		tbl.style.border='none';
		return true;
	} else { 
		lyr.style.display='inline'; 
		tbl.style.border='thick #880000 solid';
		elem.focus();
		return false; 
	}
}

//-->
</script>
<?php
} else {
	echo '<p align="center" style="color: rgb(136, 0, 0); font-size: 10pt; font-weight: bold;">You have not answered all the required evaulation questions, please use the return button below to return to evaulation. <u>Do not use</u> the back buton on your browser.</p>';
	echo '<div align="center"><form method="post" action="" name="editp1">';
	echo '<input type="hidden" name="token" value="'.$this->token.'">';
	echo '<input type="hidden" name="editpart" value="1">';
	echo '<input type="image" name="edit" value="Edit" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_return.png">';
	echo '</form></div>';
}
?>
