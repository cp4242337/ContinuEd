<?php defined('_JEXEC') or die('Restricted access');
$path = JPATH_SITE.'/cache/';
$filename = 'ContinuEd_Material_Stats' . '-' . date("Y-m-d_H-i-s").'.csv';
$contents = "";


$contents .= "\"Material\","\"Course\",\"Category\",\"When\",\"Name\",\"Email\",\"Group\",\"What\",\"Session\",\"IP Address\"\n";
foreach ($this->items as $row)
{
	if ($row->user == 0) $row->username='Guest';


	$contents .= '"'.$row->mat_title.'",';
	$contents .= '"'.$row->course_name.'",';
	$contents .= '"'.$row->cat_name.'",';
	$contents .= '"'.$row->mt_time.'",';
	if ($row->mt_user == 0) $contents .= '"Guest",';
	else $contents .= '"'.$this->users[$row->mt_user]->name.'",';
	if ($row->mt_user == 0) $contents .= '"Guest",';
	else $contents .= '"'.$this->users[$row->mt_user]->email.'",';
	if ($row->mt_user == 0) $contents .= '"Guest",';
	else $contents .= '"'.$this->users[$row->mt_user]->usergroup.'",';
	$contents .= '"';
	switch ($row->mt_type) {
		case 'view': $contents .= 'First View'; break;
		case 'review': $contents .= 'Review'; break;
		case 'nocredit': $contents .= 'No Credit'; break;
	}
	$contents .= '",';
	$contents .= '"'.$row->mt_session."\",";
	$contents .= '"'.$row->mt_ipaddr."\"\n";
}
JFile::write($path.$filename,$contents);

 $app = JFactory::getApplication();
 $app->redirect('../cache/'.$filename);