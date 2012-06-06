<?php
/**
 * @version		$Id: matpage.php 2011-12-12 $
 * @package		ContinuEd.Site
 * @subpackage	matpage
 * @copyright	Copyright (C) 2008 - 2011 Corona Productions.
 * @license		GNU General Public License version 2
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * ContinuEd MatPage Model
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	matpage
 * @since		1.20
 */
class ContinuEdModelMatpage extends JModel
{

	/**
	* Get matpage information.
	*
	* @param int $pageid Matpage id number
	*
	* @return object of matpage info.
	*
	* @since 1.20
	*/
	function getMatPage($pageid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$cecfg = ContinuEdHelper::getConfig();
		$query = 'SELECT * FROM #__ce_material WHERE mat_id = '.$pageid;
		$db->setQuery( $query );
		$matpage = $db->loadObject();
		//Get Media if MAMS Exists
		if ($cecfg->mams) {
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
		}
		return $matpage;
	}

}
