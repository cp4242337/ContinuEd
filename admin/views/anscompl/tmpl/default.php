<?php defined('_JEXEC') or die('Restricted access');
$db =& JFactory::getDBO();
echo '<h2>'.$this->cinfo->cname.'</h2>';
if ($this->cinfo->course_haspre) {
	?>
<div id="editcell">
<h3>PreTest</h3>
<table class="adminlist">
	<thead>
		<tr>
			<th width="30"><?php echo JText::_( 'Part' ); ?></th>
			<th width="20"><?php echo JText::_( 'Q#' ); ?></th>
			<th><?php echo JText::_( 'Question' ); ?></th>
			<th width="80"><?php echo JText::_( 'Category' ); ?></th>
			<th width="50%"><?php echo JText::_( 'Answer' ); ?></th>

		</tr>
	</thead>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->preq ); $i < $n; $i++)
	{
		$row = &$this->preq[$i];

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="right"><?php echo $row->part; ?></td>
		<td align="right"><?php echo $row->ordering; ?></td>
		<td><?php echo $row->qtext; ?></td>
		<td><?php 				if ($row->qcat== 'eval') echo 'Evaulation';
		if ($row->qcat== 'assess') echo 'Assessment'; ?></td>
		<td><?php
		if ($row->qtype == 'multi' || $row->qtype == 'dropdown') {
			if ($row->qcat=='assess') {
				if ($row->correct) echo '<font color="green">'.$row->opttxt.'</font>';
				else echo '<font color="red">'.$row->opttxt.'</font>';
			}
			else echo $row->opttxt;
		}
		if ($row->qtype == 'textbox') { echo $row->answer; }
		if ($row->qtype == 'textar') { echo $row->answer; }
		if ($row->qtype == 'cbox') { if ($row->answer == 'on') echo 'Checked'; else echo 'Unchecked'; }
		if ($row->qtype == 'mcbox') {
			$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$row->qid.' ORDER BY ordering ASC';
			$db->setQuery( $query );
			$qopts = $db->loadAssocList();
			$answers = explode(' ',$row->answer);
			foreach ($qopts as $opts) {
				if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'<br />'; }
			}
		}
		if ($row->qtype == 'yesno') { echo $row->answer; }
		?></td>

	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->disporder+1;
	}
	?>
</table>
</div>
	<?php
}
if ($this->cinfo->haseval) {
	?>
<div id="editcell">
<h3>PostTest</h3>
<table class="adminlist">
	<thead>
		<tr>
			<th width="30"><?php echo JText::_( 'Part' ); ?></th>
			<th width="20"><?php echo JText::_( 'Q#' ); ?></th>
			<th><?php echo JText::_( 'Question' ); ?></th>
			<th width="80"><?php echo JText::_( 'Category' ); ?></th>
			<th width="50%"><?php echo JText::_( 'Answer' ); ?></th>

		</tr>
	</thead>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->postq ); $i < $n; $i++)
	{
		$row = &$this->postq[$i];

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="right"><?php echo $row->part; ?></td>
		<td align="right"><?php echo $row->ordering; ?></td>
		<td><?php echo $row->qtext; ?></td>
		<td><?php 				if ($row->qcat== 'eval') echo 'Evaulation';
		if ($row->qcat== 'assess') echo 'Assessment'; ?></td>
		<td><?php
		if ($row->qtype == 'multi') {
			if ($row->qcat=='assess') {
				if ($row->correct) echo '<font color="green">'.$row->opttxt.'</font>';
				else echo '<font color="red">'.$row->opttxt.'</font>';
			}
			else echo $row->opttxt;
		}
		if ($row->qtype == 'textbox') { echo $row->answer; }
		if ($row->qtype == 'textar') { echo $row->answer; }
		if ($row->qtype == 'cbox') { if ($row->answer == 'on') echo 'Checked'; else echo 'Unchecked'; }
		if ($row->qtype == 'mcbox') {
			$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$row->qid.' ORDER BY ordering ASC';
			$db->setQuery( $query );
			$qopts = $db->loadAssocList();
			$answers = explode(' ',$row->answer);
			foreach ($qopts as $opts) {
				if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'<br />'; }
			}
		}
		if ($row->qtype == 'yesno') { echo $row->answer; }
		?></td>

	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->disporder+1;
	}
	?>
</table>
</div>
	<?php
}
?>
