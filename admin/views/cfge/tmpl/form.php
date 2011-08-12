<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Configuration' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Front Matter Agreement' ); ?></td>
		<td><textarea class="text_area" name="FM_TEXT" cols="60" rows="2"><?php echo $this->answer->FM_TEXT;?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Eval Confirmation Agreement' ); ?></td>
		<td><textarea class="text_area" name="EV_TEXT" cols="60" rows="2"><?php echo $this->answer->EV_TEXT;?></textarea>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( '% Needed to Pass' ); ?></td>
		<td><input type="text" class="text_area" name="EVAL_PERCENT"
			id="EVAL_PERCENT" width="40" maxlen="50"
			value="<?php echo $this->answer->EVAL_PERCENT;?>"></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Show Answer Explanation' ); ?></td>
		<td><?php echo JHTML::_( 'select.booleanlist', 'EVAL_EXPL', '', $this->answer->EVAL_EXPL, 'Yes', 'No' ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Indicate Required Questions' ); ?></td>
		<td><?php echo JHTML::_( 'select.booleanlist', 'EVAL_REQD', '', $this->answer->EVAL_REQD, 'Yes', 'No' ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Indicate Assessment Questions' ); ?></td>
		<td><?php echo JHTML::_( 'select.booleanlist', 'EVAL_ASSD', '', $this->answer->EVAL_ASSD, 'Yes', 'No' ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Required Indicator Image' ); ?></td>
		<td><input type="text" class="text_area" name="EVAL_REQI" width="40"
			maxlen="50" value="<?php echo $this->answer->EVAL_REQI;?>"></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Assessment Indicator Image' ); ?></td>
		<td><input type="text" class="text_area" name="EVAL_ASSI" width="40"
			maxlen="50" value="<?php echo $this->answer->EVAL_ASSI;?>"></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Show "Provided by: " in catalog' ); ?></td>
		<td><?php echo JHTML::_( 'select.booleanlist', 'CAT_PROV', '', $this->answer->CAT_PROV, 'Yes', 'No' ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Allow guests to view catalog' ); ?></td>
		<td><?php echo JHTML::_( 'select.booleanlist', 'CAT_GUEST', '', $this->answer->CAT_GUEST, 'Yes', 'No' ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'No degree selected error message' ); ?></td>
		<td><textarea class="text_area" name="NODEG_MSG" cols="60" rows="2"><?php echo $this->answer->NODEG_MSG;?></textarea>(NO
		HTML)</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Show previous attempt answers' ); ?></td>
		<td><?php echo JHTML::_( 'select.booleanlist', 'EVAL_ANSRPT', '', $this->answer->EVAL_ANSRPT, 'Yes', 'No' ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Records Page Title' ); ?></td>
		<td><input type="text" class="text_area" name="REC_TIT" id="REC_TIT"
			size="40" maxlen="255" value="<?php echo $this->answer->REC_TIT;?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Records Page Header Text' ); ?></td>
		<td><?php echo $this->editor->display('REC_PRETXT',$this->answer->REC_PRETXT,'100%','300','30','30',false); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Records Page Footer Text' ); ?></td>
		<td><?php echo $this->editor->display('REC_POSTTXT',$this->answer->REC_POSTTXT,'100%','300','30','30',false); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Show Faculty' ); ?></td>
		<td><?php echo JHTML::_( 'select.booleanlist', 'SHOW_FAC', '', $this->answer->SHOW_FAC, 'Yes', 'No' ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Login Message' ); ?></td>
		<td><textarea class="text_area" name="LOGIN_MSG" cols="60" rows="2"><?php echo $this->answer->LOGIN_MSG;?></textarea>(&lt;br&gt;
		Only)</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Needs Degree Msg' ); ?></td>
		<td><?php echo JHTML::_( 'select.booleanlist', 'NEEDS_DEGREE', '', $this->answer->NEEDS_DEGREE, 'Yes', 'No' ); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><?php echo JText::_( 'Template Name' ); ?></td>
		<td><input type="text" class="text_area" name="TEMPLATE" id="TEMPLATE"
			width="40" maxlen="50" value="<?php echo $this->answer->TEMPLATE;?>">
		</td>
	</tr>


</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="CFG_ID"
	value="<?php echo $this->answer->CFG_ID; ?>" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="controller"
	value="cfge" /></form>
