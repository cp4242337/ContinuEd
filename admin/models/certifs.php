<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelCertifs extends JModelList
{
	
	public function __construct($config = array())
	{
		
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('ct.ctmpl_id', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('ct.*');

		// From the hello table
		$query->from('#__ce_certiftempl as ct');
		
		// Join over the certificate type.
		$query->select('c.crt_name AS cert_type');
		$query->join('LEFT', '#__ce_certifs AS c ON c.crt_id = ct.ctmpl_cert');
		
		// Join over the provider.
		$query->select('p.prov_name AS provider_name');
		$query->join('LEFT', '#__ce_providers AS p ON p.prov_id = ct.ctmpl_prov');
			
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('ct.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(ct.published IN (0, 1))');
		}
		
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}
}
