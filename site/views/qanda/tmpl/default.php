<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuedHelper::getConfig();
$db =& JFactory::getDBO();
echo '<div class="componentheading">'.$this->cinfo->course_name.'</div>';
$cpart = 0;
foreach ($this->qanda as $qanda) {
	echo '<p><b>'.$qanda->q_text.'</b><br /><em>From: '.$qanda->username.'</p>';
}
echo '<p align="center">';
//Refresh Button
echo '<a href="index.php?option=com_continued&course='.$this->cinfo->course_id.'&view=qanda&Itemid='.JRequest::getVar('Itemid').'" class="cebutton">';
echo 'Refresh</a>';
//Return Button
echo '<a href="'.$this->redirurl.'" class="cebutton">';
echo 'Return</a>';
echo '</p>';
?>
</div>