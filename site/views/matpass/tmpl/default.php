<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
echo '<p align="center" style="color:#008000">You have already passed this course';
if ($this->fmtext['hascertif']) echo '<br /><a href="index2.php?option=com_continued&view=certif&course='.$this->fmtext['id'].'" target="_blank"><img alt="Get Certificate" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_certif.png" alt="Get Certificate" border="0"></a>';
echo '</p>';
echo '<div class="componentheading">'.$this->fmtext['cname'].'</div>';
$res = $mainframe->triggerEvent('onInsertVid',array(& $this->fmtext['material']));
$res = $mainframe->triggerEvent('onInsertQuestionA',array(& $res[0]));
echo $res[0];
echo '<p align="center"><a href="'.$this->fmtext['cataloglink'].'">';
echo '<img src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_return.png" border="0" alt="Return"></a></p>';

echo '<form name="continued_material" id="continued_material" method="post" action="">';
if ($this->fmtext['course_hasinter']) {
	foreach ($this->reqids as $r) {
		echo '<input type="hidden" name="req'.$r.'d" value="1">';
	}
}
echo '</form>';
?>
