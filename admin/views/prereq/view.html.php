<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class ContinuEdViewPreReq extends JView
{
	/**
	 * display method of view
	 * @return void
	 */
	public function display($tpl = null) 
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
		$script = $this->get('Script');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->script = $script;

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
		JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
		$isNew = $this->item->pr_id == 0;
		JToolBarHelper::title($isNew ? JText::_('COM_CONTINUED_MANAGER_PREREQ_NEW') : JText::_('COM_CONTINUED_MANAGER_PREREQ_EDIT'), 'prereq');
		// Built the actions for new and existing records.
		if ($isNew) 
		{
			// For new records, check the create permission.
			JToolBarHelper::save('prereq.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('prereq.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::save('prereq.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('prereq.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew = $this->item->pr_id == 0;
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_CONTINUED_PREREQ_CREATING') : JText::_('COM_CONTINUED_PREREQ_EDITING'));
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_continued/views/prereq/submitbutton.js");
		JText::script('COM_CONTINUED_PREREQ_ERROR_UNACCEPTABLE');
	}
}
