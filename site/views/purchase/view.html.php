<?php
/**
 * @version		$Id: view.html.php 2012-07-24 $
 * @package		ContinuEd.Site
 * @subpackage	purchase
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Purchase Page View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	purchase
 * @since		always
 */
class ContinuEdViewPurchase extends JView
{
	var $cinfo = null;
	
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		$app=Jfactory::getApplication();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$user =& JFactory::getUser();
		
		// Logged in
		if ($user->id ) {
			$this->cinfo = $model->getCourseInfo($courseid);
		} else {
			//take user to fm if not logged in
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		}
		
		switch ($layout) {
			case "subpay":
				$this->submitPayment();
				break;
			case 'default':
			default:
				$this->payInfo();
				break;
		}
		
		parent::display($tpl);

	}
	
	function payInfo() {
		
	}
	
	function submitPayment() {
		$cecfg = ContinuEdHelper::getConfig();
		include_once 'components/com_continued/helpers/paypal.php';
		$paypal = new PayPalAPI($cecfg->paypal_mode,$cecfg->paypal_username,$cecfg->paypal_password,$cecfg->paypal_signature);
		if (!$paypal->submitPayment($this->cinfo)) {
			$app->redirect('index.php?option=com_continued&view=purchase&layout=error&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$cinfo->course_id);
		}
	}
}
?>
