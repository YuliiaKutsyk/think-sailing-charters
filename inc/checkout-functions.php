<?php

//Checkout fields
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['account']['account_username']);
    unset($fields['account']['account_password']);
    unset($fields['account']['account_password-2']);
    $fields['order']['order_comments']['placeholder'] = 'This field is optional …';
    return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'misha_checkout_fields_styling', 9999 );
function misha_checkout_fields_styling( $f ) {
    $f['billing']['billing_email']['class'][0] = 'form-row-last';
    $f['billing']['billing_phone']['class'][0] = 'form-row-first';
    return $f;
}

// add fields
add_action( 'woocommerce_before_checkout_billing_form', 'woocommerce_form_field_radio' );
function woocommerce_form_field_radio( $checkout ) {
    $service_name = '';
    foreach( WC()->cart->get_cart() as $cart_item ) {
        $variation_id = $cart_item['data']->get_id();
        $product = wc_get_product($variation_id);
        if($product->is_type('variation')) {
            $data = sc_gather_data();
            $prices = sc_get_order_totals($data);
            $product->set_price($prices['boat-price']);
            $attrs = wc_get_product_variation_attributes( $variation_id );
            $service_name = $attrs['attribute_pa_service'];
            $service = get_term_by('slug',$service_name,'pa_service');
            $service_id = $service->term_id;
            break;
        }
    }
    $is_dest_enabled = false;
    if($service) {
        $is_dest_enabled = get_field('service_dest', 'term_' . $service_id);
    }
    if($is_dest_enabled) {
        $destinations = get_field('destinations_list', 'term_' . $service_id);
        if( $destinations ){
            $i = 1;
            foreach( $destinations as $destination ){
                $destTitle = get_the_title( $destination->ID );
                woocommerce_form_field( 'destination', array(
                    'id' => 'destination-' . $i,
                    'type'  => 'checkbox',
                    'class' => array('destination-field form-row-first'),
                    'label' => $destTitle,
                ), $checkout->get_value( 'destination' ) );
                $i++;
            }
        }
    }
}

// save fields to order meta
add_action( 'woocommerce_checkout_update_order_meta', 'misha_save_what_we_added' );
function misha_save_what_we_added( $order_id ){

    if( !empty( $_POST['destination'] ) )
        update_post_meta( $order_id, 'destination', sanitize_text_field( $_POST['destination'] ) );


    if( !empty( $_POST['destination'] ) && $_POST['destination'] == 1 )
        update_post_meta( $order_id, 'destination', 1 );

    if( !empty( $_POST['order_service_type'] ) )
        update_post_meta( $order_id, 'order_service_type', sanitize_text_field( $_POST['order_service_type'] ) );

    update_post_meta( $order_id, 'is_food', $_POST['is_food'] );
}

function sc_check_category_in_cart($cat_slug) {
    $cat_in_cart = 0;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        if ( has_term( $cat_slug, 'product_cat', $cart_item['product_id'] ) ) {
            $cat_in_cart = 1;
            break;
        }
    }
    return $cat_in_cart;
}

function sc_get_booking_data($order_id = 0) {
    if(isset($order_id)) {
        $order = wc_get_order($order_id);
    }
    $response = array();
    $booking_data = false;
    if(isset($order) && $order) {
        $response['duration'] = (int)get_post_meta( $order_id, 'sc_duration', true);
        $response['start'] = get_post_meta( $order_id, 'sc_day_f', true);
        $response['end'] = get_post_meta( $order_id, 'sc_endday_f', true);
    } else {
        $response['duration'] = (int)WC()->session->get('s_duration');
        $response['start'] = date_format(new DateTime(WC()->session->get('s_day_f')),"Y-m-d");
        $response['end'] = date_format(new DateTime(WC()->session->get('s_endday_f')),"Y-m-d");
    }
    return $response;
}

function sc_is_booking_in_cart() {
    $is_booking = false;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        if(isset($cart_item['mvvwb_cart_item_key'])) {
            $is_booking = true;
            break;
        }
    }
    return $is_booking;
}

