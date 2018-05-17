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

class PhocacartReview
{
	public static function getReviewsByProduct($productId) {
		$db = JFactory::getDBO();
		if ((int)$productId > 0) {
			
			$columns		= 'a.id, a.product_id, a.user_id, a.name, a.rating, a.review';
			$groupsFull		= $columns;
			$groupsFast		= 'a.id';
			$groups			= PhocacartUtilsSettings::isFullGroupBy() ? $groupsFull : $groupsFast;
			
			
			$query = 'SELECT '.$columns
					   .' FROM #__phocacart_reviews AS a'
					   .' WHERE a.product_id = '.(int) $productId
					   .' AND a.published = 1'
					   .' GROUP BY '.$groups;
			$db->setQuery($query);

			$reviews = $db->loadObjectList();
			
			return $reviews;
		}
		return false;
	}
	
	public static function addReview( &$error, $approveReview, $productId, $userId, $userName, $rating, $review) {
	
		if ((int)$productId > 0 && (int)$userId > 0 && $userName != '' && (int)$rating > 0 && $review != '') {
			$db = JFactory::getDBO();
			
			$published		= 0;
			if ($approveReview == 0) {
				$published = 1;
			}
			
			// Check if user added some review to the product
			$query = 'SELECT a.id FROM #__phocacart_reviews AS a'
				   .' WHERE a.product_id = '.(int) $productId
				   .' AND a.user_id = '.(int)$userId
				   .' ORDER BY a.id';
			$db->setQuery($query);
			$reviewed = $db->loadColumn();
			if (!empty($reviewed)) {
				$error = 1;
				return false;
			}
			
			
			$query = ' INSERT INTO #__phocacart_reviews (product_id, user_id, name, rating, review, published)'
			.' VALUES ('
			.(int)$productId.', '
			.(int)$userId.', '
			.$db->quote(strip_tags($userName)).', '
			.(int)$rating.', '
			.$db->quote(strip_tags($review)).', '
			.(int)$published.')';

			$db->setQuery($query);
			$db->execute();	
			return true;
		} else {
			$error = 2;
			return false;
		}
	}
}