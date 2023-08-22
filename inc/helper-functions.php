<?php

function calc_extra_persons_price($people,$default,$price) {
    $new_price = 0;
    if($default > 0) {
        $extra_people = (int)$people - (int)$default;
        if($extra_people > 0) {
            $new_price += $price * $extra_people;
        }
    }
    return $new_price;
}

function sc_get_dates_in_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }
    return $dates;
}

function sc_get_rate_dates($service = '') {
    $rates = [];
    $year = date('Y');
    if($service == 'bareboat-charters'){
        $rate_1_from = get_field('bb_low_date_from','option');
        $rate_1_to = get_field('bb_low_date_to','option');
        $rate_2_from = get_field('bb_mid_date_from','option');
        $rate_2_to = get_field('bb_mid_date_to','option');
        $rate_3_from = get_field('bb_high_date_from','option');
        $rate_3_to = get_field('bb_high_date_to','option');
        $rates['rate_3'] = array(
            'from' => $rate_3_from . '/' . $year,
            'to' => $rate_3_to . '/' .  $year
        );
    } else {
        $rate_1_from = get_field('summer_rate_from', 'option');
        $rate_1_to = get_field('summer_rate_to', 'option');
        $rate_2_from = get_field('winter_rate_from', 'option');
        $rate_2_to = get_field('winter_rate_to', 'option');
    }
    $rates['rate_1'] = array(
        'from' => $rate_1_from . '/' .  $year,
        'to' => $rate_1_to . '/' .  $year
    );
    $rates['rate_2'] = array(
        'from' => $rate_2_from . '/' .  $year,
        'to' => $rate_2_to . '/' .  $year
    );
    return $rates;
}

