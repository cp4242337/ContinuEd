<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
$db =& JFactory::getDBO();
echo '<h2 class="componentheading">'.$this->cinfo->course_name.'</h2>';
$cpart = 0;
echo '<p>Please check your answers before proceeding. ';
if ($this->haseval) echo 'You may go back to a part by clicking the Change button for each part.';
echo '</p>';

echo '<div align="center"><table align="center" width="90%"><tr><td align="left"><b>Question</b></td><td align="right"><b>Your Answer</b></td></tr>';
if ($this->haspre) { foreach ($this->preqa as $preqa) {
	if ($preqa->q_part != $cpart) {
		$cpart = $preqa->q_part;
		echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
		echo '<tr><td align="left"><b>Pretest Part '.$cpart;
		if ($preqa->part_name) echo ' - '.$preqa->part_name;
		echo '</b></td><td align="right">';
		if ($this->cinfo->course_changepre) {
			echo '<form method="post" action="" name="editp'.$cpart.'">';
			echo '<input type="hidden" name="token" value="'.$this->token.'">';
			echo '<input type="hidden" name="editpart" value="'.$cpart.'">';
			echo '<input type="hidden" name="editarea" value="pre">';
			echo '<input type="image" name="edit" value="Edit" src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_change.png">';
			echo '</form>';
		}
		echo '</td></tr>';
	}
	echo '<tr><td valign="top" align="left">';
	if ($preqa->q_cat == 'assess') { if ($cecfg->EVAL_ASSD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_ASSI.'" alt="required">';}
	if ($preqa->q_req) { if ($cecfg->EVAL_REQD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_REQI.'" alt="required"> ';}
	echo $preqa->ordering.'. '.$preqa->q_text;
	echo '</td><td align="right"><b>';
	if ($preqa->q_type == 'multi' || $preqa->q_type == 'dropdown') {
		echo $preqa->opt_text;
	}
	if ($preqa->q_type == 'textbox') echo $preqa->answer;
	if ($preqa->q_type == 'textar') echo $preqa->answer;
	if ($preqa->q_type == 'cbox') {
		if ($preqa->answer == 'on') echo 'Checked';
		else echo 'Unchecked';
	}
	if ($preqa->q_type == 'mcbox') {
		$query = 'SELECT * FROM #__ce_questions_opts WHERE opt_question = '.$preqa->ques_id.' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$answers = explode(' ',$preqa->answer);
		foreach ($qopts as $opts) {
			if (in_array($opts->opt_id,$answers)) { echo $opts->opt_text.'<br />'; }
		}
	}
	if ($preqa->q_type == 'yesno') echo $preqa->answer;

	echo '</b></td></tr>';
}}
$cpart=0;

if ($this->haseval && $this->haspre) echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
if ($this->haseval) { foreach ($this->qanda as $qanda) {
	if ($qanda->q_part != $cpart) {
		$cpart = $qanda->q_part;
		echo '<tr><td colspan="2"><hr size="1" /></td></tr>';
		echo '<tr><td align="left"><b>Posttest Part '.$cpart;
		if ($qanda->part_name) echo ' - '.$qanda->part_name;
		echo '</b></td><td align="right">';
		echo '<form method="post" action="" name="editp'.$cpart.'">';
		echo '<input type="hidden" name="token" value="'.$this->token.'">';
		echo '<input type="hidden" name="editpart" value="'.$cpart.'">';
		echo '<input type="hidden" name="editarea" value="post">';
		echo '<input type="image" name="edit" value="Edit" src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_change.png">';
		echo '</form></td></tr>';
	}
	echo '<tr><td valign="top" align="left">';
	if ($qanda->q_cat == 'assess') { if ($cecfg->EVAL_ASSD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_ASSI.'" alt="required">';}
	if ($qanda->q_req) { if ($cecfg->EVAL_REQD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_REQI.'" alt="required"> ';}
	echo $qanda->ordering.'. '.$qanda->q_text;
	echo '</td><td align="right"><b>';
	if ($qanda->q_type == 'multi' || $qanda->q_type == 'dropdown') {
		echo $qanda->opt_text;
	}
	if ($qanda->q_type == 'textbox') echo $qanda->answer;
	if ($qanda->q_type == 'textar') echo $qanda->answer;
	if ($qanda->q_type == 'cbox') {
		if ($qanda->answer == 'on') echo 'Checked';
		else echo 'Unchecked';
	}
	if ($qanda->q_type == 'mcbox') {
		$query = 'SELECT * FROM #__ce_questions_opts WHERE opt_question = '.$qanda->ques_id.' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadObjectList();
		$answers = explode(' ',$qanda->answer);
		foreach ($qopts as $opts) {
			if (in_array($opts->opt_id,$answers)) { echo $opts->opt_text.'<br />'; }
		}
	}
	if ($qanda->q_type == 'yesno') echo $qanda->answer;

	echo '</b></td></tr>';
}}
?>
<tr>
	<td colspan="2">
	<hr size="1" />
	</td>
</tr>
<tr>
	<td colspan="2"><?php if ($cecfg->EVAL_REQD) { ?><img
		src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_REQI; ?>"
		alt="required"> - Required <?php } if ($cecfg->EVAL_ASSD)  { ?><img
		src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_ASSI; ?>"
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
			<td valign="top" width="470" align="left"><span class="style2"><?php echo $cecfg->EV_TEXT; ?></span></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br>
			<input name="certifme" id="certifme" value="Continue" type="image"
				src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'; ?>btn_continue.png"></td>
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
	echo '<input type="image" name="edit" value="Edit" src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_return.png">';
	echo '</form></div>';
}
?>
</div>