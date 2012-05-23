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
echo '<div id="continued-loginmodh">';
if ($type == 'logout') { 
	echo '<form action="" method="post" id="login-form">';
	if ($params->get('greeting')) {
		
		if($params->get('name') == 0)  {
			echo JText::sprintf('MOD_CELOGIN_HINAME', $user->get('name'));
		} else  {
			echo JText::sprintf('MOD_CELOGIN_HINAME', $user->get('username'));
		} 
	
	}
	echo '<input type="submit" name="Submit" class="uf_button" value="'.JText::_('JLOGOUT').'" />';
	echo '<input type="hidden" name="option" value="com_continued" />';
	echo '<input type="hidden" name="view" value="login" />';
	echo '<input type="hidden" name="layout" value="logout" />';
	echo '<input type="hidden" name="return" value="'.$return.'" />';
	echo JHtml::_('form.token');
	
	
	
	echo '<br /><a href="'.JRoute::_('index.php?option=com_continued&view=user&layout=profile').'" >'.JText::_('MOD_CELOGINH_PROFILE').'</a><br />';
	echo '<a href="'.JRoute::_('index.php?option=com_continued&view=user&layout=cerecords').'">'.JText::_('MOD_CELOGINH_CERECORDS').'</a>';

	
	echo '</form>';

 } else { 
	
	echo '<form action="" method="post" id="login-form" name="login-form" >';
	echo '<div class="continued-loginmodh-field">'.JText::_('MOD_CELOGINH_VALUE_USERNAME').':<br />';
	echo '<input id="login_user" type="text" name="login_user" class="uf_modfieldh" >'; 
	echo '</div>';	
	echo '<div class="continued-loginmodh-field">'.JText::_('JGLOBAL_PASSWORD').':<br />';
	echo '<input id="login_pass" type="password" name="login_pass" class="uf_modfieldh" >';
	echo '</div>';	
	echo '<div class="continued-loginmodh-button">';
	echo '<input type="submit" name="Submit" class="uf_button" value="'.JText::_('JLOGIN').'" />';
	echo '</div>';
	echo '<div style="clear:both;"></div>';
	echo '<a href="'.JRoute::_('index.php?option=com_continued&view=lost').'" class="uf_btnlink">'.JText::_('MOD_CELOGINH_FORGOT_YOUR_INFO').'</a><br />';
	$usersConfig = JComponentHelper::getParams('com_users');
	if ($usersConfig->get('allowUserRegistration')) {
		echo '<a href="'.JRoute::_('index.php?option=com_continued&view=userreg').'" class="uf_btnlink">'.JText::_('MOD_CELOGINH_REGISTER').'</a>';
	}
	
	echo '<input type="hidden" name="option" value="com_continued" />';
	echo '<input type="hidden" name="view" value="login" />';
	echo '<input type="hidden" name="layout" value="logmein" />';
	echo '<input type="hidden" name="return" value="'.$return.'" />';
	echo JHtml::_('form.token');
	echo '</form>';
} 
echo '</div>';
echo '<div style="clear:both;"></div>';?>
