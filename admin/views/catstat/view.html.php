<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewCatStat extends JView
{
	function display($tpl = null)
	{
		$user = JRequest::getVar('filter_user');
		$session = JRequest::getVar('filter_session');
		$month = JRequest::getVar('filter_month');
		$year = JRequest::getVar('filter_year');
		$cat = JRequest::getVar('filter_cat');
		$step = JRequest::getVar('filter_step');
		JToolBarHelper::title(   JText::_( 'Catagories - Stats' ), 'continued' );
		//JToolBarHelper::custom('csvme','archive','','CSV',false);
		JToolBarHelper::back('List All','index.php?option=com_continued&view=cattstat');
		$model = $this->getModel();

		$items		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );
		$catlist = $model->getCatList();

		$this->assignRef('user',$user);
		$this->assignRef('session',$session);
		$this->assignRef('month',$month);
		$this->assignRef('year',$year);
		$this->assignRef('catlist',$catlist);
		$this->assignRef('cat',$cat);
		$this->assignRef('step',$step);
		$this->assignRef('items',$items);
		$this->assignRef('pagination',$pagination);

		parent::display($tpl);
	}
}
