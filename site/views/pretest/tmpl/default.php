<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
echo '<div class="componentheading">'.$this->mtext->course_name.'</div>';
echo '<h3>Pretest Part '.$this->part.' of '.$this->mtext->course_preparts;
if ($this->parti) echo ' - '.$this->parti->part_name;
echo '</h3>';
if ($this->parti) echo '<p>'.$this->parti->part_desc.'</p>';
echo '<form name="evalf" method="post" action="" onSubmit="return checkRq();"><input type="hidden" name="stepnext" value="">';
//echo $this->mtext->material;
$assess=0;
if ($this->adata) echo '<input type="hidden" name="hasans" value="1">';
else echo '<input type="hidden" name="hasans" value="0">';
foreach ($this->qdata as $qdata) {
	if ($qdata->q_cat == 'assess') {
		if ($cecfg->EVAL_ASSD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_ASSI.'" alt="required">';
		$assess=1;
	}
	if ($qdata->q_req) {
		if ($cecfg->EVAL_REQD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_REQI.'" alt="required"> ';
		$req_q[] = 'p'.$this->part.'q'.$qdata->q_id;
		$req_t[] = $qdata->q_type;
	}

	//get prev answer
	$curans = null;
	if ($this->adata) {	foreach ($this->adata as $adata) { if ($adata->question== $qdata->q_id) { $curans = $adata->answer;} } }

	//Question #
	if ($qdata->q_type != 'message') echo $qdata->ordering.'. ';

	//Question text if not a single checkbox
	if ($qdata->q_type != 'cbox') {
		echo '<strong>';
		if ($qdata->q_type != 'message') echo $qdata->q_text;
		else echo $qdata->q_expl;
		echo '</strong>';
	}

	//output checkbox
	if ($qdata->q_type == 'cbox') {
		echo '<label><input type="checkbox" size="40" name="p'.$this->part.'q'.$qdata->q_id.'"';
		if ($curans == 'on') echo ' checked="checked"';
		echo '> '.$qdata->q_text.'</label><br>';
	}

	//verification msg area
	echo '<div id="'.'p'.$this->part.'q'.$qdata->q_id.'_msg" class="error_msg"></div>';

	//output radio select
	if ($qdata->q_type == 'multi') {
		$numopts=0;
		foreach ($qdata->options as $opts) {
			echo '<label><input type="radio" name="p'.$this->part.'q'.$qdata->q_id.'" value="'.$opts->opt_id.'"';
			if ($curans == $opts->opt_id) { echo ' checked="checked"'; }
			echo '> '.$opts->opt_text.'</label><br>';
			$numopts++;
		}
	}

	//output dropdown select
	if ($qdata->q_type == 'dropdown') {
		$numopts=0;
		echo '<select name="p'.$this->part.'q'.$qdata->q_id.'">';
		foreach ($qdata->options as $opts) {
			echo '<option value="'.$opts->opt_id.'"';
			if ($curans == $opts->opt_id) { echo ' SELECTED'; }
			echo '> '.$opts->opt_text.'</option>';
			$numopts++;
		}
		echo '</select>';
	}

	//output multi checkbox
	if ($qdata->q_type == 'mcbox') {
		echo '<em>(check all that apply)</em><br />';
		$selected = explode(' ',$curans);
		foreach ($qdata->options as $opts) {
			echo '<label><input type="checkbox" name="p'.$this->part.'q'.$qdata->q_id.'[]" value="'.$opts->opt_id.'"';
			if (in_array($opts->opt_id,$selected)) { echo ' CHECKED'; }
			echo '> '.$opts->opt_text.'</label><br>';
			$numopts++;
		}
	}

	//output text field
	if ($qdata->q_type == 'textbox') { echo '<input type="text" size="40" name="p'.$this->part.'q'.$qdata->q_id.'" value="'.$curans.'"><br>'; }

	//output text box
	if ($qdata->q_type == 'textar') { echo '<textarea cols="60" rows="3" name="p'.$this->part.'q'.$qdata->q_id.'">'.$curans.'</textarea><br>'; }

	//output radio select
	if ($qdata->q_type == 'yesno') {
		$numopts=2;
		echo '<label><input type="radio" name="p'.$this->part.'q'.$qdata->q_id.'" value="Yes"';
		if ($curans == 'Yes') { echo ' checked="checked"'; }
		echo '>Yes</label> ';
		echo '<label><input type="radio" name="p'.$this->part.'q'.$qdata->q_id.'" value="No"';
		if ($curans == 'No') { echo ' checked="checked"'; }
		echo '>No</label><br />';
	}

	//add in verification if nedded
	if ($qdata->q_req) { $req_o[] = $numopts;}
	if ($qdata->q_depq != 0 && ($qdata->q_type == 'textar' || $qdata->q_type == 'textbox')) {
		$reqd_q[] = 'p'.$this->part.'q'.$qdata->q_id;
		$reqd_d[] = 'p'.$this->part.'q'.$qdata->q_depq;
	}
	echo '<br>';
}

if (count($req_q) != 0) if ($cecfg->EVAL_REQD) echo '<br><img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_REQI.'" alt="required"> - Required';
if ($assess == 1) if ($cecfg->EVAL_ASSD) echo '<br><img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_ASSI.'" alt="Assess"> - Assessment Question';
echo '<p align="center">';
if ($this->part != $this->mtext->course_preparts) {
	?>
<input
	type="hidden" name="part" value="<?php echo $this->part; ?>">
<input type="hidden" name="addans"
	value="true">
	<?php if ($this->part !=1) { ?>
<input name="evalstep"
	id="evalstep" value="prev" type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'; ?>btn_prev.png"
	onclick="document.evalf.stepnext.value='prev'">
	<?php } ?>
<input name="evalstep"
	id="evalstep" value="next" type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'; ?>btn_next.png"
	onclick="document.evalf.stepnext.value='next'">
	<?php } else { ?>
<input
	type="hidden" name="part" value="<?php echo $this->part; ?>">
<input type="hidden" name="addans"
	value="true">
	<?php if ($this->part !=1) { ?>
<input name="evalstep"
	id="evalstep" value="prev" type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'; ?>btn_prev.png"
	onclick="document.evalf.stepnext.value='prev'">
	<?php } ?>
<input name="evalstep"
	id="evalstep" value="eval" type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'; ?>btn_eval.png"
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
</div>
