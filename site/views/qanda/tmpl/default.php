<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
$db =& JFactory::getDBO();
echo '<div class="componentheading">'.$this->cinfo['cname'].'</div>';
$cpart = 0;
foreach ($this->qanda as $qanda) {
	echo '<p><b>'.$qanda->qtext.'</b><br /><em>From: '.$qanda->username.'</p>';
}
?>

<?php
echo '<p align="center"><a href="'.$this->cinfo['cataloglink'].'">';
echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_return.png" border="0" alt="Return"></a></p>';

?>
