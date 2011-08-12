<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class ContinuEdControllerCatStatE extends ContinuEdController
{
	function __construct()
	{
		parent::__construct();

	}

	function csvme() {
		$filename       = 'ContinuEd_Category_Stat_Report' . '-' . date("Y-m-d");
		JResponse::setHeader('Content-Type', 'application/octet-stream');
		JResponse::setHeader('Content-Disposition', 'attachment; filename="'. $filename . '.csv"');
		$model = $this->getModel('catstat');
		$items = $model->getDataCSV();

		echo "\"Category\",\"When\",\"Who\",\"What\",\"Session\"\n";
		for ($i=0, $n=count( $items ); $i < $n; $i++)
		{
			$row = &$items[$i];
			if ($row->user == 0) $row->username='Guest';


			echo '"'.$row->catname.'",';
			echo '"'.$row->tdhit.'",';
			echo '"'.$row->username.'",';
			echo '"';
			switch ($row->viewed) {
				case 'fm': echo 'Front Matter'; break;
				case 'menu': echo 'Menu'; break;
				case 'det': echo 'Details'; break;
			}
			echo '",';
			echo '"'.$row->sessionid."\"\n";
		}
	}

}
?>

