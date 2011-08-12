<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
/* default
 */
class ContinuEdModelCfgE extends JModel
{
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('CFG_ID');
		$this->setId($array);
	}
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}


	function &getData()
	{
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__ce_config '.
					'  WHERE CFG_ID = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
		}
		return $this->_data;
	}

	function setDefault() {
		$qr = 'DELETE FROM #__ce_config WHERE CFG_ID=1';
		$db =& JFactory::getDBO();
		$db->setQuery($qr);
		$db->query();
		$q='INSERT INTO #__ce_config (CFG_ID, FM_TEXT, EV_TEXT, EVAL_PERCENT, EVAL_EXPL, EVAL_REQD, EVAL_ASSD, EVAL_REQI, EVAL_ASSI, CAT_PROV, CAT_GUEST, NODEG_MSG, EVAL_ANSRPT) VALUES
(1,"I acknowledge that I have reviewed this accreditation information and wish to proceed to the educational activity","I acknowledge that I have completed this educational activity as designed.",70,1,1,1,"images/continued/req.png","images/continued/assess.png",1,1,"Please select a degree in your profile under degree information.",1)';
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		return $db->query();
	}
	function store()
	{
		$row =& $this->getTable();

		$data->CFG_ID = JRequest::getVar('CFG_ID');
		$data->FM_TEXT = JRequest::getVar('FM_TEXT');
		$data->EV_TEXT  = JRequest::getVar('EV_TEXT');
		$data->EVAL_PERCENT = JRequest::getVar('EVAL_PERCENT');
		$data->EVAL_EXPL 	 = JRequest::getVar('EVAL_EXPL');
		$data->EVAL_REQD 	 = JRequest::getVar('EVAL_REQD');
		$data->EVAL_ASSD 	 = JRequest::getVar('EVAL_ASSD');
		$data->EVAL_REQI 	 = JRequest::getVar('EVAL_REQI');
		$data->EVAL_ASSI 	 = JRequest::getVar('EVAL_ASSI');
		$data->CAT_PROV 	 = JRequest::getVar('CAT_PROV');
		$data->CAT_GUEST 	 = JRequest::getVar('CAT_GUEST');
		$data->NODEG_MSG 	 = JRequest::getVar('NODEG_MSG');
		$data->EVAL_ANSRPT = JRequest::getVar('EVAL_ANSRPT');
		$data->REC_TIT = JRequest::getVar('REC_TIT');
		$data->REC_PRETXT = JRequest::getVar( 'REC_PRETXT', null, 'default', 'none', 2 );
		$data->REC_POSTTXT = JRequest::getVar( 'REC_POSTTXT', null, 'default', 'none', 2 );
		$data->SHOW_FAC = JRequest::getVar('SHOW_FAC');
		$data->LOGIN_MSG = JRequest::getVar( 'LOGIN_MSG', null, 'default', 'none', 2 );
		$data->NEEDS_DEGREE = JRequest::getVar('NEEDS_DEGREE');
		$data->TEMPLATE = JRequest::getVar('TEMPLATE');

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}

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


}
?>
