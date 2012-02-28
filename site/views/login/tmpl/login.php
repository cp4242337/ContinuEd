<div id="continued">
<?php
/**
 * @package		Continued.Site
 * @subpackage	login
 * @copyright	Copyright (C) 2005 - 2012 Corona Productions, Inc. & Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.20
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
echo '<h2 class="componentheading">User Login</h2>';
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
if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '')) {
	echo '<div class="continued-login-desc">';
	echo $this->params->get('login_description');
	echo '</div>';
}

//Login Form
echo '<div id="continued-login-login">';
echo '<form action="" method="post" name="loginform" id="loginform">';
echo '<div class="continued-login-login-row">';
echo '<div class="continued-login-login-label"></div>';
echo '<div class="continued-login-login-hdr">';
echo 'Login below';
echo '</div>';
echo '</div>';

//Username
echo '<div class="continued-login-login-row">';
echo '<div class="continued-login-login-label">Username</div>';
echo '<div class="continued-login-login-value">';
echo '<input name="login_user" id="login_user" type="text" class="uf_login" value=""';
echo ' validate="{required:true, messages:{required:\'Required\'}}">';
echo '</div>';
echo '<div class="continued-login-login-error"></div>';
echo '</div>';

//Password
echo '<div class="continued-login-login-row">';
echo '<div class="continued-login-login-label">Password</div>';
echo '<div class="continued-login-login-value">';
echo '<input name="login_pass" id="login_pass" type="password" class="uf_login" value=""';
echo ' validate="{required:true, messages:{required:\'Required\'}}">';
echo '</div>';
echo '<div class="continued-login-login-error"></div>';
echo '</div>';

//Submit Button
echo '<div class="continued-login-login-row">';
echo '<div class="continued-login-login-label"></div>';
echo '<div class="continued-login-login-submit">';
echo '<input type="image" value="Login" src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.'btn_login.png" border="0" name="submit">';
echo '</div>';
echo '</div>';

//Lost Password/Register
echo '<div class="continued-login-login-row">';
echo '<div class="continued-login-login-label"></div>';
echo '<div class="continued-login-login-footer">';
echo '<a href="'.JRoute::_("index.php?option=com_continued&view=lost").'">Lost Username/Password</a><br />';
$usersConfig = JComponentHelper::getParams('com_users');
if ($usersConfig->get('allowUserRegistration')) {
	echo '<a href="'.JRoute::_("index.php?option=com_continued&view=userreg").'">Register</a>';
}
echo '</div>';
echo '</div>';

echo '<input type="hidden" name="option" value="com_continued">';
echo '<input type="hidden" name="view" value="login">';
echo '<input type="hidden" name="layout" value="logmein">';
echo '<input type="hidden" name="return" value="'.base64_encode($this->redirurl).'" />';
echo JHtml::_('form.token');
echo '</form>';
echo '<div style="clear:both;"></div>';
echo '</div>';

?>
</div>