function sc_get_booking_people($order_id = 0) {
    if(isset($order_id)) {
        $order = wc_get_order($order_id);
    }
    $people = [];
    $booking_item = false;
    if(isset($order) && $order) {
        $people['adult'] = (int)get_post_meta( $order_id, 'sc_adult_number', true);
        $people['children'] = get_post_meta( $order_id, 'sc_children_number', true) !== null ? (int)get_post_meta( $order_id, 'sc_children_number', true) : 0;
        $people['infants'] = get_post_meta( $order_id, 'sc_infants_number', true) !== null ? get_post_meta( $order_id, 'sc_infants_number', true) : 0;
        $people['general'] = (int)get_post_meta( $order_id, 'sc_people_number', true);
    } else {
        $people['adult'] = (int)WC()->session->get('adult_number');
        $people['children'] = WC()->session->get('s_children') !== null ? (int)WC()->session->get('s_children') : 0;
        $people['infants'] = WC()->session->get('s_infants') !== null ? (int)WC()->session->get('s_infants') : 0;
        $people['general'] = (int)WC()->session->get('s_people_number');
    }
    return $people;
}

function human_filesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
}

function sc_get_booking_duration($order_id = 0) {
    if(isset($order_id)) {
        $order = wc_get_order($order_id);
    }
    $duration = 0;
    $booking_item = false;
    if(isset($order) && $order) {
        foreach($order->get_items() as $order_item) {
            if($order_item['variation_id']) {
                $item_id = $order_item->get_id();
                $data = $order_item->get_data();
                $booking_data = wc_get_order_item_meta($item_id,'_mvvwb_order_item_key');
                $duration = (int)$booking_data['duration'];
            }
        }
    } else {
// var_dump(WC()->session);
        $duration = (int)WC()->session->get('s_duration');

    }

    return $duration;
}


add_filter( 'woocommerce_cart_item_name', 'ts_product_image_on_checkout', 10, 3 );

function ts_product_image_on_checkout( $name, $cart_item, $cart_item_key ) {

    /* Return if not checkout page */
    if ( ! is_checkout() ) {
        return $name;
    }

    /* Get product object */
    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

    /* Get product thumbnail */
    $thumbnail = $_product->get_image();

    /* Add wrapper to image and add some css */
    $image = '<div class="ts-product-image">'
        . $thumbnail .
        '</div>';

    /* Prepend image to name and return it */
    return $image . $name;

}

// Move coupon and payment to correct place on checkout
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 20 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form');
add_action( 'woocommerce_checkout_shipping', 'woocommerce_checkout_coupon_form', 20);

add_action('woocommerce_email_after_order_table', 'sc_add_woo_email_edit_link', 10, 1 );
function sc_add_woo_email_edit_link($order) {
  $order_id = $order->get_id();
  $link_url = home_url() .'/checkout/order-received/' . $order_id .'/?key=' . $order->get_order_key() . '#edit-open';
  echo '<div style="text-align: center;" align="center"><a class="email-edit-order-btn" target="_blank" href="' . $link_url . '" style="padding: 8px 12px; border: none; border-radius: 4px; background: #23a8cd; font-size: 14px; color: #fff; text-decoration: none; font-weight: bold; display: inline-block; margin-bottom: 20px;" bgcolor="#23a8cd">Edit your order</a></div>';
}

add_action( 'woocommerce_checkout_create_order_line_item', 'save_cart_item_custom_meta_as_order_item_meta', 10, 4 );
function save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {
    if ( isset($values['day_1']) ) {
        $item->update_meta_data( 'day_1', $values['day_1'] );
    }
    if ( isset($values['day_2']) ) {
        $item->update_meta_data( 'day_2', $values['day_2'] );
    }
}

add_filter( 'woocommerce_hidden_order_itemmeta', 'hide_order_item_meta_fields' );
function hide_order_item_meta_fields( $fields ) {
  $fields[] = 'day_1';
  $fields[] = 'day_2';
  return $fields;
}

// Add displaying in order days for multiday products
add_action( 'woocommerce_after_order_itemmeta', 'order_meta_customized_display',10, 3 );
function order_meta_customized_display( $item_id, $item, $product ){
  $day_1 = wc_get_order_item_meta( $item_id, 'day_1', true);
  $day_2 = wc_get_order_item_meta( $item_id, 'day_2', true);
  if($day_1) {
    echo '<b>Day 1: </b> x' . $day_1 . '<br>';
  }
  if($day_2) {
    echo '<b>Day 2: </b> x' . $day_2 . '<br>';
  }
}

