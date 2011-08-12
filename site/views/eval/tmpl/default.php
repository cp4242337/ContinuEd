<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
$db =& JFactory::getDBO();
echo '<div class="componentheading">'.$this->mtext['cname'].'</div>';
echo '<h3>Evaluation Part '.$this->part.' of '.$this->mtext['evalparts'];
if ($this->parti) echo ' - '.$this->parti['part_name'];
echo '</h3>';
if ($this->parti) echo '<p>'.$this->parti['part_desc'].'</p>';
echo '<form name="evalf" method="post" action="" onSubmit="return checkRq();"><input type="hidden" name="stepnext" value="">';
//echo $this->mtext['material'];
$assess=0;
if ($this->adata) echo '<input type="hidden" name="hasans" value="1">';
else echo '<input type="hidden" name="hasans" value="0">';
foreach ($this->qdata as $qdata) {
	if ($qdata['qcat'] == 'assess') {
		if ($cecfg['EVAL_ASSD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_ASSI'].'" alt="required">';
		$assess=1;
	}
	if ($qdata['qreq']) {
		if ($cecfg['EVAL_REQD']) echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_REQI'].'" alt="required"> ';
		$req_q[] = 'p'.$this->part.'q'.$qdata['id'];
		$req_t[] = $qdata['qtype'];
	}

	//get prev answer
	$curans = null;
	if ($this->adata) {	foreach ($this->adata as $adata) { if ($adata['question'] == $qdata['id']) { $curans = $adata['answer'];} } }

	//Question #
	echo $qdata['ordering'].'. ';

	//Question text if not a single checkbox
	if ($qdata['qtype'] != 'cbox') {
		echo '<strong>';
		echo $qdata['qtext'];
		echo '</strong>';
	}

	//output checkbox
	if ($qdata['qtype'] == 'cbox') {
		echo '<label><input type="checkbox" size="40" name="p'.$this->part.'q'.$qdata['id'].'"';
		if ($curans == 'on') echo ' checked="checked"';
		echo '>'.$qdata['qtext'].'</label><br>';
	}

	//verification msg area
	echo '<div id="'.'p'.$this->part.'q'.$qdata['id'].'_msg" class="error_msg"></div>';

	//output radio select
	if ($qdata['qtype'] == 'multi') {
		$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$qdata['id'].' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$numopts=0;
		foreach ($qopts as $opts) {
			echo '<label><input type="radio" name="p'.$this->part.'q'.$qdata['id'].'" value="'.$opts['id'].'"';
			if ($curans == $opts['id']) { echo ' checked="checked"'; }
			echo '>'.$opts['opttxt'].'</label><br>';
			$numopts++;
		}
	}

	//output dropdown select
	if ($qdata['qtype'] == 'dropdown') {
		$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$qdata['id'].' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$numopts=0;
		echo '<select name="p'.$this->part.'q'.$qdata['id'].'">';
		foreach ($qopts as $opts) {
			echo '<option value="'.$opts['id'].'"';
			if ($curans == $opts['id']) { echo ' SELECTED'; }
			echo '>'.$opts['opttxt'].'</option>';
			$numopts++;
		}
		echo '</select>';
	}

	//output multi checkbox
	if ($qdata['qtype'] == 'mcbox') {
		echo '<em>(check all that apply)</em><br />';
		$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$qdata['id'].' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qopts = $db->loadAssocList();
		$selected = explode(' ',$curans);
		foreach ($qopts as $opts) {
			echo '<label><input type="checkbox" name="p'.$this->part.'q'.$qdata['id'].'[]" value="'.$opts['id'].'"';
			if (in_array($opts['id'],$selected)) { echo ' CHECKED'; }
			echo '>'.$opts['opttxt'].'</label><br>';
			$numopts++;
		}
	}

	//output text field
	if ($qdata['qtype'] == 'textbox') { echo '<input type="text" size="40" name="p'.$this->part.'q'.$qdata['id'].'" value="'.$curans.'"><br>'; }

	//output text box
	if ($qdata['qtype'] == 'textar') { echo '<textarea cols="60" rows="3" name="p'.$this->part.'q'.$qdata['id'].'">'.$curans.'</textarea><br>'; }

	//output radio select
	if ($qdata['qtype'] == 'yesno') {
		$numopts=2;
		echo '<label><input type="radio" name="p'.$this->part.'q'.$qdata['id'].'" value="Yes"';
		if ($curans == 'Yes') { echo ' checked="checked"'; }
		echo '>Yes</label> ';
		echo '<label><input type="radio" name="p'.$this->part.'q'.$qdata['id'].'" value="No"';
		if ($curans == 'No') { echo ' checked="checked"'; }
		echo '>No</label><br />';
	}

	//add in verification if nedded
	if ($qdata['qreq']) { $req_o[] = $numopts;}
	if ($qdata['q_depq'] != 0 && ($qdata['qtype'] == 'textar' || $qdata['qtype'] == 'textbox')) {
		$reqd_q[] = 'p'.$this->part.'q'.$qdata['id'];
		$reqd_d[] = 'p'.$this->part.'q'.$qdata['q_depq'];
	}
	echo '<br>';
}

if (count($req_q) != 0) if ($cecfg['EVAL_REQD']) echo '<br><img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_REQI'].'" alt="required"> - Required';
if ($assess == 1) if ($cecfg['EVAL_ASSD']) echo '<br><img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/'.$cecfg['EVAL_ASSI'].'" alt="Assess"> - Assessment Question';
echo '<p align="center">';
if ($this->part != $this->mtext['evalparts']) {
	?>
<input
	type="hidden" name="part" value="<?php echo $this->part; ?>">
<input type="hidden" name="addans"
	value="true">
	<?php if ($this->part !=1) { ?>
<input name="evalstep"
	id="evalstep" value="prev" type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'; ?>btn_prev.png"
	onclick="document.evalf.stepnext.value='prev'">
	<?php } ?>
<input name="evalstep"
	id="evalstep" value="next" type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'; ?>btn_next.png"
	onclick="document.evalf.stepnext.value='next'">
	<?php } else { ?>
<input
	type="hidden" name="part" value="<?php echo $this->part; ?>">
<input type="hidden" name="addans"
	value="true">
	<?php if ($this->part !=1) { ?>
<input name="evalstep"
	id="evalstep" value="prev" type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'; ?>btn_prev.png"
	onclick="document.evalf.stepnext.value='prev'">
	<?php } ?>
<input name="evalstep"
	id="evalstep" value="eval" type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'; ?>btn_eval.png"
	onclick="document.evalf.stepnext.value='eval'">

	<?php } ?>

