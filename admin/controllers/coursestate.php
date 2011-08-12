<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class ContinuEdControllerCourseStatE extends ContinuEdController
{
	function __construct()
	{
		parent::__construct();

	}

	function csvme() {
		$filename       = 'ContinuEd_Course_Stat_Report' . '-' . date("Y-m-d");
		JResponse::setHeader('Content-Type', 'application/octet-stream');
		JResponse::setHeader('Content-Disposition', 'attachment; filename="'. $filename . '.csv"');
		$model = $this->getModel('coursestat');
		$items = $model->getDataCSV();

		echo "\"Course\",\"Category\",\"When\",\"Who\",\"What\",\"Session\"\n";
		for ($i=0, $n=count( $items ); $i < $n; $i++)
		{
			$row = &$items[$i];
			if ($row->user == 0) $row->username='Guest';


			echo '"'.$row->cname.'",';
			echo '"'.$row->catname.'",';
			echo '"'.$row->tdhit.'",';
			echo '"'.$row->username.'",';
			echo '"';
			switch ($row->step) {
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
				case 'rate': echo 'Rating: '.$row->token; break;
				case 'pre': echo 'PreTest'; break;
				case 'lnk': echo 'Entry Link'; break;
				case 'fme': echo 'Front Matter - Exp'; break;
			}
			echo '",';
			echo '"'.$row->sessionid."\"\n";
		}
	}

}
?>
