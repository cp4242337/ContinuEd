<?php defined('_JEXEC') or die('Restricted access');
$path = JPATH_SITE.'/cache/';
$filename = 'ContinuEd_Category_Stats' . '-' . date("Y-m-d").'.csv';
$contents = "";


$contents .=  "\"Category\",\"When\",\"Name\",\"Email\",\"Group\",\"What\",\"Session\"\n";
foreach ($this->items as $row)
{
	if ($row->user == 0) $row->username='Guest';


	$contents .=  '"'.$row->cat_name.'",';
	$contents .=  '"'.$row->tdhit.'",';
	$contents .=  '"'.$this->users[$row->user]->name.'",';
	$contents .=  '"'.$this->users[$row->user]->email.'",';
	$contents .=  '"'.$this->users[$row->user]->usergroup.'",';
	$contents .=  '"';
	switch ($row->viewed) {
		case 'fm': $contents .=  'Front Matter'; break;
		case 'menu': $contents .=  'Menu'; break;
		case 'det': $contents .=  'Details'; break;
	}
	$contents .=  '",';
	$contents .=  '"'.$row->sessionid."\"\n";
}
JFile::write($path.$filename,$contents);

 $app = JFactory::getApplication();
 $app->redirect('../cache/'.$filename);