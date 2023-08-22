<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">

	<div class="checkbox_titles">
		<div class="checkbox-titles_wrap">
			<h4 class="checkout-step_title"><?php the_field('discount_code_title'); ?></h4>
			<p><?php the_field('discount_code_undertitle'); ?></p>
		</div>
	</div>

	<div class="coupon-inner">
		<div class="coupon-wrap">
		  <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" />
		  <button type="submit" class="button" id='coupon-button' name="apply_coupon" value="<?php esc_attr_e( 'Apply code', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply code', 'woocommerce' ); ?></button>
		  <div class="coupon-congrats">Congrats! <span class="coupon-percentage_reciever"></span></div>
		  <div class="coupon-failed">Wrong coupon code or its date has expired</div>
	    </div>
	</div>
	
	<div class="clear"></div>
</form>
