<?php
// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) . '/../..' );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once('helpers'.DS.'continued.php');

$mainframe =& JFactory::getApplication('site');
$db  =& JFactory::getDBO();
$user = &JFactory::getUser();

$courseid = JRequest::getVar('course');
$qid  = JRequest::getVar('question');
$qans  = JRequest::getVar('qans');
$token  = JRequest::getVar('token');
$userid = $user->id;
$session =& JFactory::getSession();
$cecfg = ContinuEdHelper::getConfig();

//Get Users status for course
$status = statusCheck($courseid);

//Valid Course id, Course Passed
if ($courseid && $status == "pass") {
	ContinuEdHelper::trackViewed("crt",$courseid,'GetCertificate');
		
	//Get Course Information
	$cinfo=getCourseInfo($courseid);
		
	//Get User Information
	$uinfo=getUserInfo($userid);
		
	//Get Certificate Info
	$cert=getCertif($uinfo->group,$cinfo->course_provider,$courseid,$cinfo->course_defaultcertif);
	
	$username = $uinfo->fname.' '.$uinfo->lname;
	$certhtml = $cert->ctmpl_content;
	$certhtml = str_replace('{Title}',$cinfo->course_certifname,$certhtml);
	$certhtml = str_replace('{Faculty}',$cinfo->course_faculty,$certhtml);
	$certhtml = str_replace('{Credits}',$cinfo->course_credits,$certhtml);
	$certhtml = str_replace('{ADate}',date("F d, Y", strtotime($cinfo->course_actdate)),$certhtml);
	$certhtml = str_replace('{IDate}',date("F d, Y"),$certhtml);
	$certhtml = str_replace('{Name}',$username,$certhtml);
	//$certhtml = str_replace('{Degree}',$uinfo->cb_degree,$certhtml);
	//$certhtml = str_replace('{Address1}',$uinfo->cb_address,$certhtml);
	//$certhtml = str_replace('{City}',$uinfo->cb_city,$certhtml);
	//$certhtml = str_replace('{State}',$uinfo->cb_state,$certhtml);
	//$certhtml = str_replace('{Zip}',$uinfo->cb_zipcode,$certhtml);
	$certhtml = str_replace('{LicNum}',$uinfo->lic_num,$certhtml);
	$certhtml = str_replace('{PNNum}',$cinfo->course_cneprognum,$certhtml);
	$certhtml = str_replace('{PPNum}',$cinfo->course_cpeprognum,$certhtml);
	$certhtml = str_replace('{LType}',$cinfo->course_learntype,$certhtml);
	
	require_once('lib/tcpdf/config/lang/eng.php');
	require_once('lib/tcpdf/tcpdf.php');
	
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('');
	$pdf->SetTitle('');
	$pdf->SetSubject('');
	$pdf->SetKeywords('');
	
	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(0);
	$pdf->SetFooterMargin(0);

	// remove default footer
	$pdf->setPrintFooter(false);
	$pdf->setPrintHeader(false);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont('dejavusans', '', 10);
	
	// add a page
	$pdf->AddPage();
	
	// output the HTML content
	$pdf->writeHTML($certhtml, true, false, true, false, '');
	
	$pdf->Output($cinfo->course_certifname.' Certificate '.date("Y-m-d").'.pdf', 'D');
	
//	echo $certhtml;
}
	
	
	function getCourseInfo($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_courses WHERE course_id = '.$courseid;
		$db->setQuery( $query );
		$fmtext = $db->loadObject();
		return $fmtext;
	}

	/**
	 * Course list user status check.
	 *
	 * @param int $course Course id
	 *
	 * @return string user status for course.
	 *
	 * @since 1.20
	 */
	function statusCheck($course) {
		$cmpllist = ContinuEdHelper::completedList();
		$status=$cmpllist[$course]->rec_pass;
		return $status;
	}

	/**
	 * User Info
	 *
	 * @return object of user data.
	 *
	 * @since 1.20
	 */
	function getUserInfo($userid=0) {
		if (!$userid) {
			$user =& JFactory::getUser();
			$userid = $user->id;
		}
		$db =& JFactory::getDBO();
		$query = 'SELECT userg_group FROM #__ce_usergroup WHERE userg_user="'.$userid.'"';
		$db->setQuery($query); $groupid=$db->loadResult();
		$user->group = $groupid;
		$qd = 'SELECT f.uf_sname,f.uf_type,u.usr_data FROM #__ce_uguf as g';
		$qd.= ' RIGHT JOIN #__ce_ufields as f ON g.uguf_field = f.uf_id';
		$qd.= ' RIGHT JOIN #__ce_users as u ON u.usr_field = f.uf_id && usr_user = '.$userid;
		$qd.= ' WHERE g.uguf_group='.$groupid;
		$db->setQuery( $qd );
		$udata = $db->loadObjectList();
		foreach ($udata as $u) {
			$fn=$u->uf_sname;
			if ($u->uf_type == 'multi' || $u->uf_type == 'dropdown' || $u->uf_type == 'mcbox') {
				$ansarr=explode(" ",$u->usr_data);
				$q = 'SELECT opt_text FROM #__ce_ufields_opts WHERE opt_id IN('.implode(",",$ansarr).')';
				$db->setQuery($q);
				$user->$fn = implode(", ",$db->loadResultArray());
			} else {
				$user->$fn=$u->usr_data;
			}
		}

		return $user;
	}

	function getCertif($group,$provider,$courseid,$defcert)
	{
		$db =& JFactory::getDBO();
		//determine which certif
		$q='SELECT gc_cert FROM #__ce_groupcerts WHERE gc_group = "'.$group.'"';
		$db->setQuery($q);
		$usercert = $db->loadResult();
		if (!checkDegree($courseid,$usercert)) $usercert = $defcert;
		//get that certificate
		$query = 'SELECT * FROM #__ce_certiftempl WHERE ctmpl_cert = '.$usercert.' && ctmpl_prov = '.$provider;
		$db->setQuery( $query );
		$fmtext = $db->loadObject();
		return $fmtext;
	}

	function checkDegree($courseid,$usercert) {
		$coursecerts=getCourseGroups($courseid);
		$cando = in_array($usercert,$coursecerts);
		return $cando;
	}

	function getCourseGroups($courseid)
	{
		$db =& JFactory::getDBO();
		$q='SELECT cd_cert FROM #__ce_coursecerts WHERE cd_course = "'.$courseid.'"';
		$db->setQuery($q);
		$cn = $db->loadResultArray();
		return $cn;
	}


