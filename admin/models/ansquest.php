<?php
/**
 * Hellos Model for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdModelAnsQuest extends JModel
{
	function getResponses($qid,$qtype)
	{
		$db =& JFactory::getDBO();
		$q2  = 'SELECT * FROM #__ce_evalans as a ';
		if ($qtype == 'multi' || $qtype=='dropdown') $q2 .= 'LEFT JOIN #__ce_questions_opts as o ON o.id=a.answer ';
		$q2 .= 'LEFT JOIN #__comprofiler as u ON u.user_id=a.userid ';
		$q2 .= 'WHERE a.question = "'.$qid.'"';
		$q2 .= 'GROUP BY a.sessionid ';
		$db->setQuery($q2);
		$data = $db->loadObjectList();
		return $data;
	}
	function getqInfo($qid)
	{
		$query  = ' SELECT * FROM #__ce_questions WHERE id ='.$qid;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$data = $db->loadObject();
		return $data;
	}
}
