<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	$saveOrder	= $listOrder == 'f.ordering';
	$ordering	= ($listOrder == 'f.ordering');
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->uf_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->uf_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=ufield.edit&uf_id='.(int) $item->uf_id); ?>">
				<?php echo $item->uf_name; ?></a> ( <?php echo $item->uf_sname; ?> )
		</td>
		<td class="center">
			<?php if ($item->uf_id > 9) echo JHtml::_('jgrid.published', $item->published, $i, 'ufields.', true);?>
		</td>
        <td class="order">
				<?php if ($saveOrder) :?>
					<?php if ($listDirn == 'asc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, true, 'ufields.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'ufields.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php elseif ($listDirn == 'desc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, true, 'ufields.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'ufields.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php endif; ?>
				<?php endif; ?>
				<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />

		</td>
		<td>
			<?php 
			switch ($item->uf_type) {
				case "textar": echo "Text Box"; break;
				case "textbox": echo "Text Field"; break;
				case "email": echo "Email"; break;
				case "username": echo "Username"; break;
				case "multi": echo "Radio Select"; break;
				case "cbox": echo "Check Box"; break;
				case "mcbox": echo "Multi Checkbox"; break;
				case "yesno": echo "Yes / No"; break;
				case "dropdown": echo "Drop Down"; break;
				case "message": echo "Message"; break;
				case "phone": echo "Phone"; break;
				case "password": echo "Password"; break;
			}
			?>
		</td>
		<td>
			<?php 
			if ($item->uf_req) echo '<span style="color:#008800">Yes</span>';
			else echo '<span style="color:#880000">No</span>'; 
			?>
		</td>
		<td>
			<?php 
			if ($item->uf_reg) echo '<span style="color:#008800">Yes</span>';
			else echo '<span style="color:#880000">No</span>'; 
			?>
		</td>
		<td>
			<?php 
			if ($item->uf_profile) echo '<span style="color:#008800">Yes</span>';
			else echo '<span style="color:#880000">No</span>'; 
			?>
		</td>
		<td>
			<?php 
			if ($item->uf_hidden) echo '<span style="color:#008800">Yes</span>';
			else echo '<span style="color:#880000">No</span>'; 
			?>
		</td>
		<td>
			<?php 
			if ($item->uf_change) echo '<span style="color:#008800">Yes</span>';
			else echo '<span style="color:#880000">No</span>'; 
			?>
		</td>
		<td>
		<?php 
			if ($item->uf_type=='multi' || $item->uf_type=='mcbox' || $item->uf_type=='dropdown') {
				echo '<a href="'.JRoute::_('index.php?option=com_continued&view=uopts&filter_field='.$item->uf_id).'">Options'; 
				$db =& JFactory::getDBO();
				$query = 'SELECT count(*) FROM #__ce_ufields_opts WHERE opt_field="'.$item->uf_id.'"';
				$db->setQuery( $query );
				echo ' ['.$db->loadResult().']</a>'; 
			}
		
		?>
		</td>
	
	</tr>
<?php endforeach; ?>


