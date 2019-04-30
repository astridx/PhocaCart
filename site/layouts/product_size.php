<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
$d  = $displayData;
$size = new PhocacartSize();


// Size
$length     = $size->getSizeFormat($d['length']);
$width      = $size->getSizeFormat($d['width']);
$height     = $size->getSizeFormat($d['height']);
if ($length || $width || $height ) {
    echo '<h4 class="ph-header-size">' . JText::_('COM_PHOCACART_HEADER_SIZE') . '</h4>';

    echo '<div class="ph-box-size row">';
    if ($length) {
        echo '<div class="col-md-6 col-sm-6">' . JText::_('COM_PHOCACART_LENGTH') . ':</div>';
        echo '<div class="col-md-6 col-sm-6">' . $length . '</div>';
    }
    if ($width) {
        echo '<div class="col-md-6 col-sm-6">' . JText::_('COM_PHOCACART_WIDTH') . ':</div>';
        echo '<div class="col-md-6 col-sm-6">' . $width . '</div>';
    }
    if ($height) {
        echo '<div class="col-md-6 col-sm-6">' . JText::_('COM_PHOCACART_HEIGHT') . ':</div>';
        echo '<div class="col-md-6 col-sm-6">' . $height . '</div>';
    }
    echo '</div>';
}

// Weight
$weight     = $size->getSizeFormat($d['weight'], 'weight');
if ($weight) {
    echo '<h4 class="ph-header-size">' . JText::_('COM_PHOCACART_HEADER_WEIGHT') . '</h4>';

    echo '<div class="ph-box-size row">';
    echo '<div class="col-md-6 col-sm-6">' . JText::_('COM_PHOCACART_WEIGHT') . ':</div>';
    echo '<div class="col-md-6 col-sm-6">' . $weight . '</div>';
    echo '</div>';
}

// Volume
$volume     = $size->getSizeFormat($d['volume'], 'volume');
if ($volume) {
    echo '<h4 class="ph-header-size">' . JText::_('COM_PHOCACART_HEADER_VOLUME') . '</h4>';

    echo '<div class="ph-box-size row">';
    echo '<div class="col-md-6 col-sm-6">' . JText::_('COM_PHOCACART_VOLUME') . ':</div>';
    echo '<div class="col-md-6 col-sm-6">' . $volume . '</div>';
    echo '</div>';
}

?>
