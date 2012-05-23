<div id="continued">
<?php
/**
 * @package		Continued.Site
 * @subpackage	lost.default
 * @copyright	Copyright (C) 2005 - 2012 Corona Productions, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.20
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
echo '<h2 class="componentheading">Lost username/password</h2>';
$cecfg = ContinuEdHelper::getConfig();
?>
<script type="text/javascript">
	jceq(document).ready(function() {
		jceq.metadata.setType("attr", "validate");
		jceq("#loginform").validate({
			errorClass:"uf_error",
			errorPlacement: function(error, element) {
		    	error.appendTo( element.parent("div").next("div") );
		    }
	    });	
	});
</script>
<?php 
//Login Description
if (($this->params->get('lostdescription_show') == 1 && str_replace(' ', '', $this->params->get('lost_description')) != '')) {
	echo '<div class="continued-lost-desc">';
	echo $this->params->get('lost_description');
	echo '</div>';
}

//Login Form
echo '<div id="continued-lost-lost">';
echo '<form action="" method="post" name="loginform" id="loginform">';
echo '<div class="continued-lost-lost-row">';
echo '<div class="continued-lost-lost-hdr">';
echo 'Please enter the email address for your account. Your username and a new password will be sent to you.';
echo '</div>';
echo '</div>';

//Email Address
echo '<div class="continued-lost-lost-row">';
echo '<div class="continued-lost-lost-label">Email</div>';
echo '<div class="continued-lost-lost-value">';
echo '<input name="lost_email" id="lost_email" type="text" class="uf_lost" value=""';
echo ' validate="{required:true, email:true, messages:{required:\'This Field is required\', email:\'Email address must be valid\'}}">';
echo '</div>';
echo '<div class="continued-lost-lost-error"></div>';
echo '</div>';

//Submit Button
echo '<div class="continued-lost-lost-row">';
echo '<div class="continued-lost-lost-label"></div>';
echo '<div class="continued-lost-lost-submit">';
echo '<input type="submit" value="Send Information" class="cebutton" border="0" name="submit">';
echo '</div>';
echo '</div>';



echo '<input type="hidden" name="option" value="com_continued">';
echo '<input type="hidden" name="view" value="lost">';
echo '<input type="hidden" name="layout" value="sendinfo">';
echo JHtml::_('form.token');
echo '</form>';
echo '<div style="clear:both;"></div>';
echo '</div>';

?>
</div>
