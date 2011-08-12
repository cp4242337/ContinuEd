<?php defined('_JEXEC') or die('Restricted access');
echo '<form action="index.php" method="post" name="adminForm" id="adminForm">';
echo '<p>Copy question(s) to?</p>';
echo JHTML::_('select.genericlist',$this->courses,'course',NULL,'id','cname',NULL);
echo '<p><b>Question to Copy:</b><br />';
foreach ($this->questions as $q) {
	echo '<input type="hidden" name="cid[]" value='.$q['id'].' />';
	echo $q['qtext'].'<br />';
}
echo '</p>';
?>
<input
	type="hidden" name="option" value="com_continued" />
<input type="hidden" name="task" value="" />
<input type="hidden"
	name="controller" value="copyq" />
</form>
