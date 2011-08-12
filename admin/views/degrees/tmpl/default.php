<?php defined('_JEXEC') or die('Restricted access');
$order = JHTML::_('grid.order', $this->items);
?>
<div id="editcell"><?php echo JText::_( 'Degrees must be added thru Community Builder' ); ?>
<table class="adminlist">
	<thead>
		<tr>
			<th width="50%"><?php echo JText::_( 'Degree' ); ?></th>
			<th width="50%"><?php echo JText::_( 'Certificate' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$link 		= JRoute::_( 'index.php?option=com_continued&view=degree&degree='. $row->fieldtitle );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><a href="<?php echo $link; ?>"><?php echo $row->fieldtitle; ?></a>
		</td>
		<td><?php echo $row->crt_name; ?></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
</table>
</div>

