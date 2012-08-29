<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$config = ContinuEdHelper::getConfig();
$user =& JFactory::getUser();
$username = $user->guest ? 'Guest' : $user->name;
echo '<h2 class="componentheading">'.$this->cinfo->course_name.'</h2>';
if ($username == 'Guest'){
	echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$config->LOGIN_MSG.'</span></p>';
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
		<input name="Submit" id="Continue" value="Continue" type="submit" class="cebutton">
	</div>
</form>
<?php 
	
} else {
	?>

	
	
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery.metadata.setType("attr", "validate");
		jQuery("#verify").validate({
			errorPlacement: function(error, element) {
		    	error.appendTo( element.parent("div").parent("div").prev("div") );
		    },
		    highlight: function(element, errorClass, validClass) {
		    	jQuery("#continued-fm-verify").addClass("continued-verify-errorstate");
		    },
		    unhighlight: function(element, errorClass, validClass){
		        	jQuery("#continued-fm-verify").removeClass("continued-verify-errorstate");
		    }
			
	    });

	});


</script>

<form name="verify" id="verify" method="post" action="">
<div id="continued-fm-verify">
	<div id="continued-fm-verify-error"></div>	
	<div id="continued-fm-verify-checkbox">
		<div style="width:5%;display:block;float:left;text-align:right;"><input name="fmagree" id="fmagree" value="true" type="checkbox" validate="{required:true, messages:{required:'<?php echo $config->FM_AGREE_ERROR; ?>'}}"></div>
		<div style="width:90%;display:block;float:left;"><label for="fmagree"><?php echo $config->FM_TEXT; ?></label></div>
		<div style="width:5%;display:block;float:left;"></div>
		<div style="clear:both"></div>
	</div>
	<div id="continued-fm-verify-submit"><input name="Submit" id="Continue" value="Continue" type="submit" class="cebutton"></div>
</div>
</form>	
	

<?php } } else { 
	if ($username == 'Guest') echo '<p align="center"><span style="color:#800000;font-weight:bolder;">'.$config->LOGIN_MSG.'</span></p>';
	else if ($this->cinfo->course_purchase) echo '<p align="center"><a href="'.JRoute::_('index.php?option=com_continued&view=purchase&course='.$this->cinfo->course_id).'" class="cebutton">Purchase - $'.$this->cinfo->course_purchaseprice.'</a></p>';
}


?>
</div>