</form>
</p>
	<?php
	$cnt = count($req_q);
	$cntd = count($reqd_q);
	?>
<script type='text/javascript'>
function checkRq() {
	ev = document.evalf;
	erMsg = '<span style="color:#800000"><b>Answer is Required</b></span>';
	cks = false; errs = false;
<?
for ($i=0; $i<$cnt; $i++) {
	if ($req_t[$i] == 'textbox') { echo "	if(isEmpty(ev.".$req_q[$i].", erMsg,'".$req_q[$i]."'+'_msg')) { errs=true; }\n"; }
	if ($req_t[$i] == 'multi' || $req_t[$i] == 'yesno') { echo "	if(isNCheckedR(ev.".$req_q[$i].", erMsg,".$req_o[$i].",'".$req_q[$i]."'+'_msg')) { errs=true; }\n"; }
	if ($req_t[$i] == 'cbox') { echo "	if(isChecked(ev.".$req_q[$i].", erMsg,'".$req_q[$i]."'+'_msg')) { errs=true; }\n"; }
	
} 
for ($i=0; $i<$cntd; $i++) {
	echo "	if(isEmptyD(ev.".$reqd_q[$i].", erMsg,'".$reqd_q[$i]."'+'_msg',ev.".$reqd_d[$i].")) { errs=true; }\n"; 
	
} 
	echo "if (!errs) return true;\n";
	echo 'return false; }';
?>

function isEmpty(elem, helperMsg,msgl){
	if(elem.value.length == 0){
		document.getElementById(msgl).innerHTML = helperMsg;
		elem.focus(); // set the focus to this input
		return true;
	}
	document.getElementById(msgl).innerHTML ='';
		return false;
}
function isEmptyD(elem, helperMsg,msgl,depq){
	if(elem.value.length == 0 && depq[0].checked){
		document.getElementById(msgl).innerHTML = helperMsg;
		elem.focus(); // set the focus to this input
		return true;
	}
	document.getElementById(msgl).innerHTML ='';
		return false;
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
function isChecked(elem, helperMsg,msgl) {
	if (elem.checked) {
		document.getElementById(msgl).innerHTML = '';
		return false;
	} else { 
		document.getElementById(msgl).innerHTML = helperMsg;
		elem.focus(); // set the focus to this input
		return true; 
	}
}


</script>

