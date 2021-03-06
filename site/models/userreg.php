<?php
/**
 * User Profile of ContinuEd Component
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 *
 */
class ContinuEdModelUserReg extends JModel
{

	/**
	* Get User Groups 
	*
	* @param int $id Group id for user
	*
	* @return object array of user groups.
	*
	* @since 1.20
	*/
	function getUserGroups($id=0) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$aid = $user->getAuthorisedViewLevels();
		$qd = 'SELECT ug.* FROM #__ce_ugroups as ug WHERE ug.access IN ('.implode(",",$aid).')';
		if ($id) $qd .= " && ug.ug_id = ".$id;
		$qd.= ' ORDER BY ug.ordering';
		$db->setQuery( $qd ); 
		$ugroups = $db->loadObjectList();
		return $ugroups;
	}
	
	/**
	* Get User Fields 
	*
	* @param int $group Group id for user
	* @param boolean $all t/f to get options for fields
	*
	* @return object of user data.
	*
	* @since 1.20
	*/
	function getUserFields($group,$all=true) {
		$app=Jfactory::getApplication();
		$db =& JFactory::getDBO();
		$qd = 'SELECT f.* FROM #__ce_uguf as g';
		$qd.= ' RIGHT JOIN #__ce_ufields as f ON g.uguf_field = f.uf_id';
		$qd.= ' WHERE f.published = 1 && g.uguf_group='.$group;
		$qd.=" && f.uf_hidden = 0";
		$qd.=" && f.uf_reg = 1";
		$qd.= ' ORDER BY f.ordering';
		$db->setQuery( $qd ); 
		$ufields = $db->loadObjectList();
		if ($all) {
			foreach ($ufields as &$f) {
				switch ($f->uf_type) {
					case 'multi':
					case 'dropdown':
					case 'mlist':
					case 'mcbox':
						$qo = 'SELECT opt_id as value, opt_text as text FROM #__ce_ufields_opts WHERE opt_field='.$f->uf_id.' && published > 0 ORDER BY ordering';
						$this->_db->setQuery($qo);
						$f->options = $this->_db->loadObjectList();
						break;
				}
			}
		
			foreach ($ufields as &$u) {
				$fn=$u->uf_sname;
				if ($u->uf_type == 'multi' || $u->uf_type == 'dropdown' || $u->uf_type == 'mcbox' || $u->uf_type == 'mlist') {
					$u->value=explode(" ",$app->getUserState('continued.userreg.'.$fn,$u->uf_default)); 
				} else if ($u->uf_type == 'cbox' || $u->uf_type == 'yesno') {
					$u->value=$app->getUserState('continued.userreg.'.$fn,$u->uf_default);
				} else if ($u->uf_type == 'birthday') {
					$u->value=$app->getUserState('continued.userreg.'.$fn,$u->uf_default);
				} else if ($u->uf_type != 'captcha') {
					$u->value=$app->getUserState('continued.userreg.'.$fn,$u->uf_default);
				}
			}
		}
		return $ufields;
	}
	
	
	/**
	* Add New User 
	*
	* @return bollean of success.
	*
	* @since 1.20
	*/
	public function save()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables;
		$data		= JRequest::getVar('jform', array(), 'post', 'array'); 
		$dispatcher = JDispatcher::getInstance();
		$isNew = true;
		$db		= $this->getDbo();
		$app=Jfactory::getApplication();
		$session=JFactory::getSession();
		$cecfg = ContinuEdHelper::getConfig();
		
		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');
		
		// Allow an exception to be thrown.
		try
		{
			//setup item and bind data
			$fids = array();
			$flist = $this->getUserFields($data['userGroupID'],false);
			foreach ($flist as $d) {
				$fieldname = $d->uf_sname;
				if ($d->uf_type == 'captcha') {
					$capfield=$fieldname;
				} else if ($d->uf_type=="mcbox" || $d->uf_type=="mlist") {
					$item->$fieldname = implode(" ",$data[$fieldname]);
				} else if ($d->uf_type=='cbox') {
					$item->$fieldname = ($data[$fieldname]=='on') ? "1" : "0";
				} else if ($d->uf_type=='birthday') {
					$fmonth = (int)$data[$fieldname.'_month'];
					$fday = (int)$data[$fieldname.'_day'];
					if ($fmonth < 10) $fmonth = "0".$fmonth;
					if ($fday < 10) $fday = "0".$fday;
					$item->$fieldname = $fmonth.$fday;
				} else {
					$item->$fieldname = $data[$fieldname];
				}
				if ($d->uf_type != 'captcha') $fids[]=$d->uf_id;
				if ($d->uf_type != 'captcha' || $d->uf_type != 'password') $app->setUserState('continued.userreg.'.$fieldname, $item->$fieldname);
			}
			
			if ($capfield) {
				include_once 'components/com_continued/lib/securimage/securimage.php';
				$securimage = new Securimage();
				$securimage->session_name = $session->getName();
				$securimage->case_sensitive  = false; 
				if ($securimage->check($data[$capfield]) == false) {
					$this->setError('Security Code Incorrect');
					return false;
				} 
			}
			
			//Update Joomla User Info
			$user= new JUser;
			$udata['name']=$item->fname." ".$item->lname;
			$udata['email']=strtolower($item->email);
			$udata['username']=(strtolower($item->username)) ? strtolower($item->username) : strtolower($item->email);
			$udata['password']=$item->password;
			$udata['password2']=$item->cpassword;
			$udata['block']=0;
			$udata['groups'][]=2;
			if (!$user->bind($udata)) {
				$this->setError('Bind Error: '.$user->getError());
				return false;
			}
			if (!$user->save()) {
				$this->setError('Save Error:'.$user->getError());
				return false;
			}
			
			$credentials = array();
			$credentials['username'] = (strtolower($item->username)) ? strtolower($item->username) : strtolower($item->email);
			$credentials['password'] = $item->password;
			
			//Set user group info
			$qud = 'INSERT INTO #__ce_usergroup (userg_user,userg_group,userg_update) VALUES ('.$user->id.','.$data['userGroupID'].',NOW())';
			$db->setQuery($qud);
			if (!$db->query()) {
				$this->setError('Could not update user group');
				return false;
			}
			
			//Setup Welcome email
			$groupinfo = $this->getUserGroups($data['userGroupID']);
			$emailtoaddress = $item->email;
			$emailtoname = $item->fname." ".$item->lname;
			$emailfromaddress = $cecfg->FROM_EMAIL;
			$emailfromname = $cecfg->FROM_NAME;
			$emailsubject = $cecfg->WELCOME_SUBJECT;
			
			$emailmsg = $groupinfo[0]->ug_welcome_email;
			$emailmsg = str_replace("{fullname}",$item->fname." ".$item->lname,$emailmsg);
			$emailmsg = str_replace("{username}",(strtolower($item->username)) ? strtolower($item->username) : strtolower($item->email),$emailmsg);
			$emailmsg = str_replace("{password}",$item->password,$emailmsg);
			
			
			//remove joomla user info from item
			unset($item->password); 
			unset($item->cpassword);
			unset($item->email); $app->setUserState('continued.userreg.email', "");
			unset($item->username); $app->setUserState('continued.userreg.username', "");
			
			
			// Save ContinuED user info
			$flist = $this->getUserFields($data['userGroupID'],false);
			foreach ($flist as $fl) {
				$fieldname = $fl->uf_sname;
				if (!$fl->uf_cms && $fl->uf_type != "captcha") {
					$qf = 'INSERT INTO #__ce_users (usr_user,usr_field,usr_data) VALUES ("'.$user->id.'","'.$fl->uf_id.'","'.$item->$fieldname.'")';
					$db->setQuery($qf);
					if (!$db->query()) {
						$this->setError("Error saving additional information");
						return false;
					}
					//welcome email fields
					$emailmsg = str_replace("{".$fieldname."}",$item->$fieldname,$emailmsg);
					if ($d->uf_type!='captcha' || $d->uf_type!='password') $app->setUserState('continued.userreg.'.$fieldname, "");
				}
			}
			
			//Send Welcome Email
			$mail = &JFactory::getMailer();
			$mail->IsHTML(true);
			$mail->addRecipient($emailtoaddress,$emailtoname);
			$mail->setSender($emailfromaddress,$emailfromname);
			$mail->setSubject($emailsubject);
			$mail->setBody( $emailmsg );
			$sent = $mail->Send();
			
			//Login User
			$options = array();
			$options['remember'] = true;
			$error = $app->login($credentials, $options);
			
			
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			
			return false;
		}
		
		return true;
	}

	

}
