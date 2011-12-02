<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuedHelper::getConfig();
$db =& JFactory::getDBO();
echo '<div class="componentheading">'.$this->cinfo->course_name.'</div>';
$cpart = 0;
foreach ($this->qanda as $qanda) {
	echo '<p><b>'.$qanda->qtext.'</b><br /><em>From: '.$qanda->username.'</p>';
}
echo '<p align="center">';
//Refresh Button
echo '<a href="index.php?option=com_continued&course='.$this->cinfo->id.'&view=qanda&Itemid='.JRequest::getVar('Itemid').'">';
echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_refresh.png" border="0" alt="Refresh"></a>';
//Return Button
echo '<a href="'.$this->redirurl.'">';
echo '<img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_return.png" border="0" alt="Return"></a>';
echo '</p>';
?>
</div>