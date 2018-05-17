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

class PhocaCartCpModelPhocacartExtensions extends JModelList
{
	protected $option 	= 'com_phocacart';	
	
	public function __construct($config = array())
	{

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		$categoryId = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', 'modules');
		$this->setState('filter.category_id', $categoryId);

		$params = JComponentHelper::getParams('com_phocacart');
		$this->setState('params', $params);
		
		//parent::populateState('a.date', 'desc');
	}
	
	protected function getStoreId($id = '')
	{
		$id	.= ':'.$this->getState('filter.category_id');
		return parent::getStoreId($id);
	}
	
	public function getItems() {
		
		$app = JFactory::getApplication('administrator');
		
		$items	= null;
		$type	= $this->getState('filter.category_id', 'modules');
		$url	= PhocacartUtilsSettings::getExtenstionsJSONLinks($type);
		
		$items  = $app->getUserState('com_phocacart.getExtensions.'.$type, null);
		
		if ($items === null) {
			$items = array();
			
			if ($url) {
				$content = @file_get_contents($url);
				
				if (!$content || $content == '') {
					$content = PhocacartUtils::curl_get_contents($url);
				}
				
				if ($content) {
					$contentJSON = json_decode($content, true);
					if ($contentJSON && json_last_error() == JSON_ERROR_NONE && !empty($contentJSON['extensions'])) {
						$items = $contentJSON['extensions'];
					}
				} else {
					$msg = JText::_('COM_PHOCACART_ERROR_EXTENSIONS_CHANNEL_NOT_FOUND'). ' ('.ucfirst($type).')';
					$app->enqueueMessage($msg, 'error');
				}
			}
			
			$app->setUserState('com_phocacart.getExtensions.'.$type, $items);
		} else if (empty($items)) {
			$msg = JText::_('COM_PHOCACART_ERROR_NO_EXTENSION_FOUND'). ' ('.ucfirst($type).')';
			$app->enqueueMessage($msg, 'error');
		}
		
		return $items;
	}
	
	public function getNews() {
		
		$app = JFactory::getApplication('administrator');
		
		$news	= null;
		$type	= 'news';
		$url	= PhocacartUtilsSettings::getExtenstionsJSONLinks($type);
		
		$news  = $app->getUserState('com_phocacart.getNews.'.$type, null);
		
		if ($news === null) {
			$news = array();
			
			if ($url) {
				$content = @file_get_contents($url);
				
				if (!$content || $content == '') {
					$content = PhocacartUtils::curl_get_contents($url);
				}
				
				if ($content) {
					$contentJSON = json_decode($content, true);
				
					if ($contentJSON && json_last_error() == JSON_ERROR_NONE && !empty($contentJSON['content'])) {
						$news = $contentJSON['content'];
					}
				} else {
					//$msg = JText::_('COM_PHOCACART_ERROR_NEWS_CHANNEL_NOT_FOUND'). ' ('.ucfirst($type).')';
					//$app->enqueueMessage($msg, 'error');
				}
			}
			
			$app->setUserState('com_phocacart.getNews.'.$type, $news);
		} else if (empty($news)) {
			//$msg = JText::_('COM_PHOCACART_ERROR_NO_NEWS_ENTRY_FOUND'). ' ('.ucfirst($type).')';
			//$app->enqueueMessage($msg, 'error');
		}
		
		return $news;
	}
}
?>