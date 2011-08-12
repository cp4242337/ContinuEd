<?php

// No direct access to this file
defined('_JEXEC') or die;

abstract class ContinuEdHelper
{
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_COURSES'), 'index.php?option=com_continued&view=courses', $submenu == 'courses');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CONFIG'), 'index.php?option=com_continued&view=config', $submenu == 'config');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CATEGORIES'), 'index.php?option=com_continued&view=cats', $submenu == 'cats');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_PROVIDERS'), 'index.php?option=com_continued&view=provs', $submenu == 'provs');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_REPORTS'), 'index.php?option=com_continued&view=reports', $submenu == 'reports');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CERTIFICATES'), 'index.php?option=com_continued&view=certifs', $submenu == 'certifs');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_DEGREES'), 'index.php?option=com_continued&view=degrees', $submenu == 'degrees');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CERTTYPES'), 'index.php?option=com_continued&view=certtypes', $submenu == 'certtypes');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_COURSESTATS'), 'index.php?option=com_continued&view=coursestat', $submenu == 'coursestat');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_CATSTATS'), 'index.php?option=com_continued&view=catstat', $submenu == 'catstat');
		JSubMenuHelper::addEntry(JText::_('COM_CONTINUED_SUBMENU_UGROUPS'), 'index.php?option=com_continued&view=ugroups', $submenu == 'ugroups');
	}
	
}
