<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
$user =& JFactory::getUser();
$username = $user->guest ? 'Guest' : $user->name;
if ($username == 'Guest'){
	echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$cecfg['LOGIN_MSG'].'</span></p>';
}
echo '<p align="center"><span style="color:#800000;font-weight:bolder;">You can no longer receive credit for taking this course</span>';
if ($this->passed) {
	echo '<br /><span style="color:#008000">You have already passed this course</span>';
	if ($this->fmtext['hascertif']) echo '<br /><a href="index2.php?option=com_continued&view=certif&course='.$this->fmtext['id'].'" target="_blank"><img alt="Get Certificate" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_certif.png" alt="Get Certificate" border="0"></a>';
}
echo '</p>';
echo '<div class="componentheading">'.$this->fmtext['cname'].'</div>';

if ($this->fmtext['startdate'] != '0000-00-00 00:00:00') {
	echo '<p><b>Release Date:</b> '.date("F d, Y", strtotime($this->fmtext['startdate'])).'<br />';
	echo '<b>Expiration Date:</b> '.date("F d, Y", strtotime($this->fmtext['enddate'])).'</p>';
}
echo $this->fmtext['frontmatter'];
if ($username != 'Guest') {
	?>

<form name="form1" method="post" action="">
<p align="center"><input name="fmagree" id="fmagree" value="true"
	type="hidden"> <input name="Submit" id="Submit" value="Review Material"
	type="image"
	src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'; ?>btn_continue.png"></p>

</form>


	<?php } else { echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$cecfg['LOGIN_MSG'].'</span></p>'; }


	?>