function is_date_in_range($start_date, $end_date, $date_from_user) {
    // Convert to timestamp
    $start_ts = strtotime(str_replace('/', '-', $start_date));
    $end_ts = strtotime(str_replace('/','-', $end_date));
    $user_ts = strtotime(str_replace('/','-', $date_from_user));

    // Check that user date is between start & end.
    // In case of `$start_ts` is in the end at the year, `$end_ts` is at the beginning of the year
    if($start_ts >= $end_ts) {
        $end_year = strtotime('31-12-' . date('Y'));
        $start_year = strtotime('01-01-' . date('Y'));
        return ((($user_ts >= $start_ts) && ($user_ts <= $end_year)) || (($user_ts >= $start_year) && ($user_ts <= $end_ts)));
    }
    // Check that user date is between start & end
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

function get_boat_variation_id( $service, $product_id = 0 ) {
    global $wpdb;
    if ( $product_id == 0 )
        $product_id = get_the_id();
    return $wpdb->get_var( "
        SELECT p.ID
        FROM {$wpdb->prefix}posts as p
        JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id
        WHERE pm.meta_key = 'attribute_pa_service'
        AND pm.meta_value LIKE '$service'
        AND p.post_parent = $product_id
    " );
}

function get_service_times($product_id) {
    $service_times = array();

    // Get all the terms assigned to this product for the "pa_service" taxonomy
    $terms = get_the_terms($product_id, 'pa_service');

    if (!is_array($terms)) {
        return $service_times;
    }

    // Loop through each term and get the "time_start" and "time_end" fields
    foreach ($terms as $term) {
        $time_start = get_field('time_start', 'term_' . $term->term_id);
        $time_end = get_field('time_end', 'term_' . $term->term_id);

        // Add the service time to the array
        $service_times[$term->slug] = array(
            'time_start' => $time_start,
            'time_end' => $time_end
        );
    }

    return $service_times;
}

function get_product_booked_days($product_id, $attr) {
    $booked_days = array();

    // Get the start and end time of the service
    $service_times = get_service_times($product_id);

    if (!isset($service_times[$attr])) {
        return $booked_days;
    }
    $service_time_start = $service_times[$attr]['time_start'];
    $service_time_end = $service_times[$attr]['time_end'];

    // We get all variations of the product
    $product = wc_get_product($product_id);
    if (!$product || !$product->is_type('variable')) {
        return $booked_days;
    }

    $variations = $product->get_available_variations();

    // We go through all the variations and check the booked days
    foreach ($variations as $variation) {
        $variation_id = $variation['variation_id'];

        $orders = wc_get_orders(array(
            'sc_booking' => array(date('Y-m-d'), $variation_id),
            'return'      => 'ids'
        ));

        foreach ($orders as $order_id) {
            $start = get_post_meta($order_id, 'sc_day_f', true);
            $end = get_post_meta($order_id, 'sc_endday_f', true);

            $booking_service_id = get_post_meta($order_id, 'sc_service_id', true);
            $is_multi = get_field('is_multi', 'term_' . $booking_service_id);

            // Get start and end time for the booking service
            $booking_time_start = get_field('time_start', 'term_' . $booking_service_id);
            $booking_time_end = get_field('time_end', 'term_' . $booking_service_id);

            // Adding cruise start and end times to booking dates
            $start_datetime = new DateTime($start . ' ' . $booking_time_start);
            $end_datetime = new DateTime($end . ' ' . $booking_time_end);

            if ($is_multi) {
                // Subtract 1 day from end date time to exclude the last day of the booking
                $end_datetime->modify('-1 day');
                // Add the service end time to the service end date time
                $service_end_datetime = new DateTime($end . ' ' . $service_time_end);
                // Add 1 day to the service end date time to include the last day of the booking
                $service_end_datetime->modify('+1 day');
            } else {
                $service_end_datetime = new DateTime($end . ' ' . $service_time_end);
            }

            $service_start_datetime = new DateTime($start . ' ' . $service_time_start);

            // Check if booking times conflict
            if ($service_start_datetime < $end_datetime && $service_end_datetime > $start_datetime) {
                $booked_days[] = array($start_datetime->format('Y-m-d H:i:s'), $end_datetime->format('Y-m-d H:i:s'));
            }
        }
    }

    return $booked_days;
}

function get_booked_days($product_id, $service_id) {
    $booked_days = [];

    // Get the start and end time of the cruise
    $time_start = get_field('time_start', 'term_' . $service_id);
    $time_end = get_field('time_end', 'term_' . $service_id);

    // We get all variations of the product
    $product = wc_get_product($product_id);
    if (!$product || !$product->is_type('variable')) {
        return $booked_days;
    }

    $variations = $product->get_available_variations();

    // We go through all the variations and check the booked days
    foreach ($variations as $variation) {
        $variation_id = $variation['variation_id'];

        $orders = wc_get_orders([
            'sc_booking' => [date('Y-m-d'), $variation_id],
            'return'      => 'ids'
        ]);

        foreach ($orders as $order_id) {
            $start = get_post_meta($order_id, 'sc_day_f', true);
            $end = get_post_meta($order_id, 'sc_endday_f', true);

            $booking_service_id = get_post_meta($order_id, 'sc_service_id', true);
            $is_multi = get_field('is_multi', 'term_' . $booking_service_id);

            // Get start and end time for the booking service
            $booking_time_start = get_field('time_start', 'term_' . $booking_service_id);
            $booking_time_end = get_field('time_end', 'term_' . $booking_service_id);

            // Adding cruise start and end times to booking dates
            $start_datetime = new DateTime($start . ' ' . $booking_time_start);
            $end_datetime = new DateTime($end . ' ' . $booking_time_end);

            if ($is_multi) {
                // Subtract 1 day from end date time to exclude the last day of the booking
                $end_datetime->modify('-1 day');
                // Add the service end time to the service end date time
                $service_end_datetime = new DateTime($end . ' ' . $time_end);
                // Add 1 day to the service end date time to include the last day of the booking
                $service_end_datetime->modify('+1 day');
            } else {
                $service_end_datetime = new DateTime($end . ' ' . $time_end);
            }

            $service_start_datetime = new DateTime($start . ' ' . $time_start);

            // Check if booking times conflict
            // print_r($service_end_datetime);
            // print_r($start_datetime);

            // var_dump($service_end_datetime > $start_datetime);
            // var_dump($service_start_datetime < $service_end_datetime && $service_end_datetime > $start_datetime && $service_start_datetime < $end_datetime);
            if ($service_start_datetime < $service_end_datetime && $service_end_datetime > $start_datetime && $service_start_datetime < $end_datetime) {
                $booked_days[] = [$start_datetime->format('Y-m-d H:i:s'), $end_datetime->format('Y-m-d H:i:s')];
            }
        }
    }

    return $booked_days;
}

/*function get_booked_days($product_id, $service_id) {
    $booked_days = [];

    // Get the start and end time of the cruise
    $time_start = get_field('time_start', 'term_' . $service_id);
    $time_end = get_field('time_end', 'term_' . $service_id);

    // We get all variations of the product
    $product = wc_get_product($product_id);
    if (!$product || !$product->is_type('variable')) {
        return $booked_days;
    }

    $variations = $product->get_available_variations();

    // We go through all the variations and check the booked days
    foreach ($variations as $variation) {
        $variation_id = $variation['variation_id'];

        $orders = wc_get_orders([
            'sc_booking' => [date('Y-m-d'), $variation_id],
            'return'      => 'ids'
        ]);

        foreach ($orders as $order_id) {
            $start = get_post_meta($order_id, 'sc_day_f', true);
            $end = get_post_meta($order_id, 'sc_endday_f', true);

            $booking_service_id = get_post_meta($order_id, 'sc_service_id', true);
            $is_multi = get_field('is_multi', 'term_' . $booking_service_id);

            // Get start and end time for the booking service
            $booking_time_start = get_field('time_start', 'term_' . $booking_service_id);
            $booking_time_end = get_field('time_end', 'term_' . $booking_service_id);

            // Adding cruise start and end times to booking dates
            $start_datetime = new DateTime($start . ' ' . $booking_time_start);
            $end_datetime = new DateTime($end . ' ' . $booking_time_end);

            if ($is_multi) {
                // Subtract 1 day from end date time to exclude the last day of the booking
                $end_datetime->modify('-1 day');
                // Add the service end time to the service end date time
                $service_end_datetime = new DateTime($end . ' ' . $time_end);
                // Add 1 day to the service end date time to include the last day of the booking
                $service_end_datetime->modify('+1 day');
            } else {
                $service_end_datetime = new DateTime($end . ' ' . $time_end);
            }

            $service_start_datetime = new DateTime($start . ' ' . $time_start);

            // Check if booking times conflict
            if ($service_start_datetime < $service_end_datetime && $service_end_datetime > $start_datetime && $service_start_datetime < $end_datetime) {
                $booked_days[] = [$start_datetime->format('Y-m-d H:i:s'), $end_datetime->format('Y-m-d H:i:s')];
            }
        }
    }

    return $booked_days;
}

function get_product_booked_days($variation_id) {
    $booked_days = [];
    $orders = wc_get_orders( [
        'sc_booking' => [date('Y-m-d'), $variation_id],
        'return'        => 'ids'
    ]);
    foreach ( $orders as $order_id ){
        $start = get_post_meta($order_id, 'sc_day_f', true);
        $end = get_post_meta($order_id, 'sc_endday_f', true);
        $booked_days[] = [$start, $end];
    }
    return($booked_days);
}*/

add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', 'custom_order_query', 10, 2 );
function custom_order_query($query, $query_vars){
    if ( ! empty( $query_vars['sc_booking'] ) ) {
        $query['meta_query'][] = [
            'relation' => 'AND',
            [
                'key'      => 'sc_endday_f',
                'value'    => $query_vars['sc_booking'][0],
                'compare'  => '>'
            ],
            [
                'key'      => 'sc_variation_id',
                'value'    => $query_vars['sc_booking'][1],
                'compare'  => '='
            ]
        ];
    }
    if ( ! empty( $query_vars['sc_booking_boats_daily'] ) ) {
        $query['meta_query'][] = [
            'relation' => 'AND',
            [
                'key'      => 'sc_day_f',
                'value'    => $query_vars['sc_booking_boats_daily'][0],
                // 'value'    => '4',
                'compare'  => 'LIKE'
            ],
            [
                'key'      => 'sc_variation_id',
                'value'    => $query_vars['sc_booking_boats_daily'][1],
                'compare'  => 'LIKE'
            ]
        ];
    }
    if ( ! empty( $query_vars['sc_booking_boats'] ) ) {
        $query['meta_query'][] = [
            'relation' => 'AND',
            [
                'key'      => 'sc_product_id',
                'value'    => $query_vars['sc_booking_boats'][2],
                'compare'  => '='
            ],
            [
                'relation' => 'OR',
                [
                    'relation' => 'AND',
                    [
                        'key'      => 'sc_day_f',
                        'value'    => $query_vars['sc_booking_boats'][0],
                        'compare'  => '='
                    ],
                    [
                        'key'      => 'sc_endday_f',
                        'value'    => $query_vars['sc_booking_boats'][1],
                        'compare'  => '='
                    ],
                ],
                [
                    'key' => 'sc_day_f',
                    'value' => [$query_vars['sc_booking_boats'][0], $query_vars['sc_booking_boats'][1]],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ],
                [
                    'key' => 'sc_endday_f',
                    'value' => [$query_vars['sc_booking_boats'][0], $query_vars['sc_booking_boats'][1]],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ],
            ]
        ];
    }
    if ( ! empty( $query_vars['sc_booking_page'] ) ) {
        $query['meta_query'][] = [
                'key'      => 'sc_day_f',
                'value'    =>  [date("Y") .'-' . $query_vars['sc_booking_page'][0], date("Y") .'-' . $query_vars['sc_booking_page'][1]],
                'compare'  => 'BETWEEN',
                'type'     => 'DATE_TIME'

        ];
    }
    return $query;
}

function sc_get_cleaning_price() {
    $cleaning_id = get_field('cleaning_product_id','option');
    $cleaning_product = wc_get_product($cleaning_id);
    $cleaning_price = $cleaning_product->get_price();
    return (float)$cleaning_price;
}

function woo_in_cart($product_id) {
    foreach( WC()->cart->get_cart() as $key => $val ) {
        $_product = $val['data'];
        if($product_id == $val['product_id'] ) {
            return true;
        }
    }

    return false;
}

add_action('wp_ajax_nopriv_sc_update_data', 'sc_update_data');
add_action('wp_ajax_sc_update_data', 'sc_update_data');

function sc_update_data($data){
    save_data_to_session($data);
    wp_die(ajax_get_order_totals($data));
}

function ajax_get_order_totals(){
    $data = [
    'people_total' => $_POST['people_total'],
    'adults' => $_POST['adults'],
    'children' => $_POST['children'],
    'infants' => $_POST['infants'],
    'trip_start' => $_POST['trip_start'],
    'trip_end' => $_POST['trip_end'],
    'trip_duration' => $_POST['trip_duration'],
    'service_id' => $_POST['service_id'],
    'variation_id' => $_POST['variation_id']
    ];

    $values = sc_get_order_totals($data);

    wp_die(json_encode($values));
}

function sc_get_order_totals($data = [], $order_id = 0){
    if(empty($data)){
        $data = sc_gather_data($order_id);
    }
    $rate = sc_get_pre_order_total($data);

    $deposit = get_field('deposit', 'term_' . $data['service_id']);
    $deposit = $deposit == '' ? get_field('deposit_price', 'option') : $deposit;

    $cleaning_price = $deposit / 100 * 5 * $data['trip_duration'];
    $deposit_value = $data['trip_duration'] * $deposit;
    $payable = $deposit_value + $cleaning_price;

    $balance = $rate - $deposit_value;

    $values = [
        'boat-price'     => $rate,
        'deposite-price' => $deposit_value,
        'cleaning-price' => $cleaning_price,
        'payable'        => $payable,
        'charter-row_price' => $balance,


    ];
    return $values;
}

function sc_gather_data($order_id = 0){
    if(isset($order_id)) {
        $order = wc_get_order($order_id);
    }
    $data = [
        'people_total' => 0,
        'adults' => 0,
        'children' => 0,
        'infants' => 0,
        'trip_start' => 0,
        'trip_end' => 0,
        'trip_duration' => 0,
        'service_id' => 0,
        'variation_id' => 0
    ];
    if(isset($order) && $order) {
        foreach($order->get_items() as $order_item) {
            if($order_item['variation_id']) {
                $data = [
                    'adults' => get_post_meta( $order_id, 'sc_adult_number', true),
                    'people_total' => get_post_meta( $order_id, 'sc_people_numbe', true),
                    'trip_start' => get_post_meta( $order_id, 'sc_day_f', true),
                    'trip_end' => get_post_meta( $order_id, 'sc_endday_f', true),
                    'trip_duration' => get_post_meta( $order_id, 'sc_duration', true),
                    'service_id' => get_post_meta( $order_id, 'sc_service_id', true),
                    'variation_id' => get_post_meta( $order_id, 'sc_variation_id', true)
                ];
                if(get_post_meta( $order_id, 'sc_children_number', true)) {
                    $data['children'] = get_post_meta( $order_id, 'sc_children_number', true);
                }
                if(get_post_meta($order_id,'sc_infants_number',true)) {
                    $data['infants'] = get_post_meta($order_id,'sc_infants_number',true);
                }
                if(get_post_meta($order_id,'destination',true)) {
                    $data['destination'] = get_post_meta($order_id,'destination',true);
                }
            }
        }
    } else {
        $data = [
            'adults' => WC()->session->get('adult_number') !== null ? WC()->session->get('adult_number') : 0,
            'children' => WC()->session->get('children_number') !== null ? WC()->session->get('children_number') : 0,
            'infants' => WC()->session->get('infants_number') !== null ? WC()->session->get('infants_number') : 0,
            'people_total' => WC()->session->get('s_people_number') ? WC()->session->get('s_people_number') : 0,
            'trip_start' => WC()->session->get('s_day_f'),
            'trip_end' => WC()->session->get('s_endday_f') !== null ? WC()->session->get('s_endday_f') : date('Y-m-d', strtotime(WC()->session->get('s_day_f') . ' +' . ((int)WC()->session->get('s_duration') -1) . ' day')),
            'trip_duration' => WC()->session->get('s_duration') !== null ? WC()->session->get('s_duration') : 1,
            'service_id' => WC()->session->get('s_service_id') !== null ? WC()->session->get('s_service_id') : 0,
            'variation_id' => WC()->session->get('variation_id')
        ];
    }
    return $data;

}

//sc_get_actual_rate(252, '20-05-2023');
function sc_get_actual_rate($variation_id, $date) {
    if(!$date){
        $date = date('Y') . "/01/01";
    }
    $attrs = wc_get_product_variation_attributes( $variation_id );
    $service = $attrs['attribute_pa_service'];
    $rate = 0;

    $date_ranges = sc_get_rate_dates($service);
    foreach ( $date_ranges as $key => $range ){
        if(is_date_in_range($range['from'], $range['to'], $date)){
            $rate = get_post_meta($variation_id, '_' . $key, true);
        }
    }
    return (float)$rate;
}

function sc_get_pre_order_total($data){
    $variation_id = $data['variation_id'];
    $attrs = wc_get_product_variation_attributes( $variation_id );
    $attr = $attrs['attribute_pa_service'];
    $summ_price = 0;
    $duration = $data['trip_duration'];
    $people = $data['people_total'];
    $default_people = (int)get_post_meta($variation_id,'_default_people',true);
    $extra_price = (int)get_post_meta($variation_id,'_extra_person_price',true);
    $dates_list = sc_get_dates_in_range($data['trip_start'],$data['trip_end']);

    if((int)$duration > 1) {
        if($attr == 'bareboat-charters') {
            array_pop($dates_list);
            array_shift($dates_list);
        }
        foreach($dates_list as $day) {
            if($attr == 'bareboat-charters' || $attr == 'multi-day-charters') {
                $price = sc_get_actual_rate($variation_id, $day);
                $summ_price += $price;
                $extra = calc_extra_persons_price($people,$default_people,$extra_price);
                $summ_price += $extra;
            }
        }
    } else {
        $day = $data['trip_start'];
        $price = (float)sc_get_actual_rate($variation_id, $day);
        $summ_price = $price;
        $extra = calc_extra_persons_price($people,$default_people,$extra_price);
        $summ_price += $extra;
    }
    $nightprice = 0;
    if($attr == 'multi-day-charters') {
        $nightprice = (float)get_post_meta($variation_id, '_rate_3', true );
    }
    if($duration == 1){
        $price = $summ_price + $nightprice;
    } else {
    $price = $summ_price + $nightprice * ($duration-1);
    }
//    $cart_item['data']->set_price($price);
    return $price;
}

function dateIsOverlap($dbfrom1,$dbto1,$user_st,$user_et)
{
    if (((strtotime($user_st) >= strtotime($dbfrom1)) and (strtotime($user_st) <= strtotime($dbto1))) or (strtotime($user_et) >= strtotime($dbfrom1)) and (strtotime($user_et) <= strtotime($dbto1))) {
        return true;
    } else {
        return false;
    }
}

add_action('wp_ajax_nopriv_sc_add_item_to_cart', 'sc_add_item_to_cart');
add_action('wp_ajax_sc_add_item_to_cart', 'sc_add_item_to_cart');
function sc_add_item_to_cart(){
    WC()->cart->empty_cart();
    save_data_to_session($_POST);
    $variation_id = absint($_POST['variation_id']);
    $variation = wc_get_product($variation_id);
    $product_id = $variation->get_parent_id();

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($product_id));
    $quantity = 1;
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && $add = WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
        echo $add;
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

        echo json_encode($data);
    }

    wp_die('hello world');
}

