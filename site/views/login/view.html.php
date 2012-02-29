<?php
/**
 * @package		ContinuEd.Site
 * @subpackage	login
 * @copyright	Copyright (C) 2005 - 2012 Corona Productions. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Login view class for Users.
 *
 * @package		Continued.Site
 * @subpackage	login
 * @since		1.20
 */
class ContinuEdViewLogin extends JView
{
	protected $params;
	protected $user;
	protected $redirurl;

	/**
	 * Method to display the view.
	 *
	 * @param	string	The template file to include
	 * @since	1.20
	 */
	public function display($tpl = null) {
		// Get the view data.
		$this->user		= JFactory::getUser();
		$this->params	= JFactory::getApplication()->getParams('com_continued');
		$layout = $this->getLayout();
		
		switch($layout) {
			case "login": 
				$this->userLogIn();
				break;
			case "logout": 
				$this->userLogOut();
				break;
			case "logmein": 
				$this->logInUser();
				break;
		}
		parent::display($tpl);
	}

	protected function userLogIn() {
		$this->redir = $this->params->get('login_redirect_url');
	}
	

	protected function userLogOut() {
		$app=Jfactory::getApplication();
		$app->logout();
		
		$redir = base64_decode(JRequest::getVar('return', '', 'POST', 'BASE64'));
		if (!$redir) $redir=JURI::base( true );
		
		$app->redirect($redir);
	}
	
	protected function logInUser() {
		
		$model =& $this->getModel();
		$app=Jfactory::getApplication();
		
		$redir = base64_decode(JRequest::getVar('return', '', 'POST', 'BASE64'));
		if (!$redir) $redir='index.php?option=com_continued&view=user&layout=profile';
		
		if ($model->loginUser()) {
			$app->redirect($redir);
		} else {
			$app->redirect('index.php?option=com_continued&view=login&layout=login',$model->getError());
		}
	}
}
