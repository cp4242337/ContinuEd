<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelConfig extends JModel
{
	function getConfig()
	{
		$db =& JFactory::getDBO();
		$q = 'SELECT * FROM #__ce_config';
		$db->setQuery($q);
		return $db->loadAssoc();
	}

}
