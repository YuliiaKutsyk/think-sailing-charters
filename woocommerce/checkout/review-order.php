<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

	defined( 'ABSPATH' ) || exit;
	$service_name = '';
	$service_title = '';
	$var_price = 0;
	$booking_duration = sc_get_booking_duration();
	$booking_duration = $booking_duration ? $booking_duration : 1;
	foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	  $variation_id = $cart_item['data']->get_id();
	  $product = wc_get_product($variation_id);
	  if($product->is_type('variation')) {
		$attrs = wc_get_product_variation_attributes( $variation_id );
		$service_name = $attrs['attribute_pa_service'];
		$var_product = new WC_Product_Variation($variation_id);
	  	$var_price = $cart_item['line_subtotal'];
		break;
	  }
	}
	if(!empty($service_name)) {
	  $service = get_term_by('slug',$service_name,'pa_service');
      $food_cats = get_field('services_menus',$service->term_id);
	  $service_title = $service->name;
	}

	$deposite_type = get_field('deposite_type','option');
	if($deposite_type) {
	  $deposit_price = (float)get_field('deposit_price','option');
	  if($booking_duration > 1) {
	  	$deposit_price *= $booking_duration;
	  }
	} else {
	  $deposit_percentage = (int)get_field('deposit_percentage','option') / 100;
	  $deposit_price += $var_price * $deposit_percentage;
	}
