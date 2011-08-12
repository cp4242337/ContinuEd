<?php // no direct access
defined('_JEXEC') or die('Restricted access');
global $cecfg;
$user =& JFactory::getUser();
$username = $user->name;
$certhtml = $this->cert['ctmpl_content'];
$certhtml = str_replace('{Title}',$this->cinfo['certifname'],$certhtml);
$certhtml = str_replace('{Faculty}',$this->cinfo['faculty'],$certhtml);
$certhtml = str_replace('{Credits}',$this->cinfo['credits'],$certhtml);
$certhtml = str_replace('{IDate}',date("F d, Y",strtotime($this->pinfo['ctime'])),$certhtml);
$certhtml = str_replace('{ADate}',date("F d, Y", strtotime($this->cinfo['actdate'])),$certhtml);
$certhtml = str_replace('{Name}',$username,$certhtml);
$certhtml = str_replace('{Degree}',$this->uinfo['cb_degree'],$certhtml);
$certhtml = str_replace('{Address1}',$this->uinfo['cb_address'],$certhtml);
$certhtml = str_replace('{City}',$this->uinfo['cb_city'],$certhtml);
$certhtml = str_replace('{State}',$this->uinfo['cb_state'],$certhtml);
$certhtml = str_replace('{Zip}',$this->uinfo['cb_zipcode'],$certhtml);
$certhtml = str_replace('{LicNum}',$this->uinfo['cb_licnum'],$certhtml);
$certhtml = str_replace('{PNNum}',$this->cinfo['cneprognum'],$certhtml);
$certhtml = str_replace('{PPNum}',$this->cinfo['cpeprognum'],$certhtml);
$certhtml = str_replace('{LType}',$this->cinfo['course_learntype'],$certhtml);
echo $certhtml;
if (!$certhtml) echo $cecfg['NODEG_MSG'];
?>