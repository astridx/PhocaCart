<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport('joomla.application.component.modellist');

class PhocaCartCpModelPhocacartZones extends JModelList
{
	protected $option 	= 'com_phocacart';	
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'code2', 'a.code2',
				'code3', 'a.code3',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'ordering', 'a.ordering',
				'published','a.published',
			);
		}
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		


		$state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $state);

		//$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		//$this->setState('filter.language', $language);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_phocacart');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.title', 'asc');
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.zone_id');
		

		return parent::getStoreId($id);
	}
	
	protected function getListQuery() {

		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		$columns	= 'a.id, a.image, uc.name, a.ordering, a.checked_out, a.title, a.published, a.code2, a.code3';
		$groupsFull	= $columns;
		$groupsFast	= 'a.id';
		$groups		= PhocacartUtilsSettings::isFullGroupBy() ? $groupsFull : $groupsFast;
		
		// Select the required fields from the table.
		$query->select($this->getState('list.select', $columns));
		$query->from('`#__phocacart_zones` AS a');

		// Join over the language
		//$query->select('l.title AS language_title');
		//$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		

		


		// Filter by published state.
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}


		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('( a.title LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
		}
		
		$query->group($groups);
	
		$orderCol	= $this->state->get('list.ordering', 'title');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__', 'jos_', $query->__toString()));
		return $query;	
	}
}
?>