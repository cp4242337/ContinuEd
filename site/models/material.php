<?php
/**
 * @version		$Id: material.php 2011-12-12 $
 * @package		ContinuEd.Site
 * @subpackage	material
 * @copyright	Copyright (C) 2008 - 2011 Corona Productions.
 * @license		GNU General Public License version 2
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * ContinuEd Material Model
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	material
 * @since		always
 */
class ContinuEdModelMaterial extends JModel
{

	/**
	* Get course information.
	*
	* @param int $courseid Courses id number
	*
	* @return object of course info.
	*
	* @since always
	*/
	function getMaterial($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT course_id,course_cat,course_materialintro,course_material,course_name,course_hasfm,course_hasmat,course_haseval,course_cataloglink,course_haspre,course_hasinter,course_qanda,course_startdate,course_enddate FROM #__ce_courses WHERE course_id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadObject();
		return $mtext;
	}

	/**
	* Get answers to required inter questions
	*
	* @param int $courseid Courses id number
	* @param array $reqids Array of question ids
	*
	* @return array of users answers.
	*
	* @since 1.11
	*/
	function getReqAns($courseid,$reqids) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$q2='SELECT question FROM #__ce_evalans WHERE userid = "'.$userid.'" && ans_area = "inter" && course = "'.$courseid.'" && question IN('.implode(",",$reqids).')';
		$db->setQuery($q2);
		return $db->loadResultArray();
	}

	/**
	* Get ids of required inter questions
	*
	* @param int $courseid Courses id number
	*
	* @return array of question ids.
	*
	* @since 1.11
	*/
	function getReQids($courseid) {
		$db =& JFactory::getDBO();
		$q='SELECT q_id FROM #__ce_questions as q WHERE q.q_area = "inter" && q.q_course = "'.$courseid.'" AND q.q_req = 1';
		$db->setQuery($q);
		$data = $db->loadResultArray();
		return $data;
	}
	
	/**
	* Get material pages for course
	*
	* @param int $cid Courses id number
	*
	* @return object array of material pages.
	*
	* @since 1.20
	*/
	function getMatPages($cid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$cecfg = ContinuEdHelper::getConfig();
		$q  = 'SELECT * FROM #__ce_material ';
		$q .= 'WHERE published = 1 && mat_course = '.$cid;
		$q .= ' ORDER BY ordering';
		$db->setQuery( $q );
		$matpages = $db->loadObjectList();
		
		//Get Media and Downloads if MAMS Exists
		if ($cecfg->mams) {
			foreach ($matpages as &$matpage) {
				
				//Get Media
				$qm=$db->getQuery(true);
				$qm->select('m.*');
				$qm->from('#__ce_matmed as mm');
				$qm->join('RIGHT','#__mams_media AS m ON mm.mm_media = m.med_id');
				$qm->where('mm.published >= 1');
				$qm->where('m.published >= 1');
				$qm->where('m.access IN ('.implode(",",$user->getAuthorisedViewLevels()).')');
				$qm->where('mm.mm_mat = '.$matpage->mat_id);
				$qm->order('mm.ordering ASC');
				$db->setQuery($qm);
				$matpage->media=$db->loadObjectList();
				
				//Get DLoads
				$qd=$db->getQuery(true);
				$qd->select('d.*');
				$qd->from('#__ce_matdl as md');
				$qd->join('RIGHT','#__mams_dloads AS d ON md.md_dload = d.dl_id');
				$qd->where('md.published >= 1');
				$qd->where('d.published >= 1');
				$qd->where('d.access IN ('.implode(",",$user->getAuthorisedViewLevels()).')');
				$qd->where('md.md_mat = '.$matpage->mat_id);
				$qd->order('md.ordering ASC');
				$db->setQuery($qd);
				$matpage->dloads=$db->loadObjectList();
			}
		}
		return $matpages; 
	}
	
	
}
