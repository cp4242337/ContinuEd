<?php defined('_JEXEC') or die('Restricted access');
$qtypes[0]=JHTML::_('select.option','multi','Radio Select');
$qtypes[1]=JHTML::_('select.option','textbox','Text Field');
$qtypes[2]=JHTML::_('select.option','cbox','Checkbox Single');
$qtypes[3]=JHTML::_('select.option','mcbox','Multi Checkbox');
$qtypes[4]=JHTML::_('select.option','textar','Text Box');
$qtypes[5]=JHTML::_('select.option','yesno','Yes/No Select');
$qtypes[6]=JHTML::_('select.option','dropdown','Drop Down');
$qtypes[7]=JHTML::_('select.option','qanda','Q and A');
$qcats[0]=JHTML::_('select.option','eval','Eval');
$qcats[1]=JHTML::_('select.option','assess','Assessment');
$qcats[2]=JHTML::_('select.option','qanda','Q and A');
$db =& JFactory::getDBO();
$q = 'SELECT * FROM #__ce_questions WHERE course = '.$this->courseid.' && qtype="yesno" ORDER BY ordering';
$db->setQuery($q); $res = $db->loadObjectList();
$qdeps[]=JHTML::_('select.option','0','-- None --');
if ($res) { foreach ($res as $r) {
	$qdeps[]=JHTML::_('select.option',$r->id,$r->ordering.'. '.$r->qtext);
}}

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="course"
	value="<?php echo $this->courseid; ?>">

<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Published' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.booleanlist','published','',$this->question->published,'Yes','No','published'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Question' ); ?>:
		</label></td>
		<td><textarea class="text_area" name="qtext" id="qtext" cols="60"
			rows="2"><?php echo $this->question->qtext;?></textarea></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Question #' ); ?>:
		</label></td>
		<td><?php echo $this->question->ordering;?> <input type="hidden"
			name="ordering" value="<?php echo $this->question->ordering; ?>"></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Area' ); ?>:
		</label></td>
		<td><?php echo $this->question->q_area;?> <input type="hidden"
			name="q_area" value="<?php echo $this->question->q_area; ?>"></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Type' ); ?>:
		</label></td>
		<td><?php 
			if ($this->question->q_area != "inter") echo JHTML::_('select.genericlist',$qtypes,'qtype',NULL,'value','text',$this->question->qtype);
			else  echo '<input type="hidden" name="qtype" value="multi">Radio Select';
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Category' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.genericlist',$qcats,'qcat',NULL,'value','text',$this->question->qcat).' Only radio select type questions can be asssessment questions'; ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Part' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.integerlist',1,$this->numparts,1,'qsec','',$this->question->qsec); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Required' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.booleanlist','qreq','',$this->question->qreq,'Yes','No','qreq'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Dependency' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.genericlist',$qdeps,'q_depq',NULL,'value','text',$this->question->q_depq); ?>YEs/No
		Questions only, Dependency is on yes only, only for text, question
		must be in same part</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Explinantion' ); ?>:
		</label></td>
		<td><textarea class="text_area" name="q_expl" id="q_expl" cols="60"
			rows="2"><?php echo $this->question->q_expl;?></textarea></td>
	</tr>
</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" />
<input type="hidden" name="id" value="<?php echo $this->question->id; ?>" />
<input type="hidden" name="q_addedby" value="<?php echo $this->question->q_addedby; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="question" /></form>
