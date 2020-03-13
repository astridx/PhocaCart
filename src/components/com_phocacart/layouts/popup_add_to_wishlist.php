<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
$d = $displayData;
$d['wishlist_view_href'] = 'data-dismiss="modal"';
$d['close']                 = '<button type="button" class="close" aria-label="'.JText::_('COM_PHOCACART_CLOSE').'" '. $d['wishlist_view_href'].' ><span aria-hidden="true">&times;</span></button>';
if (isset($d['wishlist_view']) && $d['wishlist_view'] == 1) {
	$d['wishlist_view_href'] = 'href="'.$d['link_wishlist'].'"';
	$d['close']             = '<a role="button" class="close" aria-label="'.JText::_('COM_PHOCACART_CLOSE').'" '. $d['wishlist_view_href'].' ><span aria-hidden="true">&times;</span></a>';
}
?>
<div id="phAddToWishListPopup" class="<?php echo $d['s']['c']['modal.zoom'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="<?php echo $d['s']['c']['modal-dialog'] ?>">
	  <div class="<?php echo $d['s']['c']['modal-content'] ?>">
		<div class="<?php echo $d['s']['c']['modal-header'] ?>">
		  <?php echo $d['s']['c']['class-type'] != 'bs4' ? $d['close'] : '' ?>
		  <h4><span class="<?php echo $d['s']['i']['info-sign'] ?>"></span> <?php echo $d['info_msg'] ?></h4>
			<?php echo $d['s']['c']['class-type'] == 'bs4' ? $d['close'] : '' ?>
		</div>
		<div class="<?php echo $d['s']['c']['modal-body'] ?>">

			<?php if (isset($d['info_msg_additional']) && $d['info_msg_additional'] != '') { ?>
			<div><?php echo $d['info_msg_additional']; ?></div>
		  <?php } ?>

		<div class="<?php echo $d['s']['c']['row'] ?>">
<div class="<?php echo $d['s']['c']['col.xs12.sm6.md6'] ?> ph-center">
<a class="<?php echo $d['s']['c']['btn.btn-primary'] ?> ph-btn" role="button" <?php echo $d['wishlist_view_href']; ?> ><span class="<?php echo $d['s']['i']['shopping-cart'] ?>"></span> <?php echo JText::_('COM_PHOCACART_CONTINUE_SHOPPING'); ?></a>
</div>

<div class="<?php echo $d['s']['c']['col.xs12.sm6.md6'] ?> ph-center">
<a class="<?php echo $d['s']['c']['btn.btn-success'] ?> ph-btn" role="button" href="<?php echo $d['link_wishlist']; ?>" ><span class="<?php echo $d['s']['i']['int-link'] ?>"></span> <?php echo JText::_('COM_PHOCACART_PROCEED_TO_WISH_LIST'); ?></a>
</div>
		</div>
		</div>
		<div class="<?php echo $d['s']['c']['modal-footer'] ?>"></div>
	   </div>
	</div>
</div>


