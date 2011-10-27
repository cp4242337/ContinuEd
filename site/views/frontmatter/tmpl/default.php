<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');

global $cecfg;
$user =& JFactory::getUser();
$username = $user->guest ? 'Guest' : $user->name;
echo '<div class="componentheading">'.$this->cinfo->course_name.'</div>';
if ($username == 'Guest'){
	echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$cecfg->LOGIN_MSG.'</span></p>';
}
echo '<div id="continued-fm-text">';
//dateinfo
if ($this->cinfo->startdate != '0000-00-00 00:00:00') {
	echo '<p><b>Release Date:</b> '.date("F d, Y", strtotime($this->cinfo->course_startdate)).'<br />';
	echo '<b>Expiration Date:</b> '.date("F d, Y", strtotime($this->cinfo->course_enddate)).'</p>';
}
echo $this->cinfo->course_frontmatter;
echo '</div>';

if ($username != 'Guest' && $this->paid) {
if ($this->expired) { ?>

<form name="agreement" method="post" action=""	onsubmit="return isChecked(this.fmagree);">
	<div align="center">
		<input type="hidden" name="token" value="<?php echo $this->token; ?>">
		<input name="fmagree" id="fmagree" value="true" type="hidden">
		<input name="Submit" id="Continue to Educational Activity" value="Continue to Educational Activity" type="image"	src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'; ?>btn_continue.png">
	</div>
</form>
<?php 
	
} else {
	?>

<form name="agreement" method="post" action=""	onsubmit="return isChecked(this.fmagree);">
	<input type="hidden" name="token" value="<?php echo $this->token; ?>">
	<div align="center">
		<table id="continued-fm-agree" border="0" cellpadding="0" cellspacing="0" width="500" align="center">
			<tbody>
				<tr><td colspan="2" align="center"><div id="continued-fm-error"><?php echo $cecfg->FM_AGREE_ERROR; ?><br /><br /></div></td></tr>
				<tr><td align="left" valign="top" width="30"><input name="fmagree" id="fmagree" value="true" type="checkbox"></td>
				<td valign="top" width="470" align="left"><?php echo $cecfg->FM_TEXT; ?></td></tr>
				<tr><td colspan="2" align="center"><br><input name="Submit" id="Continue to Educational Activity" value="Continue to Educational Activity" type="image"	src="<?php echo 'media/com_continued/template/'.$cecfg->TEMPLATE.'/'; ?>btn_continue.png"></td></tr>
			</tbody>
		</table>
	</div>
</form>

<script type="text/javascript">
<!--
function isChecked(elem) {
	var lyr = document.getElementById('continued-fm-error');
	var tbl = document.getElementById('continued-fm-agree');
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

<?php } } else { 
	if ($username == 'Guest') echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$cecfg->LOGIN_MSG.'</span></p>';
	if ($this->cinfo->course_purchase) echo '<p align="center"><a href="'.$this->cinfo->course_purchaselink.'"><img src="media/com_continued/template/'.$cecfg->TEMPLATE.'/btn_purchase.png" border="0"></p>';
}


?>
</div>