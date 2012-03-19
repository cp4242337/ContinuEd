<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewCatStat extends JView
{
	function display($tpl = null)
	{
		$user = JRequest::getVar('filter_user');
		$session = JRequest::getVar('filter_session');
		$model = $this->getModel();
		$cat = JRequest::getVar('filter_cat');
		$step = JRequest::getVar('filter_step');
		$startdate = $model->getState('startdate');
		$enddate = $model->getState('enddate');
		JToolBarHelper::title(   JText::_( 'Catagories - Stats' ), 'continued' );
		//JToolBarHelper::custom('csvme','archive','','CSV',false);
		JToolBarHelper::back('List All','index.php?option=com_continued&view=cattstat');
		$tbar =& JToolBar::getInstance('toolbar');
		$tbar->appendButton('Link','archive','Export CSV','index.php?option=com_continued&view=catstat&format=csv" target="_blank');
		
		$items		= & $this->get( 'Data');
		$users		= & $this->get( 'Users');
		$pagination = & $this->get( 'Pagination' );
		$catlist = $model->getCatList();

		$this->assignRef('user',$user);
		$this->assignRef('session',$session);
		$this->assignRef('startdate',$startdate);
		$this->assignRef('enddate',$enddate);
		$this->assignRef('catlist',$catlist);
		$this->assignRef('cat',$cat);
		$this->assignRef('step',$step);
		$this->assignRef('items',$items);
		$this->assignRef('users',$users);
		$this->assignRef('pagination',$pagination);

		parent::display($tpl);
	}
}
