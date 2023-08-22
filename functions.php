<?php

defined( 'CUSTOM_URI' )    or define( 'CUSTOM_URI',    get_template_directory_uri() );
defined( 'CUSTOM_T_PATH' ) or define( 'CUSTOM_T_PATH', get_template_directory() );
defined( 'CUSTOM_F_PATH' ) or define( 'CUSTOM_F_PATH', CUSTOM_T_PATH . '/inc' );

require_once CUSTOM_F_PATH . '/helper-functions.php';
require_once CUSTOM_F_PATH . '/action-config.php';
require_once CUSTOM_F_PATH . '/wc-variation-fields.php';
require_once CUSTOM_F_PATH . '/checkout-functions.php';
require_once CUSTOM_F_PATH . '/booking/Booking.php';
require_once CUSTOM_F_PATH . '/order-meta.php';

add_filter( 'woocommerce_new_order_email_allows_resend', '__return_true' );
add_filter( 'woocommerce_price_trim_zeros', '__return_true' );

add_action( 'after_setup_theme', function() {
    if (function_exists('add_theme_support')) {
        add_theme_support( 'woocommerce' );
        add_theme_support('menus');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');

        add_image_size('large', 1200, '', true); // Large Thumbnail
        add_image_size('medium', 600, '', true); // Medium Thumbnail
        add_image_size('small', 250, '', true); // Small Thumbnail
        add_image_size('custom-size', 700, 200, true);
    }
} );

//  Register Menu Navigation
add_action('init', 'register_menu');
function register_menu() {
    register_nav_menus(array(
        'header-menu' => ('Header menu'),
        'sidebar-menu' => ('Sidebar menu'),
        'footer-menu' => ('footer menu')
    ));
}

//ACF Options page
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page();
    acf_add_options_sub_page('Header');
    acf_add_options_sub_page('Footer');
    acf_add_options_sub_page('Configuration');
    acf_add_options_sub_page('Booking');

    acf_add_options_page(array(
        'page_title'  => 'Theme General Settings',
        'menu_title'  => 'Theme Settings',
        'menu_slug'   => 'theme-general-settings',
        'capability'  => 'edit_posts',
        'redirect'    => false
    ));

}

