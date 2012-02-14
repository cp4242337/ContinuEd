<?php
/**
 * @version		$Id: view.html.php 2012-01-11 $
 * @package		ContinuEd.Site
 * @subpackage	user
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Certificate View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	user
 * @since		always
 */
class ContinuEdViewUser extends JView
{
	public function display($tpl = null)
	{
		$cecfg = ContinuEdHelper::getConfig();
		$layout = $this->getLayout();
		
		switch($layout) {
			case "cerecords": 
				$this->userCERecords();
				break;
			case "profile": 
				$this->userProfile();
				break;
			case "proedit": 
				$this->userEdit();
				break;
			case "saveuser": 
				$this->saveUser();
				break;
		}
		parent::display($tpl);
	}
	
	protected function userCERecords() {
		$model =& $this->getModel();
		$print = JRequest::getVar('print');
		$user =& JFactory::getUser();
		$cert=NULL;
		$userid = $user->id;
		if ($userid != 0) {
			$catalog=$model->getCERecords($userid);
			$this->assignRef('print',$print);
			$this->assignRef('catalog',$catalog);
			$this->assignRef('userid',$userid);
		}
		
	}
	
	protected function userProfile() {
		$model =& $this->getModel();
		$print = JRequest::getVar('print');
		$user =& JFactory::getUser();
		$userid = $user->id;
		if ($userid != 0) {
			$userinfo=ContinuEdHelper::getUserInfo();
			$userfields=$model->getUserFields($userinfo->userGroupID);
			$this->assignRef('userinfo',$userinfo);
			$this->assignRef('userfields',$userfields);
		}
		
	}
	
	protected function userEdit() {
		$model =& $this->getModel();
		$print = JRequest::getVar('print');
		$user =& JFactory::getUser();
		$userid = $user->id;
		if ($userid != 0) {
			$userinfo=ContinuEdHelper::getUserInfo(true);
			$userfields=$model->getUserFields($userinfo->userGroupID,false,true);
			$this->assignRef('userinfo',$userinfo);
			$this->assignRef('userfields',$userfields);
		}
		
	}
	
	protected function saveUser() {
		$model =& $this->getModel();
		$print = JRequest::getVar('print');
		$user =& JFactory::getUser();
		$userid = $user->id;
		if ($userid != 0) {
			if (!$model->save()) {
				$userinfo=ContinuEdHelper::getUserInfo(true);
				$userfields=$model->getUserFields($userinfo->userGroupID,false,true);
				$this->assignRef('userinfo',$userinfo);
				$this->assignRef('userfields',$userfields);
				$this->setLayout('proedit');
			} else {
				$app=Jfactory::getApplication();
				$app->redirect('index.php?option=com_continued&view=user&layout=profile',"Profile Saved");
			}
		}
		
	}
}
?>
