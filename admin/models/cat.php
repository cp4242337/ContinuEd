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
class ContinuEdModelCat extends JModel
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

		$array = JRequest::getVar('lid',  0, '', 'array');
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
			$query = ' SELECT * FROM #__ce_cats '.
					'  WHERE cid = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
		}
		return $this->_data;
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
		$data->cid = JRequest::getVar('cid');
		$data->catname = JRequest::getVar('catname');
		$data->catdesc = JRequest::getVar( 'catdesc', null, 'default', 'none', 2 );
		$data->cathasfm = JRequest::getVar('cathasfm');
		$data->catfm = JRequest::getVar( 'catfm', null, 'default', 'none', 2 );
		$data->catsku = JRequest::getVar('catsku');
		$data->catfree = JRequest::getVar('catfree');
		$data->catskulink = JRequest::getVar('catskulink');
		$data->catprev = JRequest::getVar('catprev');
		$data->catmenu = JRequest::getVar('catmenu');
		$data->catfmlink = JRequest::getVar('catfmlink');
		$data->cat_start = JRequest::getVar('cat_start');
		$data->cat_end = JRequest::getVar('cat_end');

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
		$lids = JRequest::getVar( 'lid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $lids ))
		{
			foreach($lids as $lid) {
				if (!$row->delete( $lid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	function getCatList()
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT * FROM #__ce_cats ';
		$db->setQuery( $query );
		return $db->loadObjectList();
	}

}
?>
