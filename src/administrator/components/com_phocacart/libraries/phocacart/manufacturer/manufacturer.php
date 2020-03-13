<?php
/**
 * @package   Phoca Cart
 * @author    Jan Pavelka - https://www.phoca.cz
 * @copyright Copyright (C) Jan Pavelka https://www.phoca.cz
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 and later
 * @cms       Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();

class PhocacartManufacturer
{


	public static function getAllManufacturers($ordering = 1, $onlyAvailableProducts = 0, $lang = '', $filterProducts = array(), $limitCount = -1)
	{

		$db 			= JFactory::getDBO();
		$orderingText 	= PhocacartOrdering::getOrderingText($ordering, 4);

		$wheres		= array();
		$lefts		= array();

		$columns		= 'm.id, m.title, m.image, m.alias, m.description, m.count_products';

		/*
		$groupsFull       = $columns;
		$groupsFast		= 'm.id';
		$groups			= PhocacartUtilsSettings::isFullGroupBy() ? $groupsFull : $groupsFast;*/

		$wheres[]	= ' m.published = 1';

		if ($lang != '' && $lang != '*')
		{
			$wheres[] 	= PhocacartUtilsSettings::getLangQuery('m.language', $lang);
		}

		if ($onlyAvailableProducts == 1)
		{
			if ($lang != '' && $lang != '*')
			{
				$wheres[] 	= PhocacartUtilsSettings::getLangQuery('p.language', $lang);
			}

			$lefts[] = ' #__phocacart_products AS p ON m.id = p.manufacturer_id';
			$rules = PhocacartProduct::getOnlyAvailableProductRules();
			$wheres = array_merge($wheres, $rules['wheres']);
			$lefts	= array_merge($lefts, $rules['lefts']);
		}
		else
		{
			if ($lang != '' && $lang != '*')
			{
				$wheres[] 	= PhocacartUtilsSettings::getLangQuery('p.language', $lang);
				$lefts[] = ' #__phocacart_products AS p ON m.id = p.manufacturer_id';
			}
		}

		if (!empty($filterProducts))
		{
			$productIds = implode(',', $filterProducts);
			$wheres[]	= 'p.id IN (' . $productIds . ')';
		}

		if ((int) $limitCount > -1)
		{
			$wheres[] = " m.count_products > " . (int) $limitCount;
		}

		$q = ' SELECT DISTINCT ' . $columns
			. ' FROM  #__phocacart_manufacturers AS m'
			. (!empty($lefts) ? ' LEFT JOIN ' . implode(' LEFT JOIN ', $lefts) : '')
			. (!empty($wheres) ? ' WHERE ' . implode(' AND ', $wheres) : '')
			// .' GROUP BY '.$groups
			. ' ORDER BY ' . $orderingText;

		$db->setQuery($q);

		$items = $db->loadObjectList();

		return $items;
	}

	public static function getManufacturers($itemId, $select = 0)
	{

		$db = JFactory::getDBO();

		if ($select == 1)
		{
			$query = 'SELECT a.id';
		}
		elseif ($select == 2)
		{
			$query = 'SELECT a.id, a.alias ';
		}
		else
		{
			$query = 'SELECT a.id, a.title, a.alias, a.type, a.display_format';
		}

		$query .= ' FROM #__phocacart_manufacturers AS a'
				. ' LEFT JOIN #__phocacart_products AS p ON a.id = p.manufacturer_id'
				. ' WHERE p.id = ' . (int) $itemId
				. ' ORDER BY a.id';
		$db->setQuery($query);

		if ($select == 1)
		{
			$tags = $db->loadColumn();
		}
		else
		{
			$tags = $db->loadObjectList();
		}

		return $tags;
	}

	public static function getManufacturersByIds($cids)
	{

		$db = JFactory::getDBO();

		if ($cids != '')
		{
			// Cids is string separated by comma
			$query = 'SELECT a.id FROM #__phocacart_manufacturers AS a'
				. ' LEFT JOIN #__phocacart_products AS p ON a.id = p.manufacturer_id'
				. ' WHERE p.id IN (' . $cids . ')'
				. ' ORDER BY a.id';

			$db->setQuery($query);
			$tags = $db->loadColumn();
			$tags = array_unique($tags);

			return $tags;
		}

		return array();
	}

	public static function getManufacturerRendered($id, $title, $alias, $manufacturerAlias, $type = 1, $catId = 0, $catAlias = '')
	{
		if ($type == 1)
		{
			$link = PhocacartRoute::getItemsRoute();
			$link = $link . PhocacartRoute::getItemsRouteSuffix($manufacturerAlias, $id, $alias);

			return '<a href="' . JRoute::_($link) . '" >' . $title . '</a>';
		}
		else
		{
			return $title;
		}
	}

	public static function getActiveManufacturers($items, $ordering, $manufacturerAlias = 'manufacturer')
	{

		$db     = JFactory::getDbo();
		$o      = array();
		$wheres = array();
		$ordering = PhocacartOrdering::getOrderingText($ordering, 4);// M

		if ($items != '')
		{
			$wheres[] = 'm.id IN (' . $items . ')';
			$q = 'SELECT DISTINCT m.title, CONCAT(m.id, \'-\', m.alias) AS alias, ' . $db->quote($manufacturerAlias) . ' AS parameteralias, ' . $db->quote(ucfirst($manufacturerAlias)) . ' AS parametertitle FROM #__phocacart_manufacturers AS m'
				. (!empty($wheres) ? ' WHERE ' . implode(' AND ', $wheres) : '')
				. ' GROUP BY m.alias, m.title'
				. ' ORDER BY ' . $ordering;

			$db->setQuery($q);
			$o = $db->loadAssocList();
		}

		return $o;
	}

}

