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
class ContinuEdControllerCourseReport extends ContinuEdController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	function csvme() {
		$filename       = 'ContinuEd_Course_Report' . '-' . date("Y-m-d");
		$cid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$model = $this->getModel('coursereport');
		$questions = $model->getQuestions($cid,$area);
		$qopts = $model->getOptions();
		$items = $model->getData(true);
		$db =& JFactory::getDBO();
		JResponse::setHeader('Content-Type', 'application/octet-stream');
		JResponse::setHeader('Content-Disposition', 'attachment; filename="'. $filename . '.csv"');
		//echo 'Course: '.$data->qtext."\n";
		echo "Name,EMail,Degree,Course,Timestamp,Score";
		foreach ($questions as $qu) {
			echo ',#'.$qu->ordering;
		}
		echo "\n";
		for ($i=0, $n=count( $items ); $i < $n; $i++)
		{
			$row = &$items[$i];
			echo $row->fullname.',';
			echo $row->email.',';
			echo $row->cb_degree.',';
			echo str_replace(',','',$row->cname).',';
			echo $row->ctime.',';
			if ($area == 'post') echo $row->cpercent.',';
			else echo $row->cmpl_prescore.',';
			foreach ($questions as $qu) {
				$qnum = 'q'.$qu->id.'ans';
				if ($qu->qtype == 'multi' || $qu->qtype == 'dropdown') {
					echo str_replace(',','',$qopts[$row->$qnum]);
				}
				if ($qu->qtype == 'textbox') { echo str_replace(',','',$row->$qnum); }
				if ($qu->qtype == 'textar') { echo str_replace(',','',$row->$qnum); }
				if ($qu->qtype == 'cbox') { if ($row->$qnum == 'on') echo 'Checked'; else echo 'Unchecked'; }
				if ($qu->qtype == 'mcbox') {
					$answers = explode(' ',$row->$qnum);
					foreach ($answers as $ans) {
						echo str_replace(',','',$qopts[$ans]).' ';
					}
				}

				if ($qu->qtype == 'yesno') { echo str_replace(',','',$row->$qnum); }
				echo ',';
			}


			/*if (qtype == 'multi') {
				if ($this->data->qcat=='assess') {
				if ($row->correct) echo $row->opttxt;
				else echo $row->opttxt;
				}
				else echo $row->opttxt;
				} else if ($qtype == 'mcbox') {
				$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$row->question.' ORDER BY disporder ASC';
				$db->setQuery( $query );
				$qopts = $db->loadAssocList();
				$answers = explode(' ',$row->answer);
				foreach ($qopts as $opts) {
				if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'  '; }
				}
				} else { echo $row->answer; }*/
			echo "\n";
		}


	}
}
?>
