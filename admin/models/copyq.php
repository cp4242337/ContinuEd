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
class ContinuEdModelCopyQ extends JModel
{
	function getCourseList() {
		$q='SELECT id,cname FROM #__ce_courses ORDER BY cname';
		$this->_db->setQuery($q);
		return $this->_db->loadObjectList();
	}
	function getQuestions() {
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$q='SELECT id,qtext FROM #__ce_questions WHERE id IN ('.implode(",",$cid).')';
		$this->_db->setQuery($q);
		return $this->_db->loadAssocList();
	}
	function copyQ() {
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		//$course = JRequest::getVar('course');
		JArrayHelper::toInteger($cid);
		$total		= count( $cid );
		$row =& $this->getTable('question');

		//$q='SELECT * FROM #__ce_questions_opts WHERE id IN ('.implode(",",$cid).')';
		//$this->_db->setQuery($q);
		//$ans = $this->_db->loadObjectList();


		//$q='SELECT * FROM #__ce_questions WHERE id IN ('.implode(",",$cid).')';
		//$this->_db->setQuery($q);
		//$qus = $this->_db->loadObjectList();
		//if ($qus)
		//{
		foreach($cid as $qu) {
			$row->load($qu);
			$row->id=0;
			$row->ordering=$row->getNextOrder('course = '.$row->course);
			if(!$row->store()) return false;
			/*$q  = 'INSERT INTO #__ce_questions (course,ordering,qtext,qtype,qcat,qsec,qreq) ';
				$q .= 'VALUES ("'.$course.'","'.$qu->ordering.'","'.$qu->qtext.'","'.$qu->qtype.'","'.$qu->qcat.'","'.$qu->qsec.'","'.$qu->qreq.'")';
				$this->_db->setQuery($q);
				if (!$this->_db->query($q)) {
				return false;
				}*/
			//if ($qu->qtype == 'multi' || $qu->qtype == 'mcbox') {
			if ($row->qtype == 'multi' || $row->qtype == 'mcbox' || $row->qtype == 'dropdown') {
				$newid = $row->id;
				$qoq='SELECT * FROM #__ce_questions_opts WHERE question = '.$qu->id;
				$this->_db->setQuery($qoq);
				$qos = $this->_db->loadObjectList();
				foreach($qos as $qo) {
					$q  = 'INSERT INTO #__ce_questions_opts (question,opttxt,correct,optexpl,disporder) ';
					$q .= 'VALUES ("'.$newid.'","'.$qo->opttxt.'","'.$qo->correct.'","'.$qo->optexpl.'","'.$qo->disporder.'")';
					$this->_db->setQuery($q);
					if (!$this->_db->query($q)) {
						return false;
					}
				}
			}
		}
		//} else return false;
		return true;
	}
}
?>
