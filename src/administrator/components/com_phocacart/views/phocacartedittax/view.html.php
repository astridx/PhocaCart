<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.view' );
 
class PhocaCartCpViewPhocaCartEditTax extends JViewLegacy
{
	protected $t;
	protected $item;
	protected $itemhistory;
	protected $id;
	protected $type;
	function display($tpl = null) {
		
		$app				= JFactory::getApplication();
		$this->id			= $app->input->get('id', 0, 'int');
		$this->type			= $app->input->get('type', 1, 'int');// 1 country, 2 region
		
		if ($this->type == 1) {
			$this->t				= PhocacartUtils::setVars('country');
			$this->item				= $this->get('Data');
			$this->itemcountrytax	= $this->get('CountryTaxData');
		} else {
			$this->t				= PhocacartUtils::setVars('region');
			$this->item				= $this->get('Data');
			$this->itemcountrytax	= $this->get('RegionTaxData');
		}
		
		
		

		$media = new PhocacartRenderAdminmedia();
	
		parent::display($tpl);
	}
}
?>