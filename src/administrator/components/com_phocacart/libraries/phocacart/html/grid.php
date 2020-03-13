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

if (! class_exists('JHtmlGrid'))
{
	require_once JPATH_SITE . '/libraries/joomla/html/html/grid.php';
}

// Jimport('joomla.html.html.jgrid');
class PhocacartHtmlGrid extends JHtmlJGrid
{

}
