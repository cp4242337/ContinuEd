<?php defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
<fieldset class="adminform"><legend><?php echo JText::_( 'Configuration' ); ?></legend>
<table class="admintable">

	<tr>
		<td class="key">Front Matter Agremment</td>
		<td><?php echo $this->cfg['FM_TEXT']; ?></td>
	</tr>
	<tr>
		<td class="key">Evalution Confirmation Agreement</td>
		<td><?php echo $this->cfg['EV_TEXT']; ?></td>
	</tr>
	<tr>
		<td class="key">% Needed to Pass</td>
		<td><?php echo $this->cfg['EVAL_PERCENT']; ?>%</td>
	</tr>
	<tr>
		<td class="key">Show Answer Explanation</td>
		<td><?php if ($this->cfg['EVAL_EXPL']) echo 'Yes'; else echo 'No'; ?></td>
	</tr>
	<tr>
		<td class="key">Indicate Required Questions</td>
		<td><?php if ($this->cfg['EVAL_REQD']) echo 'Yes'; else echo 'No'; ?></td>
	</tr>
	<tr>
		<td class="key">Indicate Assessent Questions</td>
		<td><?php if ($this->cfg['EVAL_ASSD']) echo 'Yes'; else echo 'No'; ?></td>
	</tr>
	<tr>
		<td class="key">Required Indicator Image</td>
		<td><?php echo $this->cfg['EVAL_REQI']; ?></td>
	</tr>
	<tr>
		<td class="key">Assessment Indicator Image</td>
		<td><?php echo $this->cfg['EVAL_ASSI']; ?></td>
	</tr>
	<tr>
		<td class="key">Show "Provided by" in catalog</td>
		<td><?php if ($this->cfg['CAT_PROV']) echo 'Yes'; else echo 'No'; ?></td>
	</tr>
	<tr>
		<td class="key">Allow guests to view catalog</td>
		<td><?php if ($this->cfg['CAT_GUEST']) echo 'Yes'; else echo 'No';; ?></td>
	</tr>
	<tr>
		<td class="key">No Degree Selected error message</td>
		<td><?php echo $this->cfg['NODEG_MSG']; ?></td>
	</tr>
	<tr>
		<td class="key">Show Previous Attempt Answers</td>
		<td><?php if ($this->cfg['EVAL_ANSRPT']) echo 'Yes'; else echo 'No'; ?></td>
	</tr>
	<tr>
		<td class="key">Records page Title</td>
		<td><?php echo $this->cfg['REC_TIT']; ?></td>
	</tr>

</table>
</fieldset>
Note: Previous answers can only be shown within same login session, text
for records page not shown above</div>
<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="1" /> <input type="hidden" name="CFG_ID"
	value="1" /> <input type="hidden" name="controller" value="cfge" /></form>
