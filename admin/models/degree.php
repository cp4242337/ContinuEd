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
class ContinuEdModelDegree extends JModel
{
	function getCertifs()
	{
		$db =& JFactory::getDBO();
		$q1 = 'SELECT * FROM #__ce_certifs';
		$db->setQuery($q1);
		$data = $db->loadAssocList();
		$data[]->crt_name='--Show All Providers--';
		return $data;
	}
	function getCurrCertif($degree)
	{
		$db =& JFactory::getDBO();
		$q1 = 'SELECT cert FROM #__ce_degreecert WHERE degree="'.$degree.'"';
		$db->setQuery($q1);
		$data = $db->loadResult();
		return $data;
	}

	function store($degree,$cert)
	{
		$db =& JFactory::getDBO();
		$q1 = 'DELETE FROM #__ce_degreecert WHERE degree="'.$degree.'"';
		$db->setQuery($q1);
		$db->Query();
		$q2 = 'INSERT INTO #__ce_degreecert (degree,cert) VALUES ("'.$degree.'","'.$cert.'")';
		$db->setQuery($q2);
		$db->Query();


		return true;
	}


}
?>
