<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class ContinuEdViewMaterials extends JView
{
	function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
		$this->state		= $this->get('State');
		$clist = $this->get('Courses');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
		$this->clist = $clist;
		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$state	= $this->get('State');
		JToolBarHelper::title(JText::_('COM_CONTINUED_MANAGER_MATERIALS'), 'continued');
		JToolBarHelper::addNew('material.add', 'JTOOLBAR_NEW');
		JToolBarHelper::editList('material.edit', 'JTOOLBAR_EDIT');
		JToolBarHelper::custom('materials.copy', 'copy.png', 'copy_f2.png','JTOOLBAR_COPY', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('materials.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('materials.unpublish', 'unpublish.png', 'unpublish_f2.png','JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		if ($state->get('filter.published') == -2) {
			JToolBarHelper::deleteList('', 'materials.delete', 'JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} else  {
			JToolBarHelper::trash('materials.trash');
		}
		JToolBarHelper::divider();
		JToolBarHelper::back('COM_CONTINUED_TOOLBAR_COURSES','index.php?option=com_continued&view=courses');
		
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_CONTINUED_MANAGER_MATERIALS'));
	}
}
