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
	var $pid = null;
	
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		$app=Jfactory::getApplication();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$user =& JFactory::getUser();
		$this->cinfo = $model->getCourseInfo($courseid);

		
		switch ($layout) {
			case "ppsubpay":
				$this->ppSubmitPayment();
				break;
			case "ppconfirm":
				$this->ppConfirmPayment();
				break;
			case "ppverify":
				$this->ppVerifyPayment();
				break;
			case "ppcancel":
				$this->ppCancelPayment();
				break;
			case "redeem":
				$this->redeemCode();
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
	
	function ppSubmitPayment() {	
		$user =& JFactory::getUser();	
		$app=Jfactory::getApplication();
		$cecfg = ContinuEdHelper::getConfig();
		if (!$user->id ) {
			//take user to fm if not logged in
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id);
		}
		include_once 'components/com_continued/helpers/paypal.php';
		$paypal = new PayPalAPI($cecfg->paypal_mode,$cecfg->paypal_username,$cecfg->paypal_password,$cecfg->paypal_signature);
		if (!$paypal->submitPayment($this->cinfo)) {
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id,$paypal->error,'error');
		}
	}
	
	function ppConfirmPayment() {	
		$user =& JFactory::getUser();	
		$app=Jfactory::getApplication();
		$cecfg = ContinuEdHelper::getConfig();
		$this->pid = JRequest::getVar( 'purchaseid' );
		$token = JRequest::getVar( 'token' );
		if (!$user->id ) {
			//take user to fm if not logged in
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id);
		}
		include_once 'components/com_continued/helpers/paypal.php';
		$paypal = new PayPalAPI($cecfg->paypal_mode,$cecfg->paypal_username,$cecfg->paypal_password,$cecfg->paypal_signature);
		if (!$paypal->confirmPayment($this->cinfo,$this->pid,$token)) {
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id,$paypal->error,'error');
		}
	}
	
	function ppVerifyPayment() {	
		$user =& JFactory::getUser();	
		$app=Jfactory::getApplication();
		$cecfg = ContinuEdHelper::getConfig();
		$this->pid = JRequest::getVar( 'purchaseid' );
		if (!$user->id ) {
			//take user to fm if not logged in
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id);
		}
		include_once 'components/com_continued/helpers/paypal.php';
		$paypal = new PayPalAPI($cecfg->paypal_mode,$cecfg->paypal_username,$cecfg->paypal_password,$cecfg->paypal_signature);
		if (!$paypal->verifyPayment($this->cinfo,$this->pid)) {
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id,$paypal->error,'error');
		} else {
			
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id,'Thank you, your payment has been completed.');
			
		}
	}
	
	function ppCancelPayment() {	
		$user =& JFactory::getUser();	
		$app=Jfactory::getApplication();
		$cecfg = ContinuEdHelper::getConfig();
		$this->pid = JRequest::getVar( 'purchaseid' );
		if (!$user->id ) {
			//take user to fm if not logged in
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id);
		}
		include_once 'components/com_continued/helpers/paypal.php';
		$paypal = new PayPalAPI($cecfg->paypal_mode,$cecfg->paypal_username,$cecfg->paypal_password,$cecfg->paypal_signature);
		$paypal->cancelPayment($this->cinfo,$this->pid);
		$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id,'Canceled');
	}
	
	
	
	function redeemCode() {
		$user =& JFactory::getUser();
		$app=Jfactory::getApplication();
		$cecfg = ContinuEdHelper::getConfig();
		$model =& $this->getModel();
		$code = JRequest::getVar( 'redeemcode' );
		if (!$user->id ) {
			//take user to fm if not logged in
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id);
		}
		if (!$model->redeemCode($this->cinfo,$code)) {
			$app->redirect('index.php?option=com_continued&view=purchase&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id,$model->codeError,'error');
		} else {
				
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$this->cinfo->course_id,'Thank you, your code has been accepted.');
				
		}
	}
}
?>