function save_data_to_session($data = []){
    // Get service from js
    if(isset($_POST['service_id']) && !empty($_POST['service_id'])) {
        WC()->session->set('s_service_id', $_POST['service_id']);
    } else {
        WC()->session->set('s_service_id', null);
    }

    // Get people total from js
    if(isset($_POST['people_total']) && !empty($_POST['people_total'])) {
        WC()->session->set('s_people_number', (int)$_POST['people_total'] > 0 ? (int)$_POST['people_total'] : 0);
    } else {
        WC()->session->set('s_people_number', null);
    }

    // Get adults from js
    if(isset($_POST['adults']) && !empty($_POST['adults'])) {
        WC()->session->set('adult_number', (int)$_POST['adults'] > 0 ? (int)$_POST['adults'] : 0);
    } else {
        WC()->session->set('adult_number', null);
    }

    // Get children total from js
    if(isset($_POST['children']) && !empty($_POST['children'])) {
        WC()->session->set('children_number', (int)$_POST['children'] > 0 ? (int)$_POST['children'] : 0);
    } else {
        WC()->session->set('children_number', null);
    }

    // Get infants total from js
    if(isset($_POST['infants']) && !empty($_POST['infants'])) {
        WC()->session->set('infants_number', (int)$_POST['infants'] > 0 ? (int)$_POST['infants'] : 0);
    } else {
        WC()->session->set('infants_number', null);
    }

    // Get trip start from js
    if(isset($_POST['trip_start']) && !empty($_POST['trip_start']) && ($_POST['trip_start'] != 0)) {
        WC()->session->set('s_day', date('F j', strtotime($_POST['trip_start'])));
        WC()->session->set('s_day_f', date('Y-m-d', strtotime($_POST['trip_start'])));
    } else {
        WC()->session->set('s_day', null);
        WC()->session->set('s_day_f', null);
    }

    // Get trip end from js
    if(isset($_POST['trip_end']) && !empty($_POST['trip_end']) && ($_POST['trip_end'] != 0)) {
        WC()->session->set('s_endday', date('F j', strtotime($_POST['trip_end'])));
        WC()->session->set('s_endday_f', date('Y-m-d', strtotime($_POST['trip_end'])));
    } else {
        WC()->session->set('s_endday', null);
        WC()->session->set('s_endday_f', null);
    }

    // Get trip duration from js
    if(isset($_POST['trip_duration']) && !empty($_POST['trip_duration']) && $_POST['trip_duration'] > 1) {
        WC()->session->set('s_duration', $_POST['trip_duration']);
    } else {
        WC()->session->set('s_duration', 1);
    }

    // Get variation id from js
    if(isset($_POST['variation_id']) && !empty($_POST['variation_id'])) {
        WC()->session->set('variation_id', $_POST['variation_id']);
    } else {
        WC()->session->set('variation_id', null);
    }

    // Get service id from js
    if(isset($_POST['service_id']) && !empty($_POST['service_id'])) {
        WC()->session->set('service_id', $_POST['service_id']);
    } else {
        WC()->session->set('service_id', null);
    }
    WC()->cart->set_session();
}

