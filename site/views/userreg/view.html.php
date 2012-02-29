<?php
/**
 * @version		$Id: view.html.php 2012-01-11 $
 * @package		ContinuEd.Site
 * @subpackage	userreg
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Registration View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	user
 * @since		always
 */
class ContinuEdViewUserReg extends JView
{
	public function display($tpl = null)
	{
		$cecfg = ContinuEdHelper::getConfig();
		$layout = $this->getLayout();
		
		switch($layout) {
			case "default": 
				$this->pickGroup();
				break;
			case "groupuser": 
				$this->setGroup();
				break;
			case "regform": 
				$this->showForm();
				break;
			case "reguser": 
				$this->addUser();
				break;
		}
		parent::display($tpl);
	}
	
	protected function pickGroup() {
		$model =& $this->getModel();
		$groups=$model->getUserGroups();
		$this->assignRef('groups',$groups);
	}
	
	protected function setGroup() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app=Jfactory::getApplication();
		$app->setUserState('continued.userreg.groupid',JRequest::getInt('groupid')); 
		$app->redirect('index.php?option=com_continued&view=userreg&layout=regform');
		
	}
	
	protected function showForm() {
		$model =& $this->getModel();
		$app=Jfactory::getApplication();
		$groupid = $app->getUserState('continued.userreg.groupid');
		if ($groupid) {
			$groupinfo = $model->getUserGroups($groupid);
			$userfields=$model->getUserFields($groupid);
			$this->assignRef('groupinfo',$groupinfo);
			$this->assignRef('groupid',$groupid);
			$this->assignRef('userfields',$userfields);
		} else {
			$app->redirect('index.php?option=com_continued&view=userreg');
		}
	}
	
	protected function addUser() {
		$model =& $this->getModel();
		$app=Jfactory::getApplication();
		
		$groupid = JRequest::getInt('jform[userGroupID]');
		if (!$model->save()) {
			$app->redirect('index.php?option=com_continued&view=userreg&layout=regform&groupid='.$groupid,$model->getError(),'error');
		} else {
			$app->redirect('index.php?option=com_continued&view=user&layout=profile');
		}		
	}
}
?>