// Menu head navigation
function wpeHeadNav() {
    wp_nav_menu(
        array(
            'theme_location'  => 'header-menu',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul class="headnav">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}
// Menu footer navigation
function wpeFootNav() {
    wp_nav_menu(
        array(
            'theme_location'  => 'footer-menu',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul class="footernav">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}

add_action( 'init', 'create_posttype' );

function create_posttype() {

    register_post_type( 'destination',
        // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Destination' ),
                'singular_name' => __( 'destinations' )
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'destinations'),
            'taxonomies' => array( 'category' ),
            'supports' => array( 'title', 'excerpt', 'editor', 'thumbnail' )
        )
    );
}

add_action( 'init', function(){

    if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // wp_mail( 'yulia@think.mt', 'mail test', 'dfdfadfdafdf');


    // WC()->session->set('sc_booking', $_SESSION);
    // print_r(WC()->session);
    // print_r(WC()->session->get('sc_booking'));
    // print_r($_SESSION);

//    print_r('c--test');
//    var_dump(WC()->cart);

}} );

add_action( 'woocommerce_checkout_update_order_meta', 'sc_save_cart_custom_data_to_order', 10, 4 );
function sc_save_cart_custom_data_to_order( $order_id ) {

    $order = new WC_Order($order_id);
    update_post_meta( $order_id, 'sc_people_number', WC()->session->get('s_people_number') );
    update_post_meta( $order_id, 'sc_service_id', WC()->session->get('s_service_id') );
    update_post_meta( $order_id, 'sc_adult_number', WC()->session->get('adult_number') );
    update_post_meta( $order_id, 'sc_children_number', WC()->session->get('children_number') );
    update_post_meta( $order_id, 'sc_infants_number', WC()->session->get('infants_number') );
    update_post_meta( $order_id, 'sc_day', WC()->session->get('s_day') );
    update_post_meta( $order_id, 'sc_day_f', WC()->session->get('s_day_f') );
    update_post_meta( $order_id, 'sc_endday', WC()->session->get('s_endday') );
    update_post_meta( $order_id, 'sc_endday_f', WC()->session->get('s_endday_f') );
    update_post_meta( $order_id, 'sc_duration', WC()->session->get('s_duration') );
    update_post_meta( $order_id, 'sc_variation_id', WC()->session->get('variation_id') );
    update_post_meta( $order_id, 'sc_service_id', WC()->session->get('service_id') );
    update_post_meta( $order_id, 'sc_general_total', WC()->session->get('general_total') );
    update_post_meta( $order_id, 'menu_note', sanitize_text_field( $_POST['menu_note']));
    update_post_meta( $order_id, 'sc_not_sure_menu', sanitize_text_field( $_POST['is_menu']));

    WC()->session->__unset( 'sc_booking' );
}

// Custom cart calcucations
add_action( 'woocommerce_before_calculate_totals', 'adding_custom_price', 10, 1);
function adding_custom_price( $cart ) {
  if ( is_admin() && ! defined( 'DOING_AJAX' ) )
      return;
    $data = sc_gather_data();
    $boat_total = sc_get_pre_order_total($data);
    foreach ( $cart->get_cart() as $cart_item ) {
        $product = $cart_item['data'];
        $product_id = $product->get_id();
        if ($product_id == WC()->session->get('variation_id') && $product->is_type( 'variation' )) {
            $cart_item['data']->set_price($boat_total);
        }
    }

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
    $product_discount = (int)get_field('discount_for_children','option');
    $cleaning_id = get_field('cleaning_product_id','option');
    $data_session = sc_gather_data();
    $deposit_price = (float)get_field('deposit', 'term_' . $data_session['service_id']);
    $booking_duration = sc_get_booking_duration();
    $booking_duration = $booking_duration ? $booking_duration : 1;
    $contents_total = 0;
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if(has_term('food', 'product_cat', $cart_item['product_id'] )) {
            $price = $cart_item['data']->get_regular_price();
            $adult_q = $data_session['adults'];
            $children_q = $data_session['children'];

            $new_price = $price * $adult_q + $children_q * ($price/100 * (100 - $product_discount));
            // var_dump($cart_item['day_1']);
            // var_dump($cart_item['day_2']);
            if($cart_item['day_1'] == 1 && $cart_item['day_2'] == 1){
                // var_dump($price);
                // var_dump($new_price);
                $new_price = $new_price * 2;
                // var_dump($new_price);
                // var_dump($cart_item['day_1']);
                // var_dump($cart_item['day_2']);
            }
            $cart_item['data']->set_price( $new_price );
            // var_dump($new_price);
        }


        $contents_total = $contents_total + $cart_item['data']->get_price();
    }


    $data = sc_gather_data();
    $cleaning_price = sc_get_order_totals($data)['cleaning-price'];

    // print_r($contents_total);
    WC()->session->set('general_total', $contents_total + $cleaning_price);
    WC()->cart->set_session();
}
// add_action( 'woocommerce_calculate_totals', 'sc_food_children_discount', 10, 1 );
function sc_food_children_discount( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
    $product_discount = (int)get_field('discount_for_children','option');
    $cleaning_id = get_field('cleaning_product_id','option');
    $data_session = sc_gather_data();
    $deposit_price = (float)get_field('deposit', 'term_' . $data_session['service_id']);
    $booking_duration = sc_get_booking_duration();
    $booking_duration = $booking_duration ? $booking_duration : 1;
    $contents_total = 0;
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if(has_term('food', 'product_cat', $cart_item['product_id'] )) {
            $price = $cart_item['data']->get_regular_price();
            $adult_q = $data_session['adults'];
            $children_q = $data_session['children'];

            $new_price = $price * $adult_q + $children_q * ($price/100 * (100 - $product_discount));
            // var_dump($cart_item['day_1']);
            // var_dump($cart_item['day_2']);
            if($cart_item['day_1'] == 1 && $cart_item['day_2'] == 1){
                // var_dump($price);
                // var_dump($new_price);
                $new_price = $new_price * 2;
                // var_dump($new_price);
                // var_dump($cart_item['day_1']);
                // var_dump($cart_item['day_2']);
            }
            $cart_item['data']->set_price( $new_price );
            // var_dump($new_price);
        }


        $contents_total = $contents_total + $cart_item['data']->get_price();
    }


    $data = sc_gather_data();
    $cleaning_price = sc_get_order_totals($data)['cleaning-price'];

    // print_r($contents_total);
    WC()->session->set('general_total', $contents_total + $cleaning_price);
    WC()->cart->set_session();
}

