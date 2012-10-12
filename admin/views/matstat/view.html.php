<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewMatStat extends JView
{
	function display($tpl = null)
	{
		$user = JRequest::getVar('filter_user');
		$session = JRequest::getVar('filter_session');
		$cat = JRequest::getVar('filter_cat');
		$course = JRequest::getVar('filter_course');
		$type = JRequest::getVar('filter_type');
		JToolBarHelper::title(   JText::_( 'Material - Stats' ), 'continued' );
		JToolBarHelper::back('List All','index.php?option=com_continued&view=matstat');
		$tbar =& JToolBar::getInstance('toolbar');
		$tbar->appendButton('Link','archive','Export CSV','index.php?option=com_continued&view=matstat&format=csv&filter_cat='.$cat.'&filter_course='.$course.'&filter_user='.$user.'&filter_type='.$type.'" target="_blank');
		$model = $this->getModel();
		$startdate = $model->getState('startdate');
		$enddate = $model->getState('enddate');

		$items		= & $this->get( 'Data');
		$users		= & $this->get( 'Users');
		$pagination = & $this->get( 'Pagination' );
		$catlist = $model->getCatList();
		$courselist = $model->getCourseList($cat);

		$this->assignRef('user',$user);
		$this->assignRef('session',$session);
		$this->assignRef('startdate',$startdate);
		$this->assignRef('enddate',$enddate);
		$this->assignRef('catlist',$catlist);
		$this->assignRef('courselist',$courselist);
		$this->assignRef('cat',$cat);
		$this->assignRef('type',$type);
		$this->assignRef('course',$course);
		$this->assignRef('items',$items);
		$this->assignRef('users',$users);
		$this->assignRef('pagination',$pagination);

		parent::display($tpl);
	}
}
