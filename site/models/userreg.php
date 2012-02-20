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
		$qd = 'SELECT ug.* FROM #__ce_ugroups as ug';
		if ($id) $qd .= " WHERE ug.ug_id = ".$id;
		$qd.= ' ORDER BY ug.ug_name';
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
					case 'mcbox':
						$qo = 'SELECT opt_id as value, opt_text as text FROM #__ce_ufields_opts WHERE opt_field='.$f->uf_id.' && published > 0 ORDER BY ordering';
						$this->_db->setQuery($qo);
						$f->options = $this->_db->loadObjectList();
						break;
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
		// Initialise variables;
		$data		= JRequest::getVar('jform', array(), 'post', 'array'); 
		$dispatcher = JDispatcher::getInstance();
		$isNew = true;
		$db		= $this->getDbo();
		$app=Jfactory::getApplication();
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
				$item->$fieldname = $data[$fieldname];
				$fids[]=$d->uf_id;
			}
			
			//Update Joomla User Info
			$user= new JUser;
			$udata['name']=$item->fname." ".$item->lname;
			$udata['email']=$item->email;
			$udata['username']=$item->username;
			$udata['password']=$item->password;
			$udata['password2']=$item->cpassword;
			$udata['block']=0;
			$udata['groups'][]=2;
			$user->bind($udata);
			if (!$user->save()) {
				$this->setError('Save Error');
				return false;
			}
			
			$credentials = array();
			$credentials['username'] = $item->username;
			$credentials['password'] = $item->password;
			
			//Update update date
			$qud = 'INSERT INTO #__ce_usergroup (userg_user,userg_group,userg_update) VALUES ('.$user->id.','.$data['userGroupID'].',NOW())';
			$db->setQuery($qud);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			//Setup Welcome email
			$groupinfo = $this->getUserGroups($data['userGroupID']);
			$emailtoaddress = $item->email;
			$emailtoname = $item->fname." ".$item->lname;
			$emailfromaddress = $cecfg->FROM_EMAIL;
			$emailfromname = $cecfg->FROM_NAME;
			$emailsubject = $cecfg->WELCOME_SUBJECT;
			
			$emailmsg = $groupinfo->ug_welcome_email;
			$emailmsg = str_replace("{fullname}",$item->fname." ".$item->lname,$emailmsg);
			$emailmsg = str_replace("{username}",$item->username,$emailmsg);
			
			
			//remove joomla user info from item
			unset($item->password);
			unset($item->cpassword);
			unset($item->email);
			unset($item->username);
			
			
			// Save ContinuED user info
			$flist = $this->getUserFields($data['userGroupID'],false);
			foreach ($flist as $fl) {
				$fieldname = $fl->uf_sname;
				if (isset($item->$fieldname)) {
					if ($fl->uf_type=="mcbox") $item->$fieldname = implode(" ",$item->$fieldname);
					if ($fl->uf_type=='cbox') $item->$fieldname = ($item->$fieldname=='on') ? "1" : "0";
					$qf = 'INSERT INTO #__ce_users (usr_user,usr_field,usr_data) VALUES ("'.$user->id.'","'.$fl->uf_id.'","'.$item->$fieldname.'")';
					$db->setQuery($qf);
					if (!$db->query()) {
						$this->setError($db->getErrorMsg());
						return false;
					}
					//welcome email fields
					$emailmsg = str_replace("{".$fieldname."}",$item->$fieldname,$emailmsg);
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