// AJAX save destination or added/removed products changed on thankyou page
add_action('wp_ajax_edit_order','sailing_edit_order');
add_action('wp_ajax_nopriv_edit_order','sailing_edit_order');
function sailing_edit_order() {
  $order_id = $_POST['order_id'];
  $order = wc_get_order($order_id);
  if(!$order) {
    wp_die();
  }
  $products = $_POST['added_ids'];
  $destination_current = $_POST['order_destination'];
  $destination_edit = $_POST['edit_destination'];
  $add_q = array();
  $people = sc_get_booking_people($order_id);
  $product_discount = (int)get_field('discount_for_children','option');
  $is_destination = isset($_POST['is_destination']);
  if($is_destination) {
    if(! empty( $_POST['edit_destination'] )) {
      update_post_meta($order_id, 'destination', sanitize_text_field( $_POST['edit_destination'] ));
    }
  } else {
    delete_post_meta($order_id, 'destination');
  }

  // Remove old order items
  foreach ($order->get_items() as $item_id => $item) {
    $product_id = $item->get_product_id();
    if(has_term( 'food', 'product_cat', $product_id )) {
      $order->remove_item( $item_id );
    }
  }
  $food_price = 0;
  if(isset($products)){
    if(count($products) > 0) {
        //Adding new order items
        $added_ids = array();
        foreach($products as $p) {
          $product_id = $p[0];
          array_push($added_ids,$product_id);
          $product = new WC_Product($product_id);
          $key = array_search($product_id,$added_ids);
          $q = $products[$key];
          $q_summ = $q[1] + $q[2];
          $price = (int)$product->get_price();

          $product_total = ($price * $people['adult'] + $price/100 * (100 - $product_discount) * $people['children']) * $q_summ;
          $food_price += $product_total;
          if(count($p) > 1) {
            $summ_q = (int)$p[1] + (int)$p[2];
            $order->add_product($product,$summ_q,[
                'subtotal'     => $product_total,
                'total'        => $product_total,
            ]);
          } else {
            $order->add_product($product,1,[
                'subtotal'     => $product_total,
                'total'        => $product_total,
            ]);
          }

        }
        // wp_die(json_encode($added_ids));
        // Adding meta data to order items (days)
        foreach ($order->get_items() as $item_id => $item) {
          $product_id = $item->get_product_id();
          if(has_term( 'food', 'product_cat', $product_id )) {

            $key = array_search($product_id,$added_ids);
            $q = $products[$key];
            if(count($q) > 1) {
              $item->update_meta_data('day_1', $q[1]);
              $item->update_meta_data('day_2', $q[2]);
            }
          }
        }
    }
  }

  $order->calculate_totals();
  $data = sc_gather_data($order_id);
  $prices = sc_get_order_totals($data);
  $order->set_total( $prices['payable'] );
  $order->save();
  update_post_meta( $order_id, 'sc_general_total', $prices['boat-price'] + $prices['cleaning-price'] + $food_price );

  $email_new_order = WC()->mailer()->get_emails()['WC_Email_New_Order'];
  $old_subject = $email_new_order->get_option('subject');
  $old_title = $email_new_order->get_option('heading');
  $email_new_order->update_option('subject',"Order #" . $order_id . " was updated");
  $email_new_order->update_option('heading',"Order #" . $order_id . " was updated");
  $email_new_order->trigger( $order_id );
  $email_new_order->update_option('subject',$old_subject);
  $email_new_order->update_option('heading',$old_title);

  $customer_email = WC()->mailer()->get_emails()['WC_Email_Customer_Processing_Order'];
  $old_subject = $customer_email->get_option('subject');
  $old_title = $customer_email->get_option('heading');
  $customer_email->update_option('subject',"Order #" . $order_id . " was updated");
  $customer_email->update_option('heading',"Order #" . $order_id . " was updated");
  $customer_email->trigger( $order_id );
  $customer_email->update_option('subject',$old_subject);
  $customer_email->update_option('heading',$old_title);




  wp_die();
}