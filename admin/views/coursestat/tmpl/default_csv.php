<?php defined('_JEXEC') or die('Restricted access');
$path = JPATH_SITE.'/cache/';
$filename = 'ContinuEd_Course_Stats' . '-' . date("Y-m-d").'.csv';
$contents = "";


$contents .= "\"Course\",\"Category\",\"When\",\"Name\",\"Email\",\"Group\",\"What\",\"Session\"\n";
foreach ($this->items as $row)
{
	if ($row->user == 0) $row->username='Guest';


	$contents .= '"'.$row->course_name.'",';
	$contents .= '"'.$row->cat_name.'",';
	$contents .= '"'.$row->tdhit.'",';
	$contents .= '"'.$this->users[$row->user]->name.'",';
	$contents .= '"'.$this->users[$row->user]->email.'",';
	$contents .= '"'.$this->users[$row->user]->usergroup.'",';
	$contents .= '"';
	switch ($row->step) {
		case 'fm': $contents .= 'Front Matter'; break;
		case 'mt': $contents .= 'Material'; break;
		case 'qz': $contents .= 'Evaluation'; break;
		case 'chk': $contents .= 'Check'; break;
		case 'asm': $contents .= 'Assess (Grading)'; break;
		case 'crt': $contents .= 'Certificate'; break;
		case 'vo': $contents .= 'Material - Expired'; break;
		case 'fmp': $contents .= 'FM - Passed'; break;
		case 'mtp': $contents .= 'Material - Passed'; break;
		case 'ans': $contents .= 'View Answers'; break;
		case 'rate': $contents .= 'Rating: '.$row->token; break;
		case 'pre': $contents .= 'PreTest'; break;
		case 'lnk': $contents .= 'Entry Link'; break;
		case 'fme': $contents .= 'Front Matter - Exp'; break;
		case 'fmn': $contents .= 'Front Matter - No CE'; break;
		case 'mtn': $contents .= 'Material - No CE'; break;
	}
	$contents .= '",';
	$contents .= '"'.$row->sessionid."\"\n";
}
JFile::write($path.$filename,$contents);

 $app = JFactory::getApplication();
 $app->redirect('../cache/'.$filename);