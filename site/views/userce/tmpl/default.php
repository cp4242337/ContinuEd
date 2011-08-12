<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
if (!$this->print) {
	?>
<div class="componentheading"><a
	href="index2.php?option=com_continued&view=userce&print=1"
	target="_blank"><img src="components/com_continued/printButton.png"
	border="0" align="right"></a><?php echo $cecfg['REC_TIT']; ?></div>
<p><?php 
} else {
	echo '<div class="componentheading">'.$cecfg['REC_TIT'].'<a href="javascript:print()"><img src="components/com_continued/printButton.png" border="0"> Print</a> </div>';
}
if ($cecfg['REC_PRETXT']) echo $cecfg['REC_PRETXT'];
else echo '<p>Here are the accredited activities you\'ve taken.</p>';

if ($this->catalog) {
	echo '<table width="100%">';
	//echo '<tr><td>Category</td><td><b>Course</b></td><td><b>Date Taken</b></td><td><b>Pass/Fail</b></td><td></td></tr>';
	foreach ($this->catalog as $course) {
		echo '<tr><td valign="top" colspan="2"><a href="index.php?option=com_continued&cat='.$course['cid'].'&Itemid='.JRequest::getVar('Itemid').'">'.$course['catname'].'</a><br /><b>';
		echo $course['cname'];
		echo '</b></td></tr><tr><td valign="top"><b>Date Taken:</b> '.date("F d, Y", strtotime($course['ctime'])).'</td><td><b>Status:</b> ';
		if ($course['haseval']) {
			if ($course['cpass'] == 'fail') echo '<span style="color:#800000">Failed</span>';
			if ($course['cpass'] == 'pass') echo '<span style="color:#008000">Passed</span>';

			//echo '</td><td valign="top">';
			if ($course['cpass'] == 'pass' && $course['hascertif'])echo ' <a href="index2.php?option=com_continued&view=certif&course='.$course['id'].'" target="_blank">Get Certificate</a>';

		} else echo 'Completed';
		echo '</td></tr>';
		echo '<tr><td colspan="2"><hr size="1"></td></tr>';
	}
	echo '</table>';
} else echo '<p>At this time, you have not completed any CE courses.</p>';
if (!$this->print) {
	if ($cecfg['REC_POSTTXT']) echo '<br />'.$cecfg['REC_POSTTXT'];
	?> <?php } ?>