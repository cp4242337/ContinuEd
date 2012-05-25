<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: matdloads.php 2012-05-24 $
 * @package		ContinuEd.Admin
 * @subpackage	matdloads
 * @copyright	Copyright (C) 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport('joomla.application.component.modellist');

/**
 * ContinuEd Downloads Model
 *
 * @static
 * @package		ContinuEd.Admin
 * @subpackage	matdloads
 * @since		1.0
 */
class ContinuEdModelMatDloads extends JModelList
{
	
	public function __construct($config = array())
	{
		
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'ordering', 'a.ordering',
			);
		}
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);

		$matId = $this->getUserStateFromRequest($this->context.'.filter.material', 'filter_material','');
		$this->setState('filter.material', $matId);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.ordering', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the table
		$query->from('#__ce_matdl as a');
		
		// Filter by article.
		$matId = $this->getState('filter.material');
		if (is_numeric($matId)) {
			$query->where('a.md_mat = '.(int) $matId);
		}

		// Join over the authors.
		$query->select('dl.dl_fname');
		$query->join('LEFT', '#__mams_dloads AS dl ON dl.dl_id = a.md_dload');
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}
		
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}
}
