<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die();
jimport('joomla.form.formfield');

class JFormFieldPhocaSelectFilename extends JFormField
{
	public $type = 'PhocaSelectFileName';

	protected function getInput() {
		$html 			= array();
		$managerOutput	= $this->element['manager'] ? '&amp;manager='.(string)$this->element['manager'] : '';
		$group 			= PhocacartUtilsSettings::getManagerGroup((string) $this->element['manager']);
		$textButton		= 'COM_PHOCACART_FORM_SELECT_'.strtoupper($group['t']);

		// Set variable {field-id} because of dynamically created items
		// {field-id} will be replaced by dynamically created ID of input closest to button which will fire the modal box
		//$link 			= 'index.php?option=com_phocacart&amp;view=phocacartmanager'.$group['c'].$managerOutput.'&amp;field='.$this->id;
		$link 			= 'index.php?option=com_phocacart&amp;view=phocacartmanager'.$group['c'].$managerOutput.'&amp;field={ph-field-id}';

		$onchange 		= (string) $this->element['onchange'];
		$size     		= ($v = $this->element['size']) ? ' size="' . $v . '"' : '';
		$class    		= ($v = $this->element['class']) ? ' class="' . $v . '"' : 'class="text_area"';
		$required 		= ($v = $this->element['required']) ? ' required="required"' : '';

		if ((string)$this->element['manager'] == 'publicfile') {
			$idA			= 'phPublicFile';
		} else {
			$idA			= 'phProductFile';
		}
		$w				= 700;
		$h				= 400;


		JHtml::_('jquery.framework');

		// 1) phocacartform.js - loads click event to run modal window - NO NEED TO DEFINE EVENT HERE
        // 2) phocacartform.js - creates modal window, then loads the iframe with url - NO NEED TO LOAD MODAL WINDOW AND TO BUILD IT HERE


		$idAC = $idA.'ModalName'. $this->id;

		$html[] = '<span class="input-append"><input type="text" ' . $required . ' id="' . $this->id . '" name="' . $this->name . '"'
			. ' value="' . $this->value . '"' . $size . $class . ' />';
		// data-id does not work by dynamically added form fields so we need to get the id which is stored in input before the button
		$html[] = ' <a href="#'.$idAC.'" role="button" class="btn btn-primary '.$idA.'ModalButton" data-toggle="modal" title="' . JText::_($textButton) . '" data-title="' . JText::_($textButton) . '" data-id="' . $this->id . '" data-src="'.$link.'"  data-height='.$w.' data-width='.$h.'">'
			. '<span class="icon-list icon-white"></span> '
			. JText::_($textButton) . '</a></span>';

		return implode("\n", $html);
	}
}
?>
