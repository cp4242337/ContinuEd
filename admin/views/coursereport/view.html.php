<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewCourseReport extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'ContinuEd Course Report' ), 'generic.png' );
		$model = $this->getModel('coursereport');
		$cid = $model->getState('course');
		$cat = $model->getState('cat');
		$usergroup = $model->getState('usergroup');
		$tbar =& JToolBar::getInstance('toolbar');
		$tbar->appendButton('Link','archive','Export CSV','index.php?option=com_continued&controller=coursereport&task=csvme&course='.$cid.'&cat='.$cat.'&format=raw');
		if ($cid) JToolBarHelper::back('Courses','index.php?option=com_continued&view=courses');
		if ($cid) {
			$qpre = $model->getQuestions($cid,'pre');
			$qpost = $model->getQuestions($cid,'post');
			$qinter = $model->getQuestions($cid,'inter');
			if ($qpre || $qinter || $qpost) $options = $model->getOptions();
			$this->assignRef('opts',$options);
			$this->assignRef('qpre',$qpre);
			$this->assignRef('qinter',$qinter);
			$this->assignRef('qpost',$qpost);
		}
		
		$items		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );
		$courselist = & $this->get( 'Courses' );
		$catlist = & $this->get( 'Cats' );
		$userlist = & $this->get( 'Users' );
		$grouplist = & $this->get( 'UserGroups' );
		$startdate = $model->getState('startdate');
		$enddate = $model->getState('enddate');
		$pf=$model->getState('pf');
		$type=$model->getState('type');

		$this->assignRef('startdate',$startdate);
		$this->assignRef('enddate',$enddate);
		$this->assignRef('area',$area);
		$this->assignRef('pf',$pf);
		$this->assignRef('type',$type);
		$this->assignRef('cat',$cat);
		$this->assignRef('course',$cid);
		$this->assignRef('grouplist',$grouplist);
		$this->assignRef('courselist',$courselist);
		$this->assignRef('catlist',$catlist);
		$this->assignRef('userlist',$userlist);
		$this->assignRef('usergroup',$usergroup);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		parent::display($tpl);
	}
}
