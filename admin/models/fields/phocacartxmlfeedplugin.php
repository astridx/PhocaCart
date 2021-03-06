<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class JFormFieldPhocaCartXmlFeedPlugin extends JFormField
{
	protected $type 		= 'PhocaCartXmlFeedPlugin';

	protected function getInput() {

		//$document = JFactory::getDocument();
		//$id		= $this->form->getValue('id');
		//$method	= $this->form->getValue('method');
		$plugins = PhocacartFeed::getFeedPluginMethods('', 1);

		return JHtml::_('select.genericlist',  $plugins,  $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id );
	}
}
?>
