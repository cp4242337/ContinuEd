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
class ContinuEdModelUser extends JModel
{

	function getPurchaseRecords($userid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT * ';
		$query .= 'FROM #__ce_purchased as f ';
		$query .= 'LEFT JOIN #__ce_courses as c ON f.purchase_course = c.course_id ';
		$query .= 'WHERE f.purchase_user = '.$userid;
		$query .= ' ORDER BY f.purchase_time DESC';
		$db->setQuery( $query );
		$postlist = $db->loadObjectList();
		return $postlist;
	}

	function getCERecords($userid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT * ';
		$query .= 'FROM #__ce_records as f ';
		$query .= 'LEFT JOIN #__ce_courses as c ON f.rec_course = c.course_id ';
		$query .= 'WHERE f.rec_user = '.$userid;
		$query .= ' ORDER BY f.rec_start DESC';
		$db->setQuery( $query );
		$postlist = $db->loadObjectList();
		return $postlist;
	}

	/**
	* User Fields 
	*
	* @param int $group Group id for user
	*
	* @return object of user data.
	*
	* @since 1.20
	*/
	function getUserFields($group,$showhidden=false,$all=false,$changable=false) {
		$db =& JFactory::getDBO();
		$qd = 'SELECT f.* FROM #__ce_uguf as g';
		$qd.= ' RIGHT JOIN #__ce_ufields as f ON g.uguf_field = f.uf_id';
		$qd.= ' WHERE f.published = 1 && g.uguf_group='.$group;
		if (!$showhidden) $qd.=" && f.uf_hidden = 0";
		if ($changable) $qd.=" && f.uf_change = 1";
		$qd .= ' && f.uf_type != "captcha"';
		$qd.= ' ORDER BY f.ordering';
		$db->setQuery( $qd ); 
		$ufields = $db->loadObjectList();
		if ($all) {
			foreach ($ufields as &$f) {
				switch ($f->uf_type) {
					case 'multi':
					case 'dropdown':
					case 'mcbox':
					case 'mlist':
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
	* Save User 
	*
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
		$ugroup = $data['userGroupID'];
		
		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');
		
		// Allow an exception to be thrown.
		try
		{
			//setup item and bind data
			$fids = array();
			$flist = $this->getUserFields($ugroup,false,false,true);
			foreach ($flist as $d) {
				$fieldname = $d->uf_sname;
				if ($d->uf_type=='birthday') {
					$fmonth = (int)$data[$fieldname.'_month'];
					$fday = (int)$data[$fieldname.'_day'];
					if ($fmonth < 10) $fmonth = "0".$fmonth;
					if ($fday < 10) $fday = "0".$fday;
					$item->$fieldname = $fmonth.$fday;
				}
				else $item->$fieldname = $data[$fieldname];
				$fids[]=$d->uf_id;
			}
			
			//Update Joomla User Info
			$user=JFactory::getUser();
			$udata['name']=$item->fname." ".$item->lname;
			$udata['password']=$item->password;
			$udata['password2']=$item->cpassword;
			$udata['block']=$user->block;
			if (!$user->bind($udata)) {
				$this->setError($user->getError());
				return false;
			};
			if (!$user->save(true)) {
				$this->setError($user->getError());
				return false;
			}
			
			//remove joomla user info from item
			unset($item->password);
			unset($item->cpassword);
			
			
			//Save ContinuEd Userinfo
			$query	= $db->getQuery(true);
			$query->delete();
			$query->from('#__ce_users');
			$query->where('usr_user = '.$user->id);
			$query->where('usr_field IN ('.implode(",",$fids).')');
			$db->setQuery((string)$query);
			$db->query();
			
			$flist = $this->getUserFields($ugroup,false,false,true);
			foreach ($flist as $fl) {
				$fieldname = $fl->uf_sname;
				if (!$fl->uf_cms) {
					if ($fl->uf_type=="mcbox" || $fl->uf_type=="mlist") $item->$fieldname = implode(" ",$item->$fieldname);
					if ($fl->uf_type=='cbox') { 
						if ($item->$fieldname=='on') $item->$fieldname = 1;
						else $item->$fieldname = 0;
					}
					$qf = 'INSERT INTO #__ce_users (usr_user,usr_field,usr_data) VALUES ("'.$user->id.'","'.$fl->uf_id.'","'.$item->$fieldname.'")';
					$db->setQuery($qf);
					if (!$db->query()) {
						$this->setError($db->getErrorMsg());
						return false;
					}
				}
			}
			
			//Update update date
			$qud = 'UPDATE #__ce_usergroup SET userg_update = NOW() WHERE userg_user = '.$user->id;
			$db->setQuery($qud);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
			
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			
			return false;
		}
		
		return true;
	}

	

}
