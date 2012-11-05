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
if ($type == 'logout') { 
	echo '<form action="" method="post" id="login-form">';
	if ($params->get('greeting')) {
		echo '<div class="continued-loginmod-row">';
		echo '<div class="continued-loginmod-hdr">';
	if($params->get('name') == 0)  {
			echo JText::sprintf('MOD_CELOGIN_HINAME', $user->get('name'));
		} else  {
			echo JText::sprintf('MOD_CELOGIN_HINAME', $user->get('username'));
		} 
	echo '</div></div>';
	}
	echo '<div class="continued-loginmod-row">';
	echo '<div class="continued-loginmod-submit">';
	echo '<input type="submit" name="Submit" class="uf_button" value="'.JText::_('JLOGOUT').'" />';
	echo '<input type="hidden" name="option" value="com_continued" />';
	echo '<input type="hidden" name="view" value="login" />';
	echo '<input type="hidden" name="layout" value="logout" />';
	echo '<input type="hidden" name="return" value="'.$return.'" />';
	echo JHtml::_('form.token');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="continued-loginmod-row">';
	echo '<div class="continued-loginmod-footer">';
	echo '<a href="'.JRoute::_('index.php?option=com_continued&view=user&layout=profile').'">'.JText::_('MOD_CELOGIN_PROFILE').'</a><br />';
	echo '<a href="'.JRoute::_('index.php?option=com_continued&view=user&layout=cerecords').'">'.JText::_('MOD_CELOGIN_CERECORDS').'</a>';
	if ($cecfg->purchase) echo '<br /><a href="'.JRoute::_('index.php?option=com_continued&view=user&layout=purchases').'">'.JText::_('MOD_CELOGIN_PURCHASES').'</a>';
	echo '</div>';
	echo '</div>';
	
	echo '</form>';
 } else { 
	
	echo '<form action="" method="post" id="login-form" name="login-form" >';
	if ($params->get('pretext')) {
		echo '<div class="pretext"><p>'.$params->get('pretext').'</p></div>';
	}
	
	echo '<div class="continued-loginmod-row">';
	echo '<div class="continued-loginmod-label">'.JText::_('MOD_CELOGIN_VALUE_USERNAME').'</div>';
	echo '<div class="continued-loginmod-value"><input id="login_user" type="text" name="login_user" class="uf_modfield">'; 
	echo '</div>';
	echo '<div class="continued-loginmod-error"></div>';
	echo '</div>';
	
	echo '<div class="continued-loginmod-row">';
	echo '<div class="continued-loginmod-label">'.JText::_('JGLOBAL_PASSWORD').'</div>';
	echo '<div class="continued-loginmod-value"><input id="login_pass" type="password" name="login_pass" class="uf_modfield">';
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
		echo '<a href="'.JRoute::_('index.php?option=com_continued&view=userreg&return='.$return).'">'.JText::_('MOD_CELOGIN_REGISTER').'</a>';
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
