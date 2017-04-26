<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
require_once JPATH_COMPONENT.'/controllers/phocacartcommons.php';
class PhocaCartCpControllerPhocaCartEditStockAdvanced extends PhocaCartCpControllerPhocaCartCommons
{
	public function &getModel($name = 'PhocaCartEditStockAdvanced', $prefix = 'PhocaCartCpModel', $config = array()) {
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	function save() {
	
		if (!JSession::checkToken('request')) {
			$app->enqueueMessage('Invalid Token', 'message');
			return false;
		}
		
		$app					= JFactory::getApplication();
		$jform					= $app->input->get('jform', array(), 'array');
		$id						= $app->input->get('id', 0, 'int');
		

		if (!empty($jform)) {
			$model = $this->getModel( 'phocacarteditstockadvanced' );
			if(!$model->save($jform, $id)) {
				$message = JText::_( 'COM_PHOCACART_ERROR_ADD_STOCK_QUANTITY' );
				$app->enqueueMessage($message, 'error');
			} else {
				$message = JText::_( 'COM_PHOCACART_SUCCESS_ADD_STOCK_QUANTITY' );
				$app->enqueueMessage($message, 'message');
			}
			$app->redirect('index.php?option=com_phocacart&view=phocacarteditstockadvanced&tmpl=component&id='.(int)$id);
		} else {
		
			$app->enqueueMessage(JText::_('COM_PHOCACART_NO_ITEM_FOUND'), 'error');
			$app->redirect('index.php?option=com_phocacart&view=phocacarteditstockadvanced&tmpl=component');
		}
	}
	
}
?>