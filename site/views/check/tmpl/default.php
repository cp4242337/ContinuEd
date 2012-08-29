<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
$db =& JFactory::getDBO();
echo '<h2 class="componentheading">'.$this->cinfo->course_name.'</h2>';
$cpart = 0;
echo '<p>Please check your answers before proceeding. ';
if ($this->haseval) echo 'You may go back to a part by clicking the Change Your Answers button for each part.';
echo '</p>';

echo '<div align="center">';
if ($this->haspre) { foreach ($this->preqa as $preqa) {
	if ($preqa->q_part != $cpart) {
		if ($cpart != 0) echo "</table><p>&nbsp;</p>";
		echo '<table align="center" width="100%" class="zebra"><thead>';
		$cpart = $preqa->q_part;
		//echo '<tr><td><hr size="1" /></td></tr>';
		echo '<tr><th width="20">&nbsp;</th><th align="left"><b>Posttest Part '.$cpart;
		if ($preqa->part_name) echo ' - '.$preqa->part_name;
		echo '</b>';
		echo '</th></tr></thead>';
		echo '<tfoot><tr><td>&nbsp;</td><td>';
		if ($this->cinfo->course_changepre) {
			echo '<form method="post" action="" name="editp'.$cpart.'">';
			echo '<input type="hidden" name="token" value="'.$this->token.'">';
			echo '<input type="hidden" name="editpart" value="'.$cpart.'">';
			echo '<input type="hidden" name="editarea" value="pre">';
			echo '<input type="submit" name="edit" value="Change Your Answers" class="cebutton">';
			echo '</form>';
		}
		echo '</td></tr></tfoot>';
		echo '<tbody>';
		
	}
	echo '<tr><td>';
	if ($preqa->q_cat == 'assess') { if ($cecfg->EVAL_ASSD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_ASSI.'" alt="required">';}
	if ($preqa->q_req) { if ($cecfg->EVAL_REQD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_REQI.'" alt="required"> ';}
	echo '<b>'.$preqa->ordering.'.</b></td><td align="left" valign="top"><span class="continued-check-question">'.$preqa->q_text.'</span><br /><span class="continued-check-answer">';
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

	echo '</span></td></tr>';
}

if ($this->preqa) echo '</tbody></table><p>&nbsp;</p>';
}
$cpart=0;

if ($this->haseval) { foreach ($this->qanda as $qanda) {
	if ($qanda->q_part != $cpart) {
		if ($cpart != 0) echo "</table><p>&nbsp;</p>";
		echo '<table align="center" width="100%" class="zebra"><thead>';
		$cpart = $qanda->q_part;
		//echo '<tr><td><hr size="1" /></td></tr>';
		echo '<tr><th width="20">&nbsp;</th><th align="left"><b>Posttest Part '.$cpart;
		if ($qanda->part_name) echo ' - '.$qanda->part_name;
		echo '</b>';
		echo '</th></tr></thead>';
		echo '<tfoot><tr><td>&nbsp;</td><td>';
		echo '<form method="post" action="" name="editp'.$cpart.'">';
		echo '<input type="hidden" name="token" value="'.$this->token.'">';
		echo '<input type="hidden" name="editpart" value="'.$cpart.'">';
		echo '<input type="hidden" name="editarea" value="post">';
		echo '<input type="submit" name="edit" value="Change Your Answers" class="cebutton">';
		echo '</form>';
		echo '</td></tr></tfoot>';
		echo '<tbody>';
	}
	echo '<tr><td>';
	if ($qanda->q_cat == 'assess') { if ($cecfg->EVAL_ASSD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_ASSI.'" alt="required">';}
	if ($qanda->q_req) { if ($cecfg->EVAL_REQD) echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.$cecfg->EVAL_REQI.'" alt="required"> ';}
	echo '<b>'.$qanda->ordering.'.</b></td><td align="left" valign="top"><span class="continued-check-question">'.$qanda->q_text.'</span><br /><span class="continued-check-answer">';
	
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

	echo '</span></td></tr>';
}
if ($this->qanda) echo '</tbody></table><p>&nbsp;</p>';
}
?>


</div>
<?php if ($this->hasallreq) { ?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery.metadata.setType("attr", "validate");
		jQuery("#verify").validate({
			errorPlacement: function(error, element) {
		    	error.appendTo("#continued-check-verify-error");
		    },
		    highlight: function(element, errorClass, validClass) {
		    	jQuery("#continued-check-verify").addClass("continued-verify-errorstate");
		    },
		    unhighlight: function(element, errorClass, validClass){
		        	jQuery("#continued-check-verify").removeClass("continued-verify-errorstate");
		    }
			
	    });

	});


</script>

<form name="verify" id="verify" method="post" action="">
<div id="continued-check-verify">
	<div id="continued-check-verify-error"></div>	
	<div id="continued-check-verify-checkbox">
		<div style="width:5%;display:block;float:left;text-align:right;"><input name="compagree" id="compagree" value="true" type="checkbox" validate="{required:true, messages:{required:'You must agree to the following statement:'}}"></div>
		<div style="width:90%;display:block;float:left;"><label for="compagree"><?php echo $cecfg->EV_TEXT; ?></label></div>
		<div style="width:5%;display:block;float:left;"></div>
		<div style="clear:both"></div>
	</div>
	<div id="continued-check-verify-submit"><input name="certifme" id="certifme" value="Continue" type="submit" class="cebutton"></div>
</div>
</form>

<?php
} else {
	echo '<p align="center" style="color: rgb(136, 0, 0); font-size: 10pt; font-weight: bold;">You have not answered all the required evaulation questions, please use the return button below to return to evaulation. <u>Do not use</u> the back buton on your browser.</p>';
	echo '<div align="center"><form method="post" action="" name="editp1">';
	echo '<input type="hidden" name="token" value="'.$this->token.'">';
	echo '<input type="hidden" name="editpart" value="1">';
	echo '<input type="submit" name="edit" value="Return to Posttest" class="cebutton">';
	echo '</form></div>';
}
?>
</div>