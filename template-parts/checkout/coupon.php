<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
	<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
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
	<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
		<td class="coupon-input" colspan="4"><?php wc_cart_totals_coupon_label( $coupon ); ?></td>
	</tr>
<?php endforeach; ?>