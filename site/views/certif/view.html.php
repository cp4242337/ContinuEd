<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	certif
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Certificate View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	certif
 * @since		always
 */
class ContinuEdViewCertif extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$courseid = JRequest::getVar( 'course' );
		$model =& $this->getModel('certif');
		
		//Get Users status for course
		$status = $model->statusCheck($courseid);
		
		//Valid Course id, Course Passed
		if ($courseid && $status == "pass") {
			ContinuEdHelper::trackViewed("crt",$courseid,'GetCertificate');
			
			//Get Course Information
			$cinfo=$model->getCourseInfo($courseid);
			
			//Get User Information
			$uinfo=$model->getUserInfo();
			
			//Get Certificate Info
			$cert=$model->getCertif($uinfo->group,$cinfo->course_provider,$courseid,$cinfo->course_defaultcertif);
			
			//Assign Vars
			$this->assignRef('cert',$cert);
			$this->assignRef('cinfo',$cinfo);
			$this->assignRef('uinfo',$uinfo);
			
			//Display
			parent::display($tpl);
		} 
	}
}
?>
