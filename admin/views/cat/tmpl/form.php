<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Category Name' ); ?>:
		</label></td>
		<td><input type="text" class="text_area" name="catname" id="catname"
			size="40" maxlen="50" value="<?php echo $this->answer->catname;?>"></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Free Access?' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.booleanlist','catfree','',$this->answer->catfree,'Yes','No','catfree'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Validity' ); ?>:
		</label></td>
		<td>From: <?php echo JHTML::_('calendar',$this->answer->cat_start,'cat_start','cat_start','%Y-%m-%d',null); ?>
		To: <?php echo JHTML::_('calendar',$this->answer->cat_end,'cat_end','cat_end','%Y-%m-%d',null); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'SKU Code' ); ?>:
		</label></td>
		<td><input type="text" class="text_area" name="catsku" id="catsku"
			size="40" maxlen="255" value="<?php echo $this->answer->catsku;?>"><br>
		Leave blank when free</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'SKU Link' ); ?>:
		</label></td>
		<td><input type="text" class="text_area" name="catskulink"
			id="catskulink" size="40" maxlen="255"
			value="<?php echo $this->answer->catskulink;?>"><br>
		Leave blank when free</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Category Desc' ); ?>:
		</label></td>
		<td><?php echo $this->editor->display('catdesc',$this->answer->catdesc,'100%','300','30','30',false); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Enable Front Matter' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.booleanlist','cathasfm','',$this->answer->cathasfm,'Yes','No','cathasfm'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Front Matter' ); ?>:
		</label></td>
		<td><?php echo $this->editor->display('catfm',$this->answer->catfm,'100%','300','30','30',false); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Show Details Button' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.booleanlist','catfmlink','',$this->answer->catfmlink,'Yes','No','catfmlink'); ?>
		</td>
	</tr>

</table>
</fieldset>

<fieldset class="adminform"><legend><?php echo JText::_( 'Catagory Nesting' ); ?></legend>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Previous Catagory?' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.booleanlist','catprev','',$this->answer->catprev,'Yes','No','catprev'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Category Menu' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.genericlist',$this->catlist,'catmenu',NULL,'cid','catname',$this->answer->catmenu); ?>
		</td>
	</tr>
</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="cid" value="<?php echo $this->answer->cid; ?>" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="controller" value="cat" /></form>
