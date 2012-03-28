<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
$user =& JFactory::getUser();
$username = $user->name;
$certhtml = $this->cert->ctmpl_content;
$certhtml = str_replace('{Title}',$this->cinfo->course_certifname,$certhtml);
$certhtml = str_replace('{Faculty}',$this->cinfo->course_faculty,$certhtml);
$certhtml = str_replace('{Credits}',$this->cinfo->course_credits,$certhtml);
$certhtml = str_replace('{ADate}',date("F d, Y", strtotime($this->cinfo->course_actdate)),$certhtml);
$certhtml = str_replace('{IDate}',date("F d, Y"),$certhtml);
$certhtml = str_replace('{Name}',$username,$certhtml);
//$certhtml = str_replace('{Degree}',$this->uinfo->cb_degree,$certhtml);
//$certhtml = str_replace('{Address1}',$this->uinfo->cb_address,$certhtml);
//$certhtml = str_replace('{City}',$this->uinfo->cb_city,$certhtml);
//$certhtml = str_replace('{State}',$this->uinfo->cb_state,$certhtml);
//$certhtml = str_replace('{Zip}',$this->uinfo->cb_zipcode,$certhtml);
$certhtml = str_replace('{LicNum}',$this->uinfo->licnum,$certhtml);
$certhtml = str_replace('{PNNum}',$this->cinfo->course_cneprognum,$certhtml);
$certhtml = str_replace('{PPNum}',$this->cinfo->course_cpeprognum,$certhtml);
$certhtml = str_replace('{LType}',$this->cinfo->course_learntype,$certhtml);
echo $certhtml;
if (!$certhtml) echo $cecfg->NODEG_MSG;
?>