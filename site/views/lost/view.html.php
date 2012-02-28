<?php
/**
 * @package		ContinuEd.Site
 * @subpackage	lost
 * @copyright	Copyright (C) 2005 - 2012 Corona Productions. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Lost view class for Users.
 *
 * @package		Continued.Site
 * @subpackage	lost
 * @since		1.20
 */
class ContinuEdViewLost extends JView
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
			case "default": 
				$this->lostInfo();
				break;
			case "infosent": 
				$this->infoSent();
				break;
			case "sendinfo": 
				$this->sendInfo();
				break;
		}
		parent::display($tpl);
	}

	protected function lostInfo() {
		
	}
	

	protected function sendInfo() {
		$model =& $this->getModel();
		$app=Jfactory::getApplication();
		if ($model->sendInfo()) {
			$app->redirect('index.php?option=com_continued&view=lost&layout=infosent',"New Password Code Sent");
		} else {
			$app->redirect('index.php?option=com_continued&view=lost',$model->getError());
		}
	}
	
	protected function infoSent() {
		
		
	}
}
