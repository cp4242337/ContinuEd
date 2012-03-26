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
	    <?php if ($cecfg->show_loginreg) { ?>
	    jceq("#regform").validate({
			errorClass:"uf_error",
			errorPlacement: function(error, element) {
		    	error.appendTo( element.parent("div").next("div") );
		    }
	    });	
	    <?php } ?>
	});
</script>
<?php 
echo '<div id="continued-login">';
//Login Description
if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '')) {
	echo '<div class="continued-login-desc">';
	echo $this->params->get('login_description');
	echo '</div>';
}

//Login Form
echo '<div id="continued-login-login">';
echo '<form action="" method="post" name="loginform" id="loginform" target="_top">';
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
echo '</div>';
if ($cecfg->show_loginreg == 1) {
	//Login Form
	echo '<div id="continued-login-reg">';
	echo '<form action="" method="post" name="regform" id="regform" target="_top">';
	echo '<div class="continued-login-reg-row">';
	echo '<div class="continued-login-reg-label"></div>';
	echo '<div class="continued-login-reg-hdr">';
	echo 'Register below';
	echo '</div>';
	echo '</div>';
	
	//group
	echo '<div class="continued-login-reg-row">';
	echo '<div class="continued-login-reg-label">Group</div>';
	echo '<div class="continued-login-reg-value">';
	echo '<select name="jform[userGroupID]" id="jform_userGroupID" class="uf_login" ';
	echo ' validate="{required:true, messages:{required:\'Required\'}}">';
	foreach ($this->glist as $g) {
		echo '<option value="'.$g->ug_id.'">'.$g->ug_name.'</option>';
	}
	echo '</select>';
	echo '</div>';
	echo '<div class="continued-login-login-error"></div>';
	echo '</div>';
	
	//first name
	echo '<div class="continued-login-reg-row">';
	echo '<div class="continued-login-reg-label">First name</div>';
	echo '<div class="continued-login-reg-value">';
	echo '<input name="jform[fname]" id="jform_fname" type="text" class="uf_login" value=""';
	echo ' validate="{required:true, messages:{required:\'Required\'}}">';
	echo '</div>';
	echo '<div class="continued-login-login-error"></div>';
	echo '</div>';
	
	//last name
	echo '<div class="continued-login-reg-row">';
	echo '<div class="continued-login-reg-label">Last Name</div>';
	echo '<div class="continued-login-reg-value">';
	echo '<input name="jform[lname]" id="jform_lname" type="text" class="uf_login" value=""';
	echo ' validate="{required:true, messages:{required:\'Required\'}}">';
	echo '</div>';
	echo '<div class="continued-login-login-error"></div>';
	echo '</div>';
	
	//email
	echo '<div class="continued-login-reg-row">';
	echo '<div class="continued-login-reg-label">Email</div>';
	echo '<div class="continued-login-reg-value">';
	echo '<input name="jform[email]" id="jform_email" type="text" class="uf_login" value=""';
	echo ' validate="{required:true, email:true, remote: { url: \''.JURI::base( true ).'/components/com_continued/helpers/chkemail.php\', type: \'post\'},';
	echo ' messages:{required:\'Required\', email:\'Invalid\', remote:\'Registered\'}}">';
	echo '</div>';
	echo '<div class="continued-login-login-error"></div>';
	echo '</div>';
	
	//captcha
	echo '<div class="continued-login-reg-row">';
	echo '<div class="continued-login-reg-label">Code</div>';
	echo '<div class="continued-login-reg-value">';
	echo '<img id="captcha_img" src="'.JURI::base(true).'/components/com_continued/lib/securimage/securimage_show.php" alt="CAPTCHA Image" />';
	echo '<input name="jform[captcha]" id="jform_captcha" value="" class="uf_login" type="text"';
	echo ' validate="{required:true, messages:{required:\'Required\'}}">';
	echo '<span class="uf_note">';
	echo '<a href="#" onclick="document.getElementById(\'captcha_img\').src = \''.JURI::base(true).'/components/com_continued/lib/securimage/securimage_show.php?\' + Math.random(); return false">Reload Image</a>';
	echo '</span>';
	echo '</div>';
	echo '<div class="continued-login-login-error"></div>';
	echo '</div>';
	
	//Submit Button
	echo '<div class="continued-login-reg-row">';
	echo '<div class="continued-login-reg-label"></div>';
	echo '<div class="continued-login-reg-submit">';
	echo '<input type="image" value="Register" src="media/com_continued/template/'.$cecfg->TEMPLATE.'/'.'btn_register.png" border="0" name="register">';
	echo '</div>';
	echo '</div>';
	
	$newpass = $this->gen_uuid();
	echo '<input type="hidden" name="jform[password]" value="'.$newpass.'">';
	echo '<input type="hidden" name="jform[cpassword]" value="'.$newpass.'">';
	echo '<input type="hidden" name="option" value="com_continued">';
	echo '<input type="hidden" name="view" value="userreg">';
	echo '<input type="hidden" name="layout" value="reguser">';
	echo '<input type="hidden" name="return" value="'.base64_encode($this->redirurl).'" />';
	echo JHtml::_('form.token');
	echo '</form>';
	echo '</div>';
}
echo '</div>';
	echo '<div style="clear:both;"></div>';



?>
</div>
