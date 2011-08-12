<?php
/**
 * Hello Model for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Hello Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdModelCourse extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the hello identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}


	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__ce_courses '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->cname = null;
			$this->_data->cdesc=null;
			$this->_data->prereq=0;

		}
		return $this->_data;
	}
	
	/**
	 * Get List of Providers
	 * @return object with data
	 */
	function getProList()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT * FROM #__ce_providers ';
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
	
	/**
	 * Get List of Categories
	 * @return object with data
	 */
	function getCatList()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT * FROM #__ce_cats ORDER BY catname';
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

	
	/**
	 * Get List of Certificate Types
	 * @return object with data
	 */
	function getCertList()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT * FROM #__ce_certifs ';
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
	
	
	/**
	 * Get List of Courses
	 * @return array
	 */
	function getCourseList()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT *,CONCAT(s.catname," - ",c.cname) as coursecat FROM #__ce_courses as c';
		$query .= ' LEFT JOIN #__ce_cats as s ON s.cid=c.ccat ';
		$query .= ' ORDER BY s.catname ASC, c.ordering ASC';
		$db->setQuery( $query );
		$data = $db->loadObjectList();
		$clist[]=JHTML::_('select.option','0','--None--');
		foreach ($data as $d) {
			if (strlen($d->coursecat) > 70) $d->coursecat = substr($d->coursecat,0,67).'...';
			$clist[]=JHTML::_('select.option',$d->id,$d->coursecat);
		}
		return $clist;
	}

	
	/**
	 * Copy Course
	 */
	function copyC() {
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$total		= count( $cid );
		$rowc =& $this->getTable();
		foreach ($cid as $oldcourse) {
			$rowc->load($oldcourse);
			$rowc->id=0;
			$rowc->cname='Copy of '.$rowc->cname;
			$rowc->ordering=$rowc->getNextOrder('ccat = '.$rowc->ccat);
			$rowc->published=0;
			$rowc->dateadded=null;
			if(!$rowc->store()) return false;
			$newcourse = $rowc->id;

			//PArts
			$q='SELECT * FROM #__ce_parts WHERE part_course = '.$oldcourse;
			$this->_db->setQuery($q);
			$qps = $this->_db->loadObjectList();
			foreach($qps as $qp) {
				$q  = 'INSERT INTO #__ce_parts (part_course,part_part,part_name,part_desc,part_area) ';
				$q .= 'VALUES ("'.$newcourse.'","'.$qp->part_part.'","'.addslashes($qp->part_name).'","'.addslashes($qp->part_desc).'","'.$qp->part_area.'")';
				$this->_db->setQuery($q);
				if (!$this->_db->query($q)) {
					return false;
				}
			}

			//Course Certificates
			$q='SELECT * FROM #__ce_coursecerts WHERE course_id = '.$oldcourse;
			$this->_db->setQuery($q);
			$qcs = $this->_db->loadObjectList();
			foreach($qcs as $qc) {
				$q  = 'INSERT INTO #__ce_coursecerts (course_id,cert_id) ';
				$q .= 'VALUES ("'.$newcourse.'","'.$qc->cert_id.'")';
				$this->_db->setQuery($q);
				if (!$this->_db->query($q)) {
					return false;
				}
			}


			//Questions and Answers
			$q='SELECT * FROM #__ce_questions WHERE course = '.$oldcourse;
			$this->_db->setQuery($q);
			$qus = $this->_db->loadObjectList();
			if ($qus)
			{
				foreach($qus as $qu) {
					$q  = 'INSERT INTO #__ce_questions (course,ordering,qtext,qtype,qcat,qsec,qreq,q_depq,q_area) ';
					$q .= 'VALUES ("'.$newcourse.'","'.$qu->ordering.'","'.addslashes($qu->qtext).'","'.$qu->qtype.'","'.$qu->qcat.'","'.$qu->qsec.'","'.$qu->qreq.'","'.$qu->q_depq.'","'.$qu->q_area.'")';
					$this->_db->setQuery($q);
					if (!$this->_db->query($q)) {
						return false;
					}
					if ($qu->qtype == 'multi' || $qu->qtype == 'mcbox' || $qu->qtype == 'dropdown') {
						$newid = $this->_db->insertid();
						$qoq='SELECT * FROM #__ce_questions_opts WHERE question = '.$qu->id;
						$this->_db->setQuery($qoq);
						$qos = $this->_db->loadObjectList();
						foreach($qos as $qo) {
							$q  = 'INSERT INTO #__ce_questions_opts (question,opttxt,correct,optexpl,ordering) ';
							$q .= 'VALUES ("'.$newid.'","'.addslashes($qo->opttxt).'","'.$qo->correct.'","'.addslashes($qo->optexpl).'","'.$qo->ordering.'")';
							$this->_db->setQuery($q);
							if (!$this->_db->query($q)) {
								return false;
							}
						}
					}
				}
			} else return false;
		}
		return true;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$row =& $this->getTable();

		//$data = JRequest::get( 'post' );
		$data->id = JRequest::getVar('id');
		$data->cname = JRequest::getVar('cname');
		$data->certifname = JRequest::getVar('certifname');
		$data->provider = JRequest::getVar('provider');
		$data->cdesc = JRequest::getVar('cdesc',null,'default','none',4);
		$data->keywords = JRequest::getVar('keywords',null,'default','none',4);
		$data->frontmatter = JRequest::getVar( 'frontmatter', null, 'default', 'none', 2 );
		$data->material = JRequest::getVar( 'material', null, 'default', 'none', 2);
		$data->startdate = JRequest::getVar('startdate');
		$data->enddate = JRequest::getVar('enddate');
		$data->ccat = JRequest::getVar('ccat');
		$data->evalparts = JRequest::getVar('evalparts');
		$data->published = JRequest::getVar('published');
		$data->credits = JRequest::getVar('credits');
		$data->faculty = JRequest::getVar('faculty');
		$data->actdate = JRequest::getVar('actdate');
		$data->cneprognum = JRequest::getVar('cneprognum');
		$data->cpeprognum = JRequest::getVar('cpeprognum',null,'default','none',4);
		$data->previmg = JRequest::getVar('previmg');
		$data->prereq = JRequest::getVar('prereq');
		$data->ordering = JRequest::getVar('ordering');
		$data->hascertif = JRequest::getVar('hascertif');
		$data->cataloglink = JRequest::getVar('cataloglink');
		$data->hasfm = JRequest::getVar('hasfm');
		$data->hasmat = JRequest::getVar('hasmat');
		$data->defaultcertif = JRequest::getVar('defaultcertif');
		$data->haseval = JRequest::getVar('haseval');
		$data->access = JRequest::getVar('access');
		$data->course_catlink = JRequest::getVar('course_catlink');
		$data->course_catmenu = JRequest::getVar('course_catmenu');
		$data->course_catexp = JRequest::getVar('course_catexp');
		$data->course_compcourse = JRequest::getVar('course_compcourse');
		$data->course_viewans = JRequest::getVar('course_viewans');
		$data->course_passmsg = JRequest::getVar('course_passmsg',null,'default','none',4);
		$data->course_failmsg = JRequest::getVar('course_failmsg',null,'default','none',4);
		$data->course_allowrate = JRequest::getVar('course_allowrate');
		$data->course_rating = JRequest::getVar('course_rating');
		$data->course_catrate = JRequest::getVar('course_catrate');
		$data->course_subtitle = JRequest::getVar('course_subtitle');
		$data->course_searchable = JRequest::getVar('course_searchable');
		$data->course_evaltype = JRequest::getVar('course_evaltype');
		$data->course_extlink = JRequest::getVar('course_extlink');
		$data->course_exturl = JRequest::getVar('course_exturl');
		$data->course_haspre = JRequest::getVar('course_haspre');
		$data->course_preparts = JRequest::getVar('course_preparts');
		$data->course_changepre = JRequest::getVar('course_changepre');
		$data->course_purchase = JRequest::getVar('course_purchase');
		$data->course_purchaselink = JRequest::getVar('course_purchaselink');
		$data->course_purchasesku = JRequest::getVar('course_purchasesku');
		$data->course_learntype = JRequest::getVar('course_learntype');
		$data->course_hasinter = JRequest::getVar('course_hasinter');
		$data->course_qanda = JRequest::getVar('course_qanda');
		//i hate that i have so many fields


		//JRequest::_cleanVar($data->PostBody,4);


		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	function setOrder($items) {
		$total		= count( $items );
		$row		=& $this->getTable();
		$groupings	= array();

		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for( $i=0; $i < $total; $i++ ) {
			$row->load( $items[$i] );
			// track parents
			$groupings[] = $row->ccat;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($row->getError());
					return false;
				}
			} // if
		} // for

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('ccat = '.(int) $group);
		}

		return true;
	}
	function orderItem($item, $movement)
	{
		$row =& $this->getTable();
		$row->load( $item );
		if (!$row->move( $movement,'ccat = '.$row->ccat )) {
			$this->setError($row->getError());
			return false;
		}
		return true;
	}
	function publish($cid = array(), $publish = 1)
	{

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__ce_courses'
			. ' SET published = ' . intval( $publish )
			. ' WHERE id IN ( '.$cids.' )'
				
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
	}
	function setAccess( $items, $access )
	{
		$row =& $this->getTable();
		foreach ($items as $id)
		{
			$row->load( $id );
			$row->access = $access;

			if (!$row->check()) {
				$this->setError($row->getError());
				return false;
			}
			if (!$row->store()) {
				$this->setError($row->getError());
				return false;
			}
		}
		return true;
	}
}
?>
