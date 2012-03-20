<?php

// No direct access to this file
defined('_JEXEC') or die;

abstract class ContinuEdHelper
{
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_COURSES'), 'index.php?option=com_continued&view=courses', $submenu == 'courses');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CATEGORIES'), 'index.php?option=com_continued&view=cats', $submenu == 'cats');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_PROVIDERS'), 'index.php?option=com_continued&view=provs', $submenu == 'provs');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_REPORTS'), 'index.php?option=com_continued&view=coursereport', $submenu == 'coursereport');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CERTIFICATES'), 'index.php?option=com_continued&view=certifs', $submenu == 'certifs');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_GROUPCERTS'), 'index.php?option=com_continued&view=groupcerts', $submenu == 'groupcerts');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CERTTYPES'), 'index.php?option=com_continued&view=certtypes', $submenu == 'certtypes');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_COURSESTATS'), 'index.php?option=com_continued&view=coursestat', $submenu == 'coursestat');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CATSTATS'), 'index.php?option=com_continued&view=catstat', $submenu == 'catstat');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_UGROUPS'), 'index.php?option=com_continued&view=ugroups', $submenu == 'ugroups');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_UFIELDS'), 'index.php?option=com_continued&view=ufields', $submenu == 'ufields');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_USERS'), 'index.php?option=com_continued&view=users', $submenu == 'Users');
	}
	
	/**
	* Get a list of filter options for the blocked state of a user.
	*
	* @return array An array of JHtmlOption elements.
	*
	* @since 1.20
	*/
	static function getStateOptions()
	{
		// Build the filter options.
		$options = array();
		$options[] = JHtml::_('select.option', '0', JText::_('JENABLED'));
		$options[] = JHtml::_('select.option', '1', JText::_('JDISABLED'));
		
		return $options;
	}
}