?>
<table class="shop_table woocommerce-checkout-review-order-table" data-currency="<?php echo get_woocommerce_currency_symbol(); ?>">
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );
  		$cleaning_id = get_field('cleaning_product_id','option');
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = $_product->get_id();
			if ( $_product && $_product->exists() && $product_id != $cleaning_id && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>

				<tbody class="order-details_wrapper">
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<td class="product-name" colspan="3">
							<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>
					</tr>
					<tr class="product-details_title">
						<td colspan="2">Order details</td>
					</tr>
					<tr class="product-details_service">
						<td colspan="2">
							<span class="charter-type_recieve"><?php echo $service_title; ?></span>
						</td>
					</tr>
                    <?php
                        $people = sc_gather_data();
                    ?>
                    <tr class="product-details_service">
                        <?php
                        $display_time_start = new DateTime(get_field('time_start', 'term_' . $service->term_id));
                        $display_time_end = new DateTime(get_field('time_end', 'term_' . $service->term_id));
                        ?>
                        <td colspan="2">
                            <?php
                                $trip_start = $people['trip_start'];
                                $trip_end = $people['trip_end'];
                                $formatted_trip_start = date("F j Y", strtotime($trip_start));

                                if (isset($trip_end) && !empty($trip_end) && $trip_start != $trip_end) {
                                    $formatted_trip_end = date("F j Y", strtotime($trip_end));
                                    echo $formatted_trip_start . " - " . $formatted_trip_end;
                                } else {
                                    echo $formatted_trip_start;
                                }
                            ?>
                        </td>
                    </tr>
					<tr class="product-details_time">
                        <?php
                        $display_time_start = new DateTime(get_field('time_start', 'term_' . $service->term_id));
                        $display_time_end = new DateTime(get_field('time_end', 'term_' . $service->term_id));
                        ?>
						<td colspan="2">
                            <span class="date-reciever"></span>
                            <span class="month-reciever"></span>
                            <span class="endDate-reciever">· </span>
                            <span class="endMonth-reciever"></span>
                            <?php
                                $checkout_location = get_field('checkout_location', 'term_' . $service->term_id);

                                if (!empty($checkout_location)) {
                                    echo $display_time_start->format('hA') . ' · ' . $display_time_end->format('hA') . " from " . $checkout_location;
                                } else {
                                    echo $display_time_start->format('hA') . ' · ' . $display_time_end->format('hA');
                                }
                            ?>
                        </td>
					</tr>
					<tr class="product-details_title">
						<td colspan="3">People</td>
					</tr>
					<tr class="product-details_time">
						<td colspan="3">
							<?php if(intval($people['adults']) > 0) { ?>
								<span class="adults-charter_number"><?php echo $people['adults']; ?> Adult(s)</span>
							<?php } ?>
							<?php if(intval($people['children']) > 0) { ?>
								<span class="children-charter_number"><?php echo $people['children']; ?> Children(s)</span>
							<?php } ?>
							<?php if(intval($people['infants']) > 0) { ?>
								<span class="infants-charter_number"><?php echo $people['infants']; ?> Infant(s)</span>
							<?php } ?>
						</td>
					</tr>
					<?php
						$food_in_cart = false;
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							if ( has_term( 'food', 'product_cat', $cart_item['product_id'] ) ) {
								$food_in_cart = true;
								break;
							}
						}
					?>
						<tr class="product-details_title food" <?php echo !$food_in_cart ? 'style="display: none;"' : ''; ?>>
							<td colspan="2">Food</td>
						</tr>
						<tr class="checkout-food-rows">
					<?php
						if($food_in_cart) {
					?>
						<td>
						<?php
							foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
								if ( has_term( 'food', 'product_cat', $cart_item['product_id'] ) ) {
									$product_id = $cart_item['product_id'];
									$product = $cart_item['data'];
									$product_q = $cart_item['quantity'];
									$price = $product->get_price();
									$adult_q = $people['adults'];
									$children_q = $people['children'];
									$q = $adult_q + $children_q;
									$title = $product->get_title();
									$days = intval($cart_item['day_1']) + intval($cart_item['day_2']);
									$days = $days > 0 ? $days : 1;
						?>
							<tr class="product-details_food">
								<td colspan="2"><span class="q-text"><?php echo $q * $days . 'x'; ?></span> <?php echo $title; ?></td>
								<?php $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key ); ?>
								<td class="servise-price_order coupon-price_full"><?php echo WC()->cart->get_product_subtotal( $product, 1 ); ?></td>
							</tr>
						<?php }} ?></td>
					<?php } ?>
					</tr>
					<tr class="product-details_title">
						<td colspan="2"><?php
						$prices = sc_get_order_totals_checkout();
						?></td>
					</tr>
					<tr class="product-details_service boat-price" data-price="<?php echo round($prices['boat-price']); ?>">
						<td colspan="2">Charter (+ tax)</td>
                        <?php
                            $price = intval($prices['boat-price']);
                            $price_formatted = '€ ' . number_format($price, 0, '', ' ');
                        ?>
						<td class="servise-price_order coupon-price_full"><?php echo $price_formatted; ?></td>
					</tr>

					<tr class="product-details_service deposite">
						<td colspan="2">Deposit</td>
						<td class="servise-price_order">
                            <?php
                                $price = $prices['deposite-price'];
                                $formatted_price = '€ ' .number_format($price, 0, ',', ' ');
                            ?>
                            <?php echo $formatted_price; ?>
						</td>
					</tr>

					<tr class="product-details_service">
						<td colspan="2">Online transaction charge</td>
                        <?php
                            $price = $prices['cleaning-price'];
                            $formatted_service_price = '€ ' .number_format($price, 0, ',', ' ');
                        ?>
						<td class="servise-price_order" colspan="2"><?php echo $formatted_service_price; ?></td>
					</tr>

					<div class="coupon-block"></div>
					<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
						<tr class="cart-discount">
							<td class="coupon-generic" colspan="2"><?php esc_html_e( 'Discount', 'woocommerce' ); ?></td>
							<td class="coupon-titles" colspan="2">
								<span class="coupon-percentage_reciever">
									<span class="coupon-reciever_calc"></span>
									<span class="price-reciever_calc"></span>
								</span>
								<?php

								wc_cart_totals_coupon_html( $coupon );
								 ?>
							</td>

						</tr>
						<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
							<td class="coupon-input" colspan="4"><?php wc_cart_totals_coupon_label( $coupon ); ?></td>
						</tr>
					<?php endforeach; ?>

					<tr class="product-details_service payable">
						<td colspan="2">Amount Payable Now</td>
                        <?php
                            $price = WC()->cart->total;
                            $total_payable_formatted = '€ ' .number_format($price, 0, ',', ' ');
                        ?>
						<td class="servise-price_order">
                            <?php echo $total_payable_formatted; ?>
						</td>
					</tr>
				</tbody>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	<tfoot>



		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Balance', 'woocommerce' ); ?></th>
			<td colspan="2" class="total-td">
                <?php
                    $order_total = WC()->session->get('general_total') - WC()->cart->total;
                    $order_total_formatted = '€ ' . number_format($order_total, 0, ',', ' ');
                    echo $order_total_formatted;
                ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		<tr>
			<td id="separator-order">Hello</td>
		</tr>
		<tr class="checkout-undertitle">
			<td class="checkout-undertitle_info checkout-undertitle_info--order" colspan="3" id="checkout-undertitle_info--order">You will only be charged the deposit fee and the online transaction charge. The Balance will be paid on the day of the charter.</td>
		</tr>
	</tfoot>
</table>
<div class="page-bottom_message sidebar-message checkout-sidebar_message">
	<a href="mailto:<?php the_field('email', 'options'); ?>" class="message-email"><?php the_field('email', 'options'); ?></a>
	<a href="tel:<?php the_field('phone', 'options'); ?>" class="message-phone"><?php the_field('phone', 'options'); ?></a>
</div>