<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->ctmpl_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->ctmpl_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=certif.edit&ctmpl_id='.(int) $item->ctmpl_id); ?>">
				<?php echo $this->escape($item->cert_type); ?></a>
		</td>
		<td>
			<?php echo $item->provider_name; ?>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'certifs.', true);?>
		</td>
		
	
	</tr>
<?php endforeach; ?>