function sc_check_category_in_order($order_id, $cat_slug) {
    $cat_in_order = 0;
    $order = wc_get_order($order_id);
    if($order) {
      $food_cat = get_term_by( 'slug', $cat_slug, 'product_cat' );
      $food_cat_id = $food_cat->term_id;
      foreach ( $order->get_items() as $order_item ) {
        $product_id =  $order_item->get_product_id();
        $cats = get_the_terms($product_id, 'product_cat');
        if(!empty($cats)) {
          foreach($cats as $cat) {
            if($cat->parent == $food_cat_id || $cat->term_id == $food_cat_id) {
              $cat_in_order = 1;
              break;
            }
          }
        }
        if($cat_in_order == 1) {
          break;
        }
      }
    }
    return $cat_in_order;
  }

// AJAX adding products on checkout page
add_action('wp_ajax_checkout_add_products', 'checkout_add_products_handler');
add_action('wp_ajax_nopriv_checkout_add_products', 'checkout_add_products_handler');
function checkout_add_products_handler() {
  $products = $_POST['products'];
  $product_discount = (int)get_field('discount_for_children','option');
  $products_subtotal = 0;
  $counter = 0;
  $response = array();
  foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    $product_id = $cart_item['product_id'];
    if(has_term('food','product_cat',$product_id)) {
      $item_key = $cart_item_key;
      WC()->cart->remove_cart_item($item_key);
    }
  }
  if(empty($products)) {
    foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
      if ( has_term( 'food', 'product_cat', $cart_item['product_id'] ) ) {
        WC()->cart->remove_cart_item( $cart_item_key );
        $counter++;
      }
    }
    $response['items'] = '';
    $products_subtotal = 0;
  } else {
    if(is_array($products)) {
      foreach($products as $p) {
        $people = sc_gather_data();
        $q = (int)$people['adults'];
        $q2 = (int)$people['children'];
        $total_q = $q + $q2;
        $product_id = (int)$p[0];
        $product = wc_get_product($product_id);
        $price = $product->get_price();
        $is_added = false;
        $q_summ = 1;
        if($product) {
          if(!$in_cart && $total_q > 0) {
            if(count($p) == 3) {
              $day1_q = (int)$p[1];
              $day2_q = (int)$p[2];
              $q_summ = $day1_q + $day2_q;
              WC()->cart->add_to_cart( $product_id, 1, NULL, NULL, array('day_1' => $day1_q, 'day_2' => $day2_q) );
            } else {
              WC()->cart->add_to_cart( $product_id, 1, NULL, NULL, array('day_1' => $day1_q, 'day_2' => $day2_q) );
            }
            $is_added = true;
          }
          $counter++;
          if($is_added) {
            $products_total = ($price * $q + $price/100 * (100 - $product_discount) * $q2) * $q_summ;
            $products_subtotal += $products_total;
            $response['items'] .= '<tr class="product-details_food"><td colspan="2"><span class="q-text">' . $total_q * $q_summ . 'x</span> ' . $product->get_title() . '</td><td class="servise-price_order">' . wc_price($products_total) . '</td></tr>';
          }
        }
      }

    }
  }
  WC()->cart->calculate_totals();
    $total = WC()->cart->cart_contents_total;
    $prices = sc_get_order_totals_checkout();
    $response['deposite'] = '€ ' . number_format(intval($prices['deposite-price']), 0, '', ' ');
    $response['payable'] = '€ ' . number_format(intval($prices['payable']), 0, '', ' ');
    $total = WC()->session->get('general_total') - $prices['deposite-price'] - $prices['cleaning-price'];

    $response['total'] = '€ ' . $total;
    sc_get_order_totals_checkout();
    echo json_encode($response);

    wp_die();
}

