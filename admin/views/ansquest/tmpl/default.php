<?php defined('_JEXEC') or die('Restricted access');
$db =& JFactory::getDBO();
?>

<div id="editcell"><?php echo '<b>'.JText::_('Question #'.$this->data->ordering.': ').'</b>'.$this->data->qtext; ?>
<table class="adminlist">
	<thead>
		<tr>
			<th width="150"><?php echo JText::_( 'Name' ); ?></th>
			<th width="150"><?php echo JText::_( 'Answered On' ); ?></th>
			<th><?php echo JText::_( 'Answer' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->firstname.' '.$row->lastname; ?></td>
		<td><?php echo $row->anstime; ?></td>
		<td><?php
		if ($this->data->qtype == 'multi') {
			if ($this->data->qcat=='assess') {
				if ($row->correct) echo '<font color="green">'.$row->opttxt.'</font>';
				else echo '<font color="red">'.$row->opttxt.'</font>';
			}
			else echo $row->opttxt;
		}
		if ($this->data->qtype == 'textbox') { echo $row->answer; }
		if ($this->data->qtype == 'textar') { echo $row->answer; }
		if ($this->data->qtype == 'cbox') { if ($row->answer == 'on') echo 'Checked'; else echo 'Unchecked'; }
		if ($this->data->qtype == 'mcbox' || $this->data->qtype == 'dropdown') {
			$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$row->question.' ORDER BY ordering ASC';
			$db->setQuery( $query );
			$qopts = $db->loadAssocList();
			$answers = explode(' ',$row->answer);
			foreach ($qopts as $opts) {
				if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'<br />'; }
			}
		}
		if ($this->data->qtype == 'yesno') { echo $row->answer; }

		?></td>

	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->disporder+1;
	}
	?>
</table>
</div>

