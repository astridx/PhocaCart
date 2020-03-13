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

class PhocacartCartRendercheckout extends PhocacartCart
{
	protected $fullitems;

	protected $total;

	public $params;


	public function __construct()
	{

		$this->setInstance(2);// Checkout
		parent::__construct();
	}


	public function render()
	{

		$app					= JFactory::getApplication();
		$s                      = PhocacartRenderStyle::getStyles();
		$pC 					= PhocacartUtils::getComponentParameters();
		$uri 					= \Joomla\CMS\Uri\Uri::getInstance();
		$url['action']			= $uri->toString();
		$url['actionbase64']	= base64_encode($url['action']);
		$pos					= PhocacartPos::isPos();

		if ($pos)
		{
			$url['linkcheckout']	= JRoute::_(PhocacartRoute::getPosRoute());
		}
		else
		{
			$url['linkcheckout']	= JRoute::_(PhocacartRoute::getCheckoutRoute());
		}

		if (empty($this->fullitems))
		{
			$this->fullitems = $this->getFullItems();// Get them from parent

			// SUBTOTAL
			if (empty($this->total))
			{
				$this->total = $this->getTotal();
			}

			// COUPONTITLE
			if (empty($this->coupon['title']))
			{
				$this->coupon['title'] = $this->getCouponTitle();
			}
		}

		$layout 				= new JLayoutFile('cart_checkout', null, array('component' => 'com_phocacart'));
		$d						= array();
		$d['s']			        = $s;
		$d['params']			= $pC;
		$d['fullitems']			= $this->fullitems;
		$d['total']				= $this->total;
		$d['fullitemsgroup']	= $this->fullitemsgroup;
		$d['coupontitle']		= $this->coupon['title'];
		$d['couponvalid']		= $this->coupon['valid'];
		$d['shippingcosts']		= $this->shipping['costs'];
		$d['paymentcosts']		= $this->payment['costs'];
		$d['action']			= $url['action'];
		$d['actionbase64']		= $url['actionbase64'];
		$d['linkcheckout']		= $url['linkcheckout'];
		$d['pathitem'] 			= PhocacartPath::getPath('productimage');
		$d['pos']				= $this->pos;
		$d['ticketid']			= (int) $this->ticket->id;
		$d['unitid']			= (int) $this->unit->id;
		$d['sectionid']			= (int) $this->section->id;

		return $layout->render($d);
	}
}

