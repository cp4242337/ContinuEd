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
	echo '<table width="100%" class="zebra">';
	echo '<thead><tr><th>Program</th><th>Date</th><th>Status</th><th></th></tr></thead><tbody>';
	
	foreach ($this->catalog as $course) {
		echo '<tr><td valign="top"><b>';
		echo $course->course_certifname;
		echo '</b></td><td valign="top">'.date("F d, Y", strtotime($course->rec_start)).'</td><td><b>Status:</b> ';
		if ($course->course_haseval) {
			if ($course->rec_pass == 'fail') echo '<span style="color:#800000">Failed</span> ';
			if ($course->rec_pass == 'pass') echo '<span style="color:#008000">Passed</span> ';
			if ($course->rec_pass == 'incomplete') echo '<span style="color:#800000">Incomplete</span> ';

			//echo '</td><td valign="top">';
			if ($course->rec_pass == 'pass' && $course->course_hascertif)echo '</td><td><a href="'.JURI::base( true ).'/components/com_continued/gencert.php?course='.$course->course_id.'" target="_blank">Get Certificate</a>';

		} else echo 'Completed';
		echo '</td></tr>';
	}
	echo '</tbody></table>';
} else echo '<p>At this time, you have not completed any CE courses.</p>';
if (!$this->print) {
	if ($cecfg->REC_POSTTXT) echo '<br />'.$cecfg->REC_POSTTXT;
	?> <?php } ?>
	</div>