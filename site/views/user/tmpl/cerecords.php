<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
if (!$this->print) {
	?>
<h2 class="componentheading"><a
	href="index.php?option=com_continued&view=userce&print=1&tmpl=component"
	target="_blank"><img src="components/com_continued/printButton.png"
	border="0" align="right"></a><?php echo $cecfg->REC_TIT; ?></h2>
<?php 
} else {
	echo '<div class="componentheading">'.$cecfg->REC_TIT.'<a href="javascript:print()"><img src="components/com_continued/printButton.png" border="0"> Print</a> </div>';
}
if ($cecfg->REC_PRETXT) echo $cecfg->REC_PRETXT;
else echo '<p>Here are the accredited activities you\'ve taken.</p>';

if ($this->catalog) {
	echo '<table width="100%">';
	foreach ($this->catalog as $course) {
		echo '<tr><td valign="top" colspan="2"><a href="index.php?option=com_continued&cat='.$course->cat_id.'&Itemid='.JRequest::getVar('Itemid').'">'.$course->cat_name.'</a><br /><b>';
		echo $course->course_name;
		echo '</b></td></tr><tr><td valign="top"><b>Date Started:</b> '.date("F d, Y", strtotime($course->rec_start)).'<br /><b>Date Finished:</b> '.date("F d, Y", strtotime($course->rec_end)).'</td><td><b>Status:</b> ';
		if ($course->course_haseval) {
			if ($course->rec_pass == 'fail') echo '<span style="color:#800000">Failed</span>';
			if ($course->rec_pass == 'pass') echo '<span style="color:#008000">Passed</span>';
			if ($course->rec_pass == 'incomplete') echo '<span style="color:#800000">Incomplete</span>';

			//echo '</td><td valign="top">';
			if ($course->cpass == 'pass' && $course->course_hascertif)echo ' <a href="index.php?option=com_continued&tmpl=component&view=certif&course='.$course->course_id.'" target="_blank">Get Certificate</a>';

		} else echo 'Completed';
		echo '</td></tr>';
		echo '<tr><td colspan="2"><hr size="1"></td></tr>';
	}
	echo '</table>';
} else echo '<p>At this time, you have not completed any CE courses.</p>';
if (!$this->print) {
	if ($cecfg->REC_POSTTXT) echo '<br />'.$cecfg->REC_POSTTXT;
	?> <?php } ?>
	</div>