add_action( 'woocommerce_init', 'force_non_logged_user_wc_session' );
function force_non_logged_user_wc_session(){
    if( is_user_logged_in() || is_admin() )
       return;

    if ( isset(WC()->session) && ! WC()->session->has_session() )
       WC()->session->set_customer_session_cookie( true );
}


// add_action( 'woocommerce_calculate_totals', 'add_custom_price', 10, 1);
function add_custom_price( $cart_object ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_calculate_totals' ) >= 2 )
        return;

    $cart_object->subtotal = WC()->session->get('general_total');
}

// Show booking data in order under "Shipping"
add_action('woocommerce_admin_order_data_after_shipping_address','sc_add_booking_data_to_order');
function sc_add_booking_data_to_order($order) {
    $order_id = $order->get_id();
    $food_note = get_post_meta($order_id,'menu_note',true);
    if($food_note) {
        echo '<p class="order_note"><strong>' . __( 'Food note:', 'woocommerce' ) . '</strong> ' . nl2br( esc_html( $food_note )) . '</p>';
    }
    echo '<div style="float: left;">';
    echo '<h3>Booking</h3>';
    echo '<br>';
    echo '<strong>Service: </strong>';
    $service = get_post_meta($order_id,'order_service_type',true);
    if($service) {
        echo attribute_slug_to_title( 'pa_service' ,$service );
    }
    echo '<br>';
    echo '<strong>Boat: </strong>';
    foreach ($order->get_items() as $item_id => $item){
        $product_id = $item->get_product_id();
        $cats = get_the_terms($product_id, 'product_cat');
        if(!empty($cats)) {
            foreach($cats as $cat) {
                $fleet_cat_id = get_term_by('slug','fleets','product_cat')->term_id;
                if($cat->parent == $fleet_cat_id || $cat->term_id == $fleet_cat_id) {
                    $boatName = $item->get_name();
                    echo $boatName;
                    break;
                }
            }
        }
    }
    echo '<br>';
    echo '<strong>Start Day: </strong>';
    echo date('F j, Y',strtotime(get_post_meta($order_id, 'sc_day_f', true)));
    echo '<br>';
    echo '<strong>End Day: </strong>';
    echo date('F j, Y',strtotime(get_post_meta($order_id, 'sc_endday_f', true)));
    echo '<br>';
    $adults = get_post_meta( $order_id, 'sc_adult_number', true );
    $children = get_post_meta( $order_id, 'sc_children_number', true );
    $infants = get_post_meta( $order_id, 'sc_infants_number', true );
    $not_sure_menu = get_post_meta( $order_id, 'sc_not_sure_menu', true );
    $is_food = get_post_meta( $order_id, 'is_food', true );
    echo '<strong>Adults: </strong>';
    echo $adults ? $adults : 0;
    echo '<br>';
    echo '<strong>Children: </strong>';
    echo $children ? $children : 0;
    echo '<br>';
    echo '<strong>Infants: </strong>';
    echo $infants ? $infants : 0;
    echo '<br>';
    echo '<strong>Includes food: </strong>';
    echo $is_food ? "True" : "False";
    echo '<br>';
    echo '<strong>Food Undecided: </strong>';
    echo $not_sure_menu ? "True" : "False";
    echo '</div>';
}


function set_excluded_dates($post_id) {
    if ($post_id === 'options') {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy'         => 'product_cat',
                    'field'            => 'slug',
                    'terms'            => 'food',
                    'operator'         => 'NOT IN',
                ),
            ),
        );

        $products = wc_get_products($args);
        $boats = [];
        foreach ($products as $product) {
            $product_id = $product->get_id();
            $boat_name = get_field('boat_name', $product_id);
            if(!$boat_name == ''){
                $boats[$boat_name] = $product_id;
            }
        }
        $csv = get_field('disabled_dates', 'option');
        $file = fopen($csv['url'], 'r');

        $headers = fgetcsv($file);
        $data = [];

        while ($row = fgetcsv($file)) {
            $key = $row[0];
            if(array_key_exists($row[0], $boats)){
                $key = $boats[$row[0]];
            }
            $data[$key][$row[2]][] = $row[1];
        }

        fclose($file);
        update_option('sc_excluded_dates', $data);
    }
}
if (class_exists('ACF')) {
    add_action('acf/save_post', 'set_excluded_dates');
}
