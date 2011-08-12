<?php
/**
 * Hello Controller for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdControllerReport extends ContinuEdController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	function remove()
	{
		$model = $this->getModel('report');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Course Completition Records Could not be deleted' );
		} else {
			$msg = JText::_( 'Course Completition Record(s) Deleted' );
		}

		$link = 'index.php?option=com_continued&view=reports';
		$this->setRedirect($link, $msg);
	}
	function csvmeinit() {
		/*$fc = JRequest::getVar('filter_course');
		 $pf = JRequest::getVar('filter_pf');
		 $cat = JRequest::getVar('filter_cat');
		 $prov = JRequest::getVar('filter_prov');
		 $month = JRequest::getVar('filter_month');
		 $year = JRequest::getVar('filter_year');*/
		$url='index.php?option=com_continued&controller=report&task=csvme&format=raw';
		$this->setRedirect($url);
	}
	function csvme() {
		$filename       = 'ContinuEd_Eval_Report' . '-' . date("Y-m-d");

		JResponse::setHeader('Content-Type', 'application/octet-stream');
		JResponse::setHeader('Content-Disposition', 'attachment; filename="'. $filename . '.csv"');
		$model=$this->getModel('reports');
		$items = $model->getData(true);
		echo "Name,EMail,Completition Time,Degree,License Number,Course,Course Provider,Course Category,Pass or Fail,PreTest %,PostTest %\n";
		for ($i=0, $n=count( $items ); $i < $n; $i++)
		{
			$row = &$items[$i];
			echo $row->fullname.',';
			echo $row->email.',';
			echo $row->ctime.',';
			echo $row->cb_degree.',';
			echo $row->cb_licnum.',';
			echo str_replace(',','',$row->cname).',';
			echo str_replace(',','',$row->pname).',';
			echo $row->catname.',';
			echo $row->cpass.',';
			if ($row->course_haspre) echo $row->cmpl_prescore.","; else echo ",";
			if ($row->haseval) echo $row->cpercent."\n"; else echo "\n";
		}


	}
}
?>
