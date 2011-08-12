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
class ContinuEdControllerAnsQuest extends ContinuEdController
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
		$filename       = 'ContinuEd_Answers_Report' . '-' . date("Y-m-d");
		$qid = JRequest::getVar('question');
		$model = $this->getModel('AnsQuest');
		$data = $model->getQInfo($qid);
		$qtype = $data->qtype;
		$items = $model->getResponses($qid,$qtype);
		$db =& JFactory::getDBO();

		JResponse::setHeader('Content-Type', 'application/octet-stream');
		JResponse::setHeader('Content-Disposition', 'attachment; filename="'. $filename . '.csv"');
		echo 'Question #'.$data->ordering.': '.$data->qtext."\n";
		echo "Name,Answered On,Answer\n";
		for ($i=0, $n=count( $items ); $i < $n; $i++)
		{
			$row = &$items[$i];
			echo $row->firstname.' '.$row->lastname.',';
			echo $row->anstime.',';
			if (qtype == 'multi') {
				if ($this->data->qcat=='assess') {
					if ($row->correct) echo $row->opttxt;
					else echo $row->opttxt;
				}
				else echo $row->opttxt;
			} else if ($qtype == 'mcbox') {
				$query = 'SELECT * FROM #__ce_questions_opts WHERE question = '.$row->question.' ORDER BY ordering ASC';
				$db->setQuery( $query );
				$qopts = $db->loadAssocList();
				$answers = explode(' ',$row->answer);
				foreach ($qopts as $opts) {
					if (in_array($opts['id'],$answers)) { echo $opts['opttxt'].'  '; }
				}
			} else { echo $row->answer; }
			echo "\n";
		}


	}
}
?>