function sc_get_order_totals_checkout(){
    $data = sc_gather_data();
    $prices = sc_get_order_totals($data);
        WC()->cart->set_total($prices['payable']);
        // WC()->session->set('general_total', $prices['boat-price'] + $prices['cleaning-price'] );
    return $prices;
}

add_action('woocommerce_admin_order_totals_after_total','sc_add_pending_to_order_admin');
function sc_add_pending_to_order_admin($order_id) {
  $order = wc_get_order($order_id);
  if($order) {
    $discount = $order->get_total_discount();
    $new_total = $order->get_subtotal() - $discount;
    $order_total = $order->get_total();
    $pending_total = get_post_meta($order_id, 'sc_general_total', true);
?>
  <tr>
    <td class="label"><?php esc_html_e( 'Pending Amount:', 'woocommerce' ); ?></td>
    <td width="1%"></td>
    <td class="total">
      <?php echo wc_price( $pending_total - $order_total, array( 'currency' => $order->get_currency() ) ); ?>
    </td>
  </tr>
<?php
  }
}


add_filter( 'woocommerce_calculated_total', "sc_order_after_calculate_totals", 20, 2);
function sc_order_after_calculate_totals($total, $cart) {
    $prices = sc_get_order_totals_checkout();
    return $prices['payable'];
}

// Get attribute title by slug
if ( ! function_exists( 'attribute_slug_to_title' ) ) {
    function attribute_slug_to_title( $attribute ,$slug ) {
        global $woocommerce;
        if ( taxonomy_exists( esc_attr( str_replace( 'attribute_', '', $attribute ) ) ) ) {
            $term = get_term_by( 'slug', $slug, esc_attr( str_replace( 'attribute_', '', $attribute ) ) );
            if ( ! is_wp_error( $term ) && $term->name )
                $value = $term->name;
        } else {
            $value = apply_filters( 'woocommerce_variation_option_name', $value );
        }
        return $value;
    }
}

function sc_exclude_dates(){
    if(!get_field('excluded_dates', 'options')){
        return [];
    }
    $morning_services_row = get_field('morning_services', 'options');
    $morning_services_row = str_replace(' ', '', $morning_services_row);
    $morning_services_array = explode(',', $morning_services_row);

    $evening_services_row = get_field('evening_services', 'options');
    $evening_services_row = str_replace(' ', '', $evening_services_row);
    $evening_services_array = explode(',', $evening_services_row);
    return(json_encode(['morning_services' => $morning_services_array, 'evening_services' => $evening_services_array]));
}