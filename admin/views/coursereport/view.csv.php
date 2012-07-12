<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewCourseReport extends JView
{
	function display($tpl = 'csv')
	{
		$model = $this->getModel('coursereport');
		$cid = $model->getState('course');
		$cat = $model->getState('cat');
		$usergroup = $model->getState('usergroup');
		$qgroup = $model->getState('qgroup');
		$qarea = $model->getState('qarea');
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
		
		$items		= & $model->getData(true);
		$pagination = & $this->get( 'Pagination' );
		$courselist = & $this->get( 'Courses' );
		$catlist = & $this->get( 'Cats' );
		$userlist = & $this->get( 'Users' );
		$grouplist = & $this->get( 'UserGroups' );
		$qgrouplist = & $this->get( 'QGroups' );
		$startdate = $model->getState('startdate');
		$enddate = $model->getState('enddate');
		$pharmids=$model->getPharmID();
		$pharmdobs=$model->getPharmDOB();
		$catids=$model->getCatbyId();
		$pf=$model->getState('pf');
		$type=$model->getState('type');

		$this->assignRef('startdate',$startdate);
		$this->assignRef('enddate',$enddate);
		$this->assignRef('qarea',$qarea);
		$this->assignRef('pf',$pf);
		$this->assignRef('type',$type);
		$this->assignRef('cat',$cat);
		$this->assignRef('course',$cid);
		$this->assignRef('grouplist',$grouplist);
		$this->assignRef('qgrouplist',$qgrouplist);
		$this->assignRef('courselist',$courselist);
		$this->assignRef('catlist',$catlist);
		$this->assignRef('userlist',$userlist);
		$this->assignRef('usergroup',$usergroup);
		$this->assignRef('pharmids',$pharmids);
		$this->assignRef('pharmdobs',$pharmdobs);
		$this->assignRef('catids',$catids);
		$this->assignRef('qgroup',$qgroup);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		parent::display($tpl);
	}
}
