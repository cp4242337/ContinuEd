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
class ContinuEdModelAnsCompl extends JModel
{
	function getQuestionsPre($fid)
	{
		$query  = ' SELECT * FROM #__ce_completed WHERE fid ='.$fid;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$data = $db->loadObject();
		$courseid = $data->course;
		$sessionid = $data->fsessionid;
		$userid = $data->user;
		$q2  = 'SELECT q.*,q.id as qid,a.*,o.opttxt,o.optexpl,o.correct FROM #__ce_evalans as a ';
		$q2 .= 'LEFT JOIN #__ce_questions as q ON q.id=a.question ';
		$q2 .= 'LEFT JOIN #__ce_questions_opts as o ON o.id=a.answer ';
		$q2 .= 'WHERE q.q_area = "pre" && a.sessionid = "'.$sessionid.'" && a.userid = "'.$userid.'" && a.course = "'.$courseid.'" ';
		$q2 .= 'GROUP BY q.id ';
		$q2 .= 'ORDER BY q.qsec, q.ordering';
		$db->setQuery($q2);
		$data = $db->loadObjectList();
		return $data;
	}
	function getQuestionsPost($fid)
	{
		$query  = ' SELECT * FROM #__ce_completed WHERE fid ='.$fid;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$data = $db->loadObject();
		$courseid = $data->course;
		$sessionid = $data->fsessionid;
		$userid = $data->user;
		$q2  = 'SELECT q.*,q.id as qid,a.*,o.opttxt,o.optexpl,o.correct FROM #__ce_evalans as a ';
		$q2 .= 'LEFT JOIN #__ce_questions as q ON q.id=a.question ';
		$q2 .= 'LEFT JOIN #__ce_questions_opts as o ON o.id=a.answer ';
		$q2 .= 'WHERE q.q_area = "post" && a.sessionid = "'.$sessionid.'" && a.userid = "'.$userid.'" && a.course = "'.$courseid.'" ';
		$q2 .= 'GROUP BY q.id ';
		$q2 .= 'ORDER BY q.qsec, q.ordering';
		$db->setQuery($q2);
		$data = $db->loadObjectList();
		return $data;
	}
	function getCourseInfo($cid)
	{
		$query  = ' SELECT cname,course_haspre,haseval FROM #__ce_courses WHERE id ='.$cid;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$data = $db->loadObject();
		return $data;
	}

}
