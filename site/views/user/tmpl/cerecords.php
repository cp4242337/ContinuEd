<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
if (!$this->print) {
	?>
<h2 class="componentheading"><a	href="index.php?option=com_continued&view=user&layout=cerecords&print=1&tmpl=component"	target="_blank"><img src="components/com_continued/printButton.png"	border="0" align="right"></a><?php echo $cecfg->REC_TIT; ?></h2>
<?php 
} else {
	echo '<div class="componentheading">'.$cecfg->REC_TIT.'<a href="javascript:print()"><img src="components/com_continued/printButton.png" border="0"> Print</a> </div>';
}
if ($cecfg->REC_PRETXT) echo $cecfg->REC_PRETXT;
else echo '<p>Here are the accredited programs you\'ve taken.</p>';

if ($this->catalog) {
	echo '<table width="100%" class="zebra">';
	echo '<thead><tr><th>Program</th><th>Date</th><th>Credits</th><th>Status</th><th></th></tr></thead><tbody>';
	$total_credits = 0;
	foreach ($this->catalog as $course) {
		echo '<tr><td><b>';
		if ($course->course_certifname) echo $course->course_certifname;
		else echo $course->course_name;
		echo '</b></td><td>';
		if (!$course->course_haseval || $course->rec_pass == 'incomplete') echo date("F d, Y", strtotime($course->rec_start));
		else echo date("F d, Y", strtotime($course->rec_end));
		echo '</td><td>';
		if ($course->course_haseval && $course->rec_pass == 'pass') {
			echo number_format($course->course_credits,2);
			$total_credits = $total_credits + floatval($course->course_credits);
		}
		else echo "";
		echo '</td><td> ';
		if ($course->course_haseval) {
			if ($course->rec_pass == 'fail') echo '<span style="color:#800000">Failed</span> ';
			if ($course->rec_pass == 'pass') echo '<span style="color:#008000">Passed</span> ';
			if ($course->rec_pass == 'flunked') echo '<span style="color:#800000">Flunked</span> ';
			if ($course->rec_pass == 'incomplete') echo '<span style="color:#800000">Incomplete</span> ';
	
			echo '</td><td>';
			if ($course->rec_pass == 'pass' && $course->course_hascertif) echo '<a href="'.JURI::base( true ).'/components/com_continued/gencert.php?course='.$course->course_id.'" target="_blank" class="cebutton_edit">Get Certificate</a>';

		} else echo 'Completed</td><td>';
		echo '</td></tr>';
	}
	echo '</tbody>';
	echo '<tfoot><tr><td colspan="5"><strong>Total Credits: '.number_format($total_credits,2).'</strong></td></tr></tfoot>';
	echo '</table>';
} else echo '<p>At this time, you have not completed any CE programs.</p>';
if (!$this->print) {
	if ($cecfg->REC_POSTTXT) echo '<br />'.$cecfg->REC_POSTTXT;
	?> <?php } ?>
	</div>