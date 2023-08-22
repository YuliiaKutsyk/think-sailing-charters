<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2>
	<?php
	if ( $sent_to_admin ) {
		$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
	} else {
		$before = '';
		$after  = '';
	}
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
	?>
</h2>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$order,
				array(
					'show_sku'      => $sent_to_admin,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => $plain_text,
					'sent_to_admin' => $sent_to_admin,
				)
			);
			?>
			<tr class="order_item">
				<td class="td" style="color: #636363; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word;" align="left">
				Online transaction charge</td>
				<td class="td" style="color: #636363; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" align="left">
				1</td>
				<td class="td" style="color: #636363; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" align="left">
				<?php
				$data = sc_gather_data($order->get_id());
				$prices = sc_get_order_totals($data);
				echo wc_price($prices['cleaning-price']);
				?>
				</td>

			</tr>
			<tr class="order_item">
			<td colspan="3" class="td" style="color: #636363; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" align="left">
				<b>Booking details:</b>
				</td>
			</tr>
			<?php
			$is_food = get_post_meta( $order->get_id(), 'is_food', true );
			$not_sure_menu = get_post_meta( $order->get_id(), 'sc_not_sure_menu', true );
			?>
			<tr>
				<td colspan="3" class="td" style="color: #636363; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" align="left">
					<?php echo '<b>#' . $order->get_id() . '</b><br>';?>
					<?php echo 'Service: ' . get_term_by('term_id', $data['service_id'], 'pa_service')->name . '<br>';?>
					<?php echo 'Adults: ' . $data['adults'] . '<br>';?>
					<?php echo 'Children: ' . (array_key_exists('children', $data) ? $data['children'] : 0) . '<br>';?>
					<?php echo 'Infants: ' . (array_key_exists('infants', $data) ? $data['infants'] : 0) . '<br>';?>
					<?php echo 'Duration: ' . $data['trip_duration'] . '<br>';?>
					<?php if($data['destination']){echo 'Preferred Location: ' . $data['destination'] . '<br>';}?>
					<?php echo 'Start date: ' . $data['trip_start'] . '<br>';?>
					<?php echo 'End date: ' . $data['trip_end'] . '<br>';?>
					<?php echo 'Start/end time: ' . get_field('time_start', 'term_' . $data['service_id']) . ' - ' . get_field('time_end', 'term_' . $data['service_id']) . ' from ' . get_field('checkout_location', 'term_' . $data['service_id']) . '<br>';?>
					<?php echo 'Includes food: ' . ($is_food ? "True" : "False") . '<br>'; ?>
					<?php echo 'Food Undecided: ' . ($not_sure_menu ? "True" : "False") . '<br>'; ?>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<?php
			$item_totals = $order->get_order_item_totals();
    		$deposite_type = get_field('deposite_type','option');
    		$order_total = $order->get_total();
    		$discount = $order->get_total_discount();
    		$new_total = $order->get_subtotal() - $discount;
			$paid_summary = array(
				'label' => 'Paid total:',
				'value' => wc_price($order_total)
			);
			$left_summary = array(
				'label' => 'Pending Amount:',
				'value' => wc_price((float)get_post_meta( $order->get_id(), 'sc_general_total', true) - $order_total)
			);
			$total_summary = array_pop($item_totals);
			$total_summary['value'] = wc_price($new_total);
			$item_totals['order_paid'] = $paid_summary;
			unset($item_totals['order_total']);
			unset($item_totals['cart_subtotal']);
			$item_totals['order_left'] = $left_summary;

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					$i++;
					?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
				<?php
			}
			if ( $food_notes = get_post_meta($order->get_id(),'menu_note', true) ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Food Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( $food_notes) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>

</div>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<div>
		<p><b>Boarding Marina: </b><?php echo get_field('checkout_location', 'term_' . $data['service_id']); ?> Marina</p>
		<p><b>Pontoon: </b><?php echo get_field('pontoon', 'option'); ?></p>
		<p><b>Yacht Name: </b>
		<?php
		$variation = wc_get_product($data['variation_id']);
		echo $variation->get_title();
		?>
		</p>
		<?php echo get_field('new_order_text', 'option'); ?>
	</div>
