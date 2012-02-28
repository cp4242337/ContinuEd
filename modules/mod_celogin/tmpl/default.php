<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_celogin
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
echo '<div id="continued-loginmod">';
if ($type == 'logout') { ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">
<?php if ($params->get('greeting')) : ?>
	<div class="login-greeting">
	<?php if($params->get('name') == 0) : {
		echo JText::sprintf('MOD_CELOGIN_HINAME', $user->get('name'));
	} else : {
		echo JText::sprintf('MOD_CELOGIN_HINAME', $user->get('username'));
	} endif; ?>
	</div>
<?php endif; ?>
	<div class="logout-button">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_continued" />
		<input type="hidden" name="view" value="login" />
		<input type="hidden" name="layout" value="logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<ul>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_continued&view=user&layout=profile'); ?>">
			<?php echo JText::_('MOD_CELOGIN_PROFILE'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_continued&view=user&layout=userce'); ?>">
			<?php echo JText::_('MOD_CELOGIN_CERECORDS'); ?></a>
		</li>
	</ul>
	
</form>
<?php } else { 
	?>
	<script type="text/javascript">
	jceq(document).ready(function() {
		jceq.metadata.setType("attr", "validate");
		jceq("#login-form").validate({
			errorClass:"uf_error",
			errorPlacement: function(error, element) {
		    	error.appendTo( element.parent("div").next("div") );
		    }
	    });	
	});
	</script>
	<?php 
	echo '<form action="" method="post" id="login-form" name="login-form" >';
	if ($params->get('pretext')) {
		echo '<div class="pretext"><p>'.$params->get('pretext').'</p></div>';
	}
	
	echo '<div class="continued-loginmod-row">';
	echo '<div class="continued-loginmod-label">'.JText::_('MOD_CELOGIN_VALUE_USERNAME').'</div>';
	echo '<div class="continued-loginmod-value"><input id="login_user" type="text" name="login_user" class="uf_modfield"';
	echo ' validate="{required:true, messages:{required:\'Required\'}}">'; 
	echo '</div>';
	echo '<div class="continued-loginmod-error"></div>';
	echo '</div>';
	
	echo '<div class="continued-loginmod-row">';
	echo '<div class="continued-loginmod-label">'.JText::_('JGLOBAL_PASSWORD').'</div>';
	echo '<div class="continued-loginmod-value"><input id="login_pass" type="password" name="login_pass" class="uf_modfield"';
	echo ' validate="{required:true, messages:{required:\'Required\'}}">';
	echo '</div>';
	echo '<div class="continued-loginmod-error"></div>';
	echo '</div>';

	echo '<div class="continued-loginmod-row">';
	echo '<div class="continued-loginmod-submit"><input type="submit" name="Submit" class="uf_button" value="'.JText::_('JLOGIN').'" /></div>';
	echo '</div>';
	echo '<div class="continued-loginmod-row">';
	echo '<div class="continued-loginmod-footer">';
	echo '<a href="'.JRoute::_('index.php?option=com_continued&view=lost').'">'.JText::_('MOD_CELOGIN_FORGOT_YOUR_INFO').'</a><br />';
	$usersConfig = JComponentHelper::getParams('com_users');
	if ($usersConfig->get('allowUserRegistration')) {
		echo '<a href="'.JRoute::_('index.php?option=com_continued&view=userreg').'">'.JText::_('MOD_CELOGIN_REGISTER').'</a>';
	}
	echo '</div>';
	echo '</div>';
	echo '<input type="hidden" name="option" value="com_continued" />';
	echo '<input type="hidden" name="view" value="login" />';
	echo '<input type="hidden" name="layout" value="logmein" />';
	echo '<input type="hidden" name="return" value="'.$return.'" />';
	echo JHtml::_('form.token');
	if ($params->get('posttext')) {
		echo '<div class="posttext"><p>'.$params->get('posttext').'</p></div>';
	}
	echo '</form>';
} 
echo '</div>';
echo '<div style="clear:both;"></div>';?>
