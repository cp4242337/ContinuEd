<?php
/**
 * @package		ContinuEd.Site
 * @subpackage	lost
 * @copyright	Copyright (C) 2012 Corona Productions, Inc. & Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');
/**
 * Rest model class for Users.
 *
 * @package		ContinuEd.Site
 * @subpackage	lost
 * @since		1.20
 */
class ContinuEdModelLost extends JModel
{
	function sendInfo() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$cecfg = ContinuEdHelper::getConfig();
		$db=JFactory::getDBO();
		
		$email = JRequest::getVar('lost_email',0);
		if (!$email) { $this->setError("Email Address is required"); return false; }
		
		$q='SELECT id FROM #__users WHERE email = "'.$email.'"';
		$db->setQuery($q); $userid=$db->loadResult();
		
		// Check for an error.
		if ($db->getErrorNum()) { $this->setError($db->getErrorMsg(), 500); return false; }
		
		// Check for a user.
		if (empty($userid)) { $this->setError('Invalid Email Address'); return false; }
		
		// Get the user object.
		$user = JUser::getInstance($userid);
		
		// Make sure the user isn't blocked.
		if ($user->block) { $this->setError('User Account Blocked'); return false; }
		
		// Make sure the user isn't a Super Admin.
		if ($user->authorise('core.admin')) { $this->setError('Not for Admins'); return false; }
		
		$newpass=$this->gen_uuid(8);
		
		$newdetails['password'] = $newpass;
		$newdetails['password2'] = $newpass;
		$user->bind($newdetails);
		if (!$user->save(true)) {
			$this->setError($user->getError());
			return false;
		}
		
		//Setup Welcome email
		$groupinfo = ContinuEdHelper::getUserGroup($userid);
		$emailtoaddress = $user->email;
		$emailtoname = $user->name;
		$emailfromaddress = $cecfg->FROM_EMAIL;
		$emailfromname = $cecfg->FROM_NAME;
		$emailsubject = $cecfg->LOSTINFO_SUBJECT;
		
		$emailmsg = $groupinfo->ug_lostinfo_email;
		$emailmsg = str_replace("{fullname}",$user->name,$emailmsg);
		$emailmsg = str_replace("{username}",$user->username,$emailmsg);
		$emailmsg = str_replace("{password}",$newpass,$emailmsg);
		
		//Send Welcome Email
		$mail = &JFactory::getMailer();
		$mail->IsHTML(true);
		$mail->addRecipient($emailtoaddress,$emailtoname);
		$mail->setSender($emailfromaddress,$emailfromname);
		$mail->setSubject($emailsubject);
		$mail->setBody( $emailmsg );
		$sent = $mail->Send();
		
		return true;
		
	}
	
	function gen_uuid($len=8) {
		$hex = md5("in_the_beginning_users_had_no_passwords" . uniqid("", true));
		$pack = pack('H*', $hex);
		$uid = base64_encode($pack); // max 22 chars
		$nuid = preg_replace("/[^a-zA-Z0-9]/", "",$uid); // uppercase only
		if ($len<4) $len=4;
		if ($len>128) $len=128; // prevent silliness, can remove
		while (strlen($nuid)<$len)
		$nuid = $nuid . gen_uuid(22); // append until length achieved
		return substr($nuid, 0, $len);
	}

}
