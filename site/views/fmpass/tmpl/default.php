<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
echo '<p align="center" style="color:#008000">You have already passed this course';
if ($this->fmtext['hascertif']) {
	echo '<br /><a href="index2.php?option=com_continued&view=certif&course='.$this->fmtext['id'].'" target="_blank">';
	echo '<img alt="Get Certificate" src="media/com_continued/template/'.$cecfg['TEMPLATE'].'/btn_certif.png" alt="Get Certificate" border="0"></a>';
}
echo '</p>';
echo '<div class="componentheading">'.$this->fmtext['cname'].'</div>';
$user =& JFactory::getUser();
$username = $user->guest ? 'Guest' : $user->name;
if ($username == 'Guest'){
	echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$cecfg['LOGIN_MSG'].'</span></p>';
}

if ($this->fmtext['startdate'] != '0000-00-00 00:00:00') {
	echo '<p><b>Release Date:</b> '.date("F d, Y", strtotime($this->fmtext['startdate'])).'<br />';
	echo '<b>Expiration Date:</b> '.date("F d, Y", strtotime($this->fmtext['enddate'])).'</p>';
}
echo $this->fmtext['frontmatter'];
if ($username != 'Guest') {
	?>

<form name="form1" method="post" action=""
	onsubmit="return isChecked(this.fmagree);">
<div style="background-color: rgb(255, 255, 255);" align="center">
<table id="agreet" style="border: medium none; padding: 5px;"
	bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0"
	width="500" align="center">
	<tbody>
		<tr>
			<td colspan="2" align="center">
			<div id="cbError"
				style="color: rgb(136, 0, 0); font-size: 10pt; font-weight: bold; display: none; position: relative;">You
			must agree to the following statement:<br>
			<br>
			</div>
			</td>

		</tr>
		<tr>
			<td align="left" valign="top" width="30"><input name="fmagree"
				id="fmagree" value="true" type="checkbox"></td>
			<td valign="top" width="470" align="left"><span class="style2"><?php echo $cecfg['FM_TEXT']; ?></span></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br>
			<input name="Submit" id="Submit" value="Review Material" type="image"
				src="<?php echo 'media/com_continued/template/'.$cecfg['TEMPLATE'].'/'; ?>btn_continue.png"></td>
		</tr>

	</tbody>
</table>
<br>
</div>
</form>
<script type="text/javascript">
<!--
function isChecked(elem) {
	var lyr = document.getElementById('cbError');
	var tbl = document.getElementById('agreet');
	if (elem.checked) {
		lyr.style.display='none'; 
		tbl.style.border='none';
		return true;
	} else { 
		lyr.style.display='inline'; 
		tbl.style.border='thick #880000 solid';
		elem.focus();
		return false; 
	}
}

//-->
</script>

	<?php } else { echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$cecfg['LOGIN_MSG'].'</span></p>'; }


	?>