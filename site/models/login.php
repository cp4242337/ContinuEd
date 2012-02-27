<?php
/**
 * @package		ContinuEd.Site
 * @subpackage	login
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
/**
 * Rest model class for Users.
 *
 * @package		ContinuEd.Site
 * @subpackage	login
 * @since		1.20
 */
class ContinuEdModelLogin extends JModel
{
	function loginUser() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app=Jfactory::getApplication();
		//Login User
		$options = array();
		$credentials['username'] = JRequest::getVar("login_user");
		$credentials['password'] = JRequest::getVar("login_pass");
		$options['remember'] = true;
		if ($app->login($credentials, $options)) return true;
		else return false;
	}

}
