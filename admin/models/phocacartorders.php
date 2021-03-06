<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die();
jimport('joomla.application.component.modellist');

class PhocaCartCpModelPhocacartOrders extends JModelList
{
	protected $option 	= 'com_phocacart';

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'user_name','user_name',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'status', 'status',
				'date', 'a.date',
				'modified', 'a.modified',
				'ordering', 'a.ordering',
				'language', 'a.language',
				//z'hits', 'a.hits',
				'published','a.published'
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

/*		$accessId = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);*/

		$state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $state);

		$language = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		// Load the parameters.
		$params = PhocacartUtils::getComponentParameters();
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.date', 'desc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		//$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.order_id');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{

		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__phocacart_orders` AS a');

		$query->select('u.name AS user_name, u.username AS user_username');
		$query->join('LEFT', '#__users AS u ON u.id=a.user_id');

		$query->select('uv.name AS vendor_name, uv.username AS vendor_username');
		$query->join('LEFT', '#__users AS uv ON uv.id=a.vendor_id');


		$query->select('sc.title AS section_name');
		$query->join('LEFT', '#__phocacart_sections AS sc ON sc.id=a.section_id');

		$query->select('un.title AS unit_name');
		$query->join('LEFT', '#__phocacart_units AS un ON un.id=a.unit_id');


		$query->select('os.title AS status_title');
		$query->join('LEFT', '#__phocacart_order_statuses AS os ON os.id = a.status_id');

		$query->select('t.amount AS total_amount, t.amount_currency AS total_amount_currency');
		$query->join('LEFT', '#__phocacart_order_total AS t ON a.id = t.order_id');
		$query->where('(t.type = '.$db->quote('brutto').' OR t.type = \'\' OR t.type IS NULL)');

		// Join over the language
		//$query->select('l.title AS language_title');
		//$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');


		// Search users in orders
		$query->join('LEFT', '#__phocacart_order_users AS us0 ON a.id=us0.order_id AND us0.type = 0');// search in billing address
		$query->join('LEFT', '#__phocacart_order_users AS us1 ON a.id=us1.order_id AND us1.type = 1');// search in shipping address


		// Filter by access level.
/*		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = '.(int) $access);
		}*/

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

				$searchIn = array('name_first', 'name_middle', 'name_last', 'name_degree', 'company', 'vat_1', 'vat_2', 'address_1', 'address_2', 'city', 'zip', 'email', 'email_contact', 'phone_1', 'phone_2', 'phone_mobile', 'fax' );

				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$searchInP =  array();

				$searchInP[] = 'a.title LIKE '. $search;
				$searchInP[] = 'a.alias LIKE '. $search;
				$searchInP[] = 'a.comment LIKE '. $search;
				foreach($searchIn as $k => $v) {
					$searchInP[] = 'us0.'.$v . ' LIKE '. $search;// search in billing address
					$searchInP[] = 'us1.'.$v . ' LIKE '. $search;// search in shipping address
				}

				$query->where('('.implode(' OR ', $searchInP).')');
				//$query->where('( a.title LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'title');
		$orderDirn	= $this->state->get('list.direction', 'asc');

		/*if ($orderCol != 'a.id') {
			$orderCol = 'a.id';
		}*/
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__', 'jos_', $query->__toString()));


		return $query;
	}

}
?>
