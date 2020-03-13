<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
JLoader::registerPrefix('Phocacart', JPATH_ADMINISTRATOR . '/components/com_phocacart/libraries/phocacart');

class JFormFieldPhocacartOrderstatus extends JFormField
{
	protected $type 		= 'PhocacartOrderstatus';

	protected function getInput() {
		
		$javascript	= '';
		$required	= ((string) $this->element['required'] == 'true') ? TRUE : FALSE;
		$multiple	= ((string) $this->element['multiple'] == 'true') ? TRUE : FALSE;
		$attr		= '';
		$attr		.= 'class="inputbox" ';
		if ($multiple) {
			$attr		.= 'size="4" multiple="multiple" ';
		}
		if ($required) {
			$attr		.= 'required aria-required="true" ';
		}
		$attr		.= $javascript . ' ';
		
		if ($multiple) {
			
			$db = JFactory::getDBO();
			
			$query = 'SELECT a.title AS text, a.id AS value'
			. ' FROM #__phocacart_order_statuses AS a'
			. ' WHERE a.published = 1'
			. ' ORDER BY a.ordering';
			$db->setQuery( $query );
			$data = $db->loadObjectList();
			if (!empty($data)) {
				foreach ($data as $k => $v) {
					$data[$k]->text = JText::_($v->text);
				}
			}
			array_unshift($data, JHtml::_('select.option', '0', JText::_('COM_PHOCACART_NONE'), 'value', 'text'));
			array_unshift($data, JHtml::_('select.option', '-1', JText::_('COM_PHOCACART_ALL'), 'value', 'text'));
			return JHtml::_('select.genericlist',  $data,  $this->name, $attr, 'value', 'text', $this->value, $this->id );
			
		} else {
			$id = (int) $this->form->getValue('status_id');
			
			if ($id < 1) {
				$id = 1;// set default "pending"
			}
			
			$status = PhocacartOrderStatus::getStatus($id);
			//array_unshift($status['data'], JHtml::_('select.option', '', '- '.JText::_('COM_PHOCACART_SET_STATUS').' -', 'value', 'text'));
			return JHtml::_('select.genericlist',  $status['data'],  $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id );
		}
	}
}
?>