<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


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
		$model = $this->getModel('coursereport');
		$cid = $model->getState('course');
		if ($cid) {
			$qpre = $model->getQuestions($cid,'pre');
			$qpost = $model->getQuestions($cid,'post');
			$qinter = $model->getQuestions($cid,'inter');
			$qopts = $model->getOptions();
		}
		$userlist = & $this->get( 'Users' );
		$items = $model->getData(true);
		$db =& JFactory::getDBO();
		if ($cid) $filename = 'ContinuEd_Course_Report' . '-' . date("Y-m-d");
		else $filename = 'ContinuEd_Report' . '-' . date("Y-m-d");
		JResponse::setHeader('Content-Type', 'application/octet-stream');
		JResponse::setHeader('Content-Disposition', 'attachment; filename="'. $filename . '.csv"');
		//echo 'Course: '.$data->qtext."\n";
		echo "Name,EMail,Group,SessionID,IP Address,Course,Cat,Status,Type,Last Step,Start,End,Pre Score,Post Score";
		if ($cid) {
			//Pretest
			foreach ($qpre as $qu) {
				echo ',PRE#'.$qu->ordering;
			}
			//Inter
			foreach ($qinter as $qu) {
				echo ',INTER#'.$qu->ordering;
			}
			//Posttest
			foreach ($qpost as $qu) {
				echo ',POST#'.$qu->ordering;
			}
		}
		echo "\n";
		for ($i=0, $n=count( $items ); $i < $n; $i++)
		{
			$row = &$items[$i];
			echo $this->userlist[$row->rec_user]->name.',';
			echo $this->userlist[$row->rec_user]->email.',';
			echo $this->userlist[$row->rec_user]->usergroup.',';
			echo $row->rec_session.',';
			echo $row->rec_ipaddr.',';
			echo str_replace(',','',$row->course_name).',';
			echo str_replace(',','',$row->cat_name).',';
			switch ($row->rec_pass) {
				case 'pass': echo 'Pass'; break;
				case 'fail': echo 'Fail'; break;
				case 'incomplete': echo 'Incomplete'; break;
				case 'audit': echo 'Audit'; break;
				case 'complete': echo 'Completed'; break;
			}
			echo ',';
		 	switch ($row->rec_type) {
				case 'nonce': echo 'Non-CE'; break;
				case 'ce': echo 'CE'; break;
				case 'review': echo 'Review'; break;
				case 'expired': echo 'Expired'; break;
				case 'viewed'	: echo 'Viewed'; break;
				
			}
			echo ",";
			switch ($row->rec_laststep) {
				case 'fm': echo 'Front Matter'; break;
				case 'mt': echo 'Material'; break;
				case 'qz': echo 'Evaluation'; break;
				case 'chk': echo 'Check'; break;
				case 'asm': echo 'Assess (Grading)'; break;
				case 'crt': echo 'Certificate'; break;
				case 'vo': echo 'Material - Expired'; break;
				case 'fmp': echo 'FM - Passed'; break;
				case 'mtp': echo 'Material - Passed'; break;
				case 'ans': echo 'View Answers'; break;
				case 'pre': echo 'PreTest'; break;
				case 'lnk': echo 'Entry Link'; break;
				case 'fme': echo 'Front Matter - Exp'; break;
			}
			echo ",";
			echo $row->rec_start.',';
			echo $row->rec_end.',';
			if ($row->rec_prescore == -1 || !$row->rec_user || $row->rec_type != 'ce') echo 'N/A'.','; else echo $row->rec_prescore.',';
			if ($row->rec_postscore == -1 || !$row->rec_user || $row->rec_type != 'ce') echo 'N/A'.','; else echo $row->rec_postscore.',';
			//Pre
			foreach ($qpre as $qu) {
				$qnum = 'q'.$qu->q_id.'ans';
				if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') {
					echo str_replace(',','',$qopts[$row->$qnum]);
				}
				if ($qu->q_type == 'textbox') { echo str_replace(',','',$row->$qnum); }
				if ($qu->q_type == 'textar') { echo str_replace(',','',$row->$qnum); }
				if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') echo 'Checked'; else echo 'Unchecked'; }
				if ($qu->q_type == 'mcbox') {
					$answers = explode(' ',$row->$qnum);
					foreach ($answers as $ans) {
						echo str_replace(',','',$qopts[$ans]).' ';
					}
				}

				if ($qu->q_type == 'yesno') { echo str_replace(',','',$row->$qnum); }
				echo ',';
			}
			//Inter
			foreach ($qinter as $qu) {
				$qnum = 'q'.$qu->q_id.'ans';
				if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') {
					echo str_replace(',','',$qopts[$row->$qnum]);
				}
				if ($qu->q_type == 'textbox') { echo str_replace(',','',$row->$qnum); }
				if ($qu->q_type == 'textar') { echo str_replace(',','',$row->$qnum); }
				if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') echo 'Checked'; else echo 'Unchecked'; }
				if ($qu->q_type == 'mcbox') {
					$answers = explode(' ',$row->$qnum);
					foreach ($answers as $ans) {
						echo str_replace(',','',$qopts[$ans]).' ';
					}
				}

				if ($qu->q_type == 'yesno') { echo str_replace(',','',$row->$qnum); }
				echo ',';
			}
			//post
			foreach ($qpost as $qu) {
				$qnum = 'q'.$qu->q_id.'ans';
				if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') {
					echo str_replace(',','',$qopts[$row->$qnum]);
				}
				if ($qu->q_type == 'textbox') { echo str_replace(',','',$row->$qnum); }
				if ($qu->q_type == 'textar') { echo str_replace(',','',$row->$qnum); }
				if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') echo 'Checked'; else echo 'Unchecked'; }
				if ($qu->q_type == 'mcbox') {
					$answers = explode(' ',$row->$qnum);
					foreach ($answers as $ans) {
						echo str_replace(',','',$qopts[$ans]).' ';
					}
				}

				if ($qu->q_type == 'yesno') { echo str_replace(',','',$row->$qnum); }
				echo ',';
			}
			echo "\n";
		}


	}
}
?>
