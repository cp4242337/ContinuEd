<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
if ($this->expired) echo '<p align="center" style="color:#800000">You can no longer receive credit for taking this course</p>';
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
