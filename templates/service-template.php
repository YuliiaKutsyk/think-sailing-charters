<?php /* Template Name: Service Page */
get_header();

$service_attr_obj = get_field('service_product_attr');

$post_title = $service_attr_obj->name;
$post_name = $service_attr_obj->slug;
$post_id = $service_attr_obj->term_id;
$service_term = get_term_by('slug',$post_name,'pa_service');
$is_multiday = get_field('is_multi',$service_attr_obj);
$is_nonbookable = get_field('is_non-bookable', $service_attr_obj);
$is_bareboat = get_field('is_bareboat', $service_attr_obj);
$excluded_dates = get_option('sc_excluded_dates');

?>
    <section class="page-content single-services fleet-content single-content <?php echo $is_bareboat ? 'bareboat-charters' : ''; ?> <?php echo $is_nonbookable ? 'third-party' : ''; ?> <?php echo $is_multiday ? 'multi-day-charters':''; ?>">
        <div class="fleet-mobile_gallery">
            <div class="owl-carousel about-gallery_slider" id="about-gallery_slider">
                <?php if( have_rows('service_gallery', $service_attr_obj) ){
                    while ( have_rows('service_gallery', $service_attr_obj) ){ the_row();
                        $image = get_sub_field('image');;?>
                        <div class="slider-item">
                            <a href="<?php echo $image; ?>" class="about-gallery_item viewbox">
                                <img src="<?php echo $image; ?>" alt="" />
                            </a>
                        </div>
                    <?php }} ?>
            </div>
            <div class="owl-custom_counter owl-custom_counter--single">
                <div class="owl-dot_current">1</div>
                <div class="separator">/</div>
                <div class="owl-dot_counter"></div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="fleet-titles single-titles">
                        <div class="fleets-titles_wrap">
                            <h1><?php the_title(); ?></h1>
                        </div>
                        <a href="#" class="back-link">Go back</a>
                    </div>
                    <div class="single-gallery">
                        <?php if( have_rows('service_gallery', $service_attr_obj) ){
                            while ( have_rows('service_gallery', $service_attr_obj) ){ the_row();
                                $image = get_sub_field('image'); ?>
                                <a href="<?php echo $image; ?>" class="viewbox">
                                    <img src="<?php echo $image; ?>" alt="" />
                                </a>
                            <?php }} ?>
                        <a href="<?php echo $image; ?>" class="viewbox-button viewbox" id="viewbox-button">See more</a>
                    </div>
                    <div class="single-info">
                        <div class="left">
                            <div class="single-description">
                                <div class="single-description_inner">
                                    <?php echo get_field('description', $service_attr_obj);?>
                                </div>
                                <a href="#" class="read-more">Read more</a>
                            </div>
                            <div class="single-feautures">
                                <?php
                                if( have_rows('features', $service_attr_obj)){
                                while( have_rows('features', $service_attr_obj) ) {
                                    the_row();
                                    ?>
                                    <div class="feauture-item">
                                        <?php echo wp_get_attachment_image(get_sub_field('icon')); ?>
                                        <h4><?php echo get_sub_field('title'); ?></h4>
                                        <p><?php echo get_sub_field('text'); ?></p>
                                    </div>
                                <?php }} ?>
                            </div>
                        </div>
                        <!-- /left -->
                        <div class="right">
                            <div class="order-sidebar">
                                <div class="services_service-title" style="display: none;"><?php echo $post_title; ?></div>
                                <p class="order-sidebar_title">Add the following for price:</p>
                                <form action="#" method="post" class="order-sidebar_form">
                                    <input type="hidden" class="sc_to_price" name="sc_people_total" value="">
                                    <input type="hidden" name="sc_people_adults" value="">
                                    <input type="hidden" name="sc_people_children" value="">
                                    <input type="hidden" name="sc_people_infants" value="">
                                    <input type="hidden" class="sc_to_price" name="sc_trip_start" value="">
                                    <input type="hidden" class="sc_to_price" name="sc_trip_end" value="">
                                    <input type="hidden" class="sc_to_price" name="sc_trip_duration" value="">
                                    <input type="hidden" class="sc_to_price" name="sc_variation_id" value="">
                                    <input type="hidden" class="sc_to_price" name="sc_service_id" value="<?php echo $post_id; ?>">
                                    <div class="charter-input charter-passenger">
                                        <div class="charter-passenger_amount">People</div>
                                        <div class="peoples-wrap filter-dropdown_wrap peoples-wrap_inner">
                                            <div class="peoples-row">
                                                <div class="left">
                                                    <h4>Adults</h4>
                                                    <p>Ages 13 or above</p>
                                                </div>
                                                <div class="right">
                                                    <input class="less-people less-people_adult disabled" disabled value="-" readonly />
                                                    <input type="text" value="0" placeholder="0" data-type="people" class="poeple-summury people-calc" readonly />
                                                    <input class="more-people more-people_adult" value="+" readonly />
                                                </div>
                                            </div>
                                            <div class="peoples-row">
                                                <div class="left">
                                                    <h4>Children</h4>
                                                    <p>Ages 2-12</p>
                                                </div>
                                                <div class="right">
                                                    <input class="less-people less-people_children disabled" disabled value="-" readonly />
                                                    <input type="text" value="0" placeholder="0" data-type="children" class="poeple-children_summury people-calc" readonly />
                                                    <input class="more-people more-people_children" value="+" readonly />
                                                </div>
                                            </div>
                                            <div class="peoples-row">
                                                <div class="left">
                                                    <h4>Infants</h4>
                                                    <p>Under 2</p>
                                                </div>
                                                <div class="right">
                                                    <input class="less-people less-people_infants disabled" disabled value="-" readonly />
                                                    <input type="text" value="0" data-type="infants" placeholder="0" class="poeple-infants_summury people-calc" readonly />
                                                    <input class="more-people more-people_infants" value="+" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if(!$is_nonbookable) { ?>
                                        <div class="charter-input charter-type charter-type_service">
                                            <div class="charter-type_current">Boat</div>
                                            <?php
                                            $service_attr_name = $service_attr_obj->slug;
                                            ?>
                                            <div class="charter-type_dropdown">
                                                <?php $the_query = new WP_Query( array(
                                                    'posts_per_page'=> -1,
                                                    'post_type'=>'product_variation',
                                                    'meta_query'      => array(
                                                        array(
                                                            'key'     => 'attribute_pa_service', // Product variation attribute
                                                            'value'   => $service_attr_name, // Term slugs only
                                                            'compare' => 'IN',
                                                        ),
                                                    ),

                                                ) );

                                                $available_posts = $the_query->posts;
                                                usort($available_posts, function($a, $b){
                                                    $a_price = sc_get_actual_rate($a->ID, date('Y-m-d'));
                                                    $b_price = sc_get_actual_rate($b->ID, date('Y-m-d'));

                                                    if ($a_price < $b_price) {
                                                        return -1;
                                                    } elseif ($a_price > $b_price) {
                                                        return 1;
                                                    } else {
                                                        return 0;
                                                    }
                                                });

                                                ?>
                                                <?php
                                                    foreach ($available_posts as $post) {
                                                        setup_postdata($GLOBALS['post'] =& $post);
                                                    $variation_id = get_the_ID();
                                                    $var_product = new WC_Product_Variation($variation_id);
                                                    $product_id = $var_product->get_parent_id();
                                                    $product = wc_get_product( $product_id );
                                                    $boat_name = get_field('boat_name',$product_id);



                                                    if(empty($variation_id)) {
                                                        continue;
                                                    }
                                                    $is_boat_bookable = get_field('third_party_boat', $product_id);
                                                    $bookings = array();
                                                    /*$booked_days = get_booked_days($product_id, $post_id);*/
                                                    $booked_days = get_booked_days($product_id, $post_id);
                                                    $excluded_dates_boat = [];
                                                    $excluded_dates_boat_day = [];
                                                    $excluded_dates_boat_evening = [];
                                                    if(array_key_exists($product_id, $excluded_dates)){
                                                        $excluded_dates_boat = $excluded_dates[$product_id];
                                                        if(array_key_exists('Day', $excluded_dates_boat)){
                                                            $excluded_dates_boat_day = $excluded_dates_boat['Day'];
                                                        }
                                                        if(array_key_exists('Evening', $excluded_dates_boat)){
                                                            $excluded_dates_boat_evening = $excluded_dates_boat['Evening'];
                                                        }
                                                    }
                                                    ?>
                                                    <div class="charter-type_item charter-boat_item srv-boat-select-item" data-persons="<?php echo get_field('persons_count', $product_id); ?>" max_people="<?php echo get_field('persons_count', $product_id); ?>" data-bookable="<?php echo $is_boat_bookable ? 0 : 1; ?>"
                                                    data-booked='<?php echo json_encode($booked_days); ?>' data-booked-day='<?php echo json_encode($excluded_dates_boat_day); ?>' data-booked-evening='<?php echo json_encode($excluded_dates_boat_evening); ?>' variation_id="<?php echo $variation_id ?>" service_id="<?php echo $post_id; ?>">
                                                        <?php echo get_the_title($product_id); ?>
                                                        <input type="hidden" class="charter-type_value" data-id="<?php echo $variation_id; ?>" value="<?php the_title(); ?>" value="<?php the_title(); ?>" <?php echo $boat_name ? 'data-boatname="' . $boat_name . '"': ''; ?> />
                                                    </div>
                                                <?php } ?>
                                                <?php wp_reset_postdata(); ?>
                                            </div>
                                        </div>
                                    <?php } if($is_multiday) { ?>
                                        <div class="charter-time_multy">
                                            <span class="date-from">From</span>
                                            <span class="date-to">To</span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="charter-input charter-time charter-time_single">
                                            Check in

                                            <?php
                                                $current_url = $_SERVER['REQUEST_URI'];

                                                if (strpos($current_url, '/day-charters/') !== false) {
                                                    $formatted_time = '09:00';
                                                } elseif (strpos($current_url, '/multi-day-charters/') !== false) {
                                                    $formatted_time = '';
                                                } elseif (strpos($current_url, '/flotilla-charters/') !== false) {
                                                    $formatted_time = '';
                                                } elseif (strpos($current_url, '/evening-cruises/') !== false) {
                                                    $formatted_time = '19:00';
                                                } elseif (strpos($current_url, '/romantic-evening-cruises/') !== false) {
                                                    $formatted_time = '19:00';
                                                } elseif (strpos($current_url, '/product/') !== false) {
                                                    $formatted_time = '09:00';
                                                } elseif (strpos($current_url, '/corporate-events/') !== false) {
                                                    $formatted_time = '09:00';
                                                } elseif (strpos($current_url, '/special-events/') !== false) {
                                                    $formatted_time = '';
                                                } else {
                                                    $formatted_time = '';
                                                }

                                                echo '' . '<span class="start-time">' . $formatted_time . '</span>';
                                            ?>
                                        </div>
                                    <?php } ?>
                                    <?php get_template_part('template-parts/multiday-calendar-form'); ?>
                                    <?php
                                    $new_total = 0;
                                    if(!$is_nonbookable) {
                                        ?>
                                        <div class="charter-options" style="display: none">
                                            <div class="charter-option_row" id="boat-price">
                                                <p>Charter (+ tax)</p>
                                                <p class="medium-text boat-price">€<span>0</span></p>
                                            </div>
                                            <div class="charter-option_row" id="deposite-price">
                                                <p>Deposit</p>
                                                <p class="medium-text deposite-price">€<span>0</span></p>
                                            </div>
                                            <div class="charter-option_row" id="cleaning-price">
                                                <p>Online transaction charge</p>
                                                <p class="medium-text cleaning-price">€<span>0</span></p>
                                            </div>
                                            <div class="charter-option_row" id="payable-price">
                                                <p>Amount Payable Now</p>
                                                <p class="medium-text deposite-price payable">€<span>0</span></p>
                                            </div>
                                            <div class="charter-option_row" id="amount-price">
                                                <p class="medium-text">Pending Amount</p>
                                                <p class="charter-row_price">€<span class="price-number_text">0</span></p>
                                            </div>
                                        </div>
                                        <input type="submit" class="single_add_to_cart_button cart-first_button to-checkout_button boat-type_checkout--button disabled" value="Continue to checkout">
                                        <a href="#" class="hidden to-checkout_button availability-check_button service-btn">Request availability</a>
                                    <?php } else { ?>
                                        <a href="#" class="to-checkout_button availability-check_button service-btn">Request availability</a>
                                    <?php } ?>
                                </form>

                            </div>
                            <div class="page-bottom_message sidebar-message">
                                <a href="mailto:<?php the_field('email', 'options'); ?>" class="message-email"><?php the_field('email', 'options'); ?></a>
                                <a href="tel:<?php the_field('phone', 'options'); ?>" class="message-phone"><?php the_field('phone', 'options'); ?></a>
                            </div>
                            <div class="sidebar-bottom_text">
                                <?php the_field('sidebar_boat_text', 'options'); ?>
                            </div>
                        </div>
                        <!-- /right -->
                    </div>

                    <?php
                    if( have_rows('terms_and_conditions', $service_attr_obj) ){ ?>
                        <div class="rates-terms_item">
                            <h4 class="term-title">
                                <?php echo __( 'Terms and Conditions' ); ?>
                            </h4>
                            <div class="rates-terms_columns">
                                <?php while( have_rows('terms_and_conditions', $service_attr_obj) ){ the_row(); ?>
                                    <div class="term-column_item">
                                        <?php the_sub_field('column'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- rates-terms_item -->
                    <div class="charter-boats">
                        <h4 class="charter-boats_title">Boats available for <?php the_title(); ?></h4>
                        <?php
                        $page_title = get_the_title();
                        $service_attr_name = $service_attr_obj->slug;
                        ?>
                        <div class="charter-boats_wrap">
                            <?php
                            $the_query = new WP_Query( array(
                                'posts_per_page'=> -1,
                                'post_type'=>'product',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'product_cat',
                                        'field' => 'slug',
                                        'terms' => $service_attr_name,
                                    )
                                )
                            ) );
                            ?>
                            <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
                                <div class="charter-boats-item">
                                    <a href="<?php the_permalink(); ?>" class="thumb-wrap">
                                        <?php if ( has_post_thumbnail()) :?>
                                            <?php the_post_thumbnail(); ?>
                                        <?php endif;
                                        ?>
                                    </a>
                                    <div class="description-wrap">
                                        <h4>
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h4>
                                        <?php
                                        $current_url = $_SERVER['REQUEST_URI'];
                                        if (!preg_match('/(special-events|corporate-events|flotilla-charters)/', $current_url)) {
                                            ?>
                                            <div class="rate-rows">
                                                <?php
                                                $product_id = get_the_ID();
                                                $var_id = get_boat_variation_id($service_attr_obj->slug,$product_id);
                                                if(stripos($page_title, 'Bareboat') !== false) {
                                                    $low_rate = intval(get_field('_rate_1', $var_id)) * 6;
                                                    ?>
                                                    <p>From €<?php echo $low_rate; ?> per week</p>
                                                <?php } else {
                                                    $var_product = new WC_Product_Variation($var_id);
                                                    $rate_2 = get_field('_rate_2', $var_id);
                                                    $rate_1 = get_field('_rate_1', $var_id);
                                                    if(!$rate_2) {
                                                        $rate_2 = $rate_1;
                                                    }
                                                    ?>
                                                    <?php if($rate_2 > 0) { ?>
                                                        <p>Winter rate
                                                            €<?php echo $rate_2; ?>
                                                        </p>
                                                    <?php } ?>
                                                    <?php if($rate_1 > 0) { ?>
                                                        <p>Summer Rate
                                                            €<?php echo $rate_1; ?>
                                                        </p>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <div class="people-count">Max Persons: <?php the_field('_total_people', $var_id); ?></div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <div class="page-bottom_message">
                        <h4 class="message-title">Not sure which boat to go for?</h4>
                        <p class="message-text"><a href="/contact/">Contact us</a> and we’ll be more than happy to assist you!</p>
                    </div>
                    <!-- /single-info -->
                    <?php get_template_part('template-parts/destinations-banner'); ?>
                </div>
            </div>
        </div>
    </section>
<?php
$cleaning_price = sc_get_cleaning_price();
$cleaning_id = get_field('cleaning_product_id','option');
$cleaning_product = wc_get_product($cleaning_id);
$cleaning_title = $cleaning_product->get_title();
$deposite_type = get_field('deposite_type','option');
$deposit_percentage = 0;
$deposit_price = 0;
if($deposite_type) {
    $deposit_price = (float)get_field('deposit_price','option');
} else {
    $deposit_percentage = (int)get_field('deposit_percentage','option') / 100;
}
?>
    <div class="mobile-order_sidebar">
        <div class="mobile-order_top <?php echo $is_nonbookable ? 'non-bookable':''; ?>">
            <div class="order-top_left">
                <div class="top-left_row">
                    <p class="tr1">Pending Amount</p>
                    <p class="tr2 service-mobile_order-price">-</p>
                </div>
                <div class="top-left_row">
                    <p class="tr3">Deposit</p>
                    <?php if($deposite_type) { ?>
                        <p class="tr4"><?php echo wc_price($deposit_price); ?></p>
                    <?php } else { ?>
                        <p class="tr4"><?php echo $deposit_percentage; ?>%</p>
                    <?php } ?>
                </div>
            </div>
            <div class="order-buttons_wrap"  style="width: 100%;">
                <?php if ($is_nonbookable) { ?>
                    <a href="#" class="to-checkout_button availability-check_button service-btn">Request availability</a>
                <?php }
                if (!$is_nonbookable) {
                    if(woo_in_cart(get_the_ID())) {
                        $current_pid = get_the_ID();
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if($product_id == $current_pid) {

                                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="to-checkout_button custom-remove_button undisabled" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">Remove</a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_html__( 'Remove', 'woocommerce' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() ),
                                            esc_attr( $cart_item_key )
                                        ),
                                        $cart_item_key
                                    );
                                }
                            }
                        } ?>
                        <a href="/sc3/checkout" class="to-checkout_button undisabled">Continue</a>
                    <?php } else { ?>
                        <a href="#" class="to-checkout_button boat-type_checkout--button disabled mobile-btn">Continue</a>
                    <?php } ?>
                    <a href="#" class="to-checkout_button availability-check_button service-btn hidden">Request availability</a>
                <?php } ?>
            </div>
        </div>
        <div class="mobile-order_details">
            <p class="details-title">Order details:</p>
            <div class="mobile-order_details--wrap">
                <div class="details-left">
                    <?php if(!$is_nonbookable) { ?>
                        <p class="charter-type_current <?php if(woo_in_cart($itemInCart)) { echo 'charter-type_fixed'; }?>">
                            Select type
                        </p>
                    <?php } ?>
                    <?php if(woo_in_cart(get_the_ID())) { ?>
                        <p class="charter-time_fixed">Select date</p>
                    <?php }
                    else {?>
                        <p class="charter-time">Select date</p>
                    <?php } ?>
                    <p class="charter-passenger_amount">People</p>
                </div>
                <a href="#" class="edit-order">Edit</a>
            </div>
            <?php if(!$is_nonbookable) { ?>
                <div class="order-details_bottom">
                    <div class="details-bottom_row">
                        <p>Charter (+ tax)</p>
                        <p class="service-mobile_order-price">Select boat</p>
                    </div>
                    <div class="details-bottom_row medium-text cleaning-price">
                        <p>Online transaction charge</p>
                        <p>€<span><?php echo $cleaning_price; ?></span></p>
                    </div>
                    <div class="details-bottom_row mobile-deposit">
                        <p>Deposit</p>
                        <?php if($deposite_type) { ?>
                            <p><span><?php echo wc_price($deposit_price); ?></span></p>
                        <?php } else { ?>
                            <p><span><?php echo $deposit_percentage; ?>%</span></p>
                        <?php } ?>
                    </div>
                    <div class="details-bottom_row mobile-basefee">
                        <p>Pending Amount</p>
                        <p class="service-mobile_order-price">Select boat</p>
                    </div>
                </div>
            <?php } ?>
            <p class="bottom-details">
                <?php the_field('sidebar_boat_text', 'options'); ?>
            </p>
        </div>
    </div>
    <div class="mobile-order_form" data-cleaning="<?php echo $cleaning_price; ?>" data-deposit="<?php echo $deposite_type; ?>" data-depprice="<?php echo $deposit_price; ?>" data-deppercentage="<?php echo $deposit_percentage; ?>" data-currency="<?php echo get_woocommerce_currency_symbol(); ?>">
        <div class="mobile-order_form--people mobile-order_form--part">
            <div class="mobile-order_form--top">
                <a href="#" class="close-order_form nav-top-button"></a>
                <div class="mobile-order_form--title">Select number of people</div>
                <div class="mobile-order_form--step">
                    <p>Step &nbsp;</p>
                    <?php if (!$is_nonbookable) { ?>
                        <p>1/3</p>
                    <?php } else { ?>
                        <p>1/2</p>
                    <?php } ?>
                </div>
            </div>
            <div class="peoples-wrap_inner peoples-wrap_mobile">
                <div class="peoples-row">
                    <div class="left">
                        <h4>Adults</h4>
                        <p>Ages 13 or above</p>
                    </div>
                    <div class="right">
                        <input class="less-people less-people_adult disabled" name="adult_number" disabled value="-" readonly />
                        <input type="text" value="0" placeholder="0" class="poeple-summury people-calc" data-type="people" readonly />
                        <input class="more-people more-people_adult" value="+" readonly />
                    </div>
                </div>
                <div class="peoples-row">
                    <div class="left">
                        <h4>Children</h4>
                        <p>Ages 2-12</p>
                    </div>
                    <div class="right">
                        <input class="less-people less-people_children disabled" disabled value="-" readonly />
                        <input type="text" value="0" placeholder="0" name="children_number" class="poeple-children_summury people-calc" data-type="children" readonly />
                        <input class="more-people more-people_children" value="+" readonly />
                    </div>
                </div>
                <div class="peoples-row">
                    <div class="left">
                        <h4>Infants</h4>
                        <p>Under 2</p>
                    </div>
                    <div class="right">
                        <input class="less-people less-people_infants disabled" disabled value="-" readonly />
                        <input type="text" value="0" placeholder="0" name="infants_number" class="poeple-infants_summury people-calc" data-type="infants" readonly />
                        <input class="more-people more-people_infants" value="+" readonly />
                    </div>
                </div>
                <button class="to-fleettype_button to-fleettype_button--service mobile-order_form--button" disabled="disabled">Continue</button>
            </div>
        </div>
        <?php if (!$is_nonbookable) { ?>
            <div class="mobile-order_form--fleet mobile-order_form--part">
                <div class="mobile-order_form--top">
                    <a href="#" class="to_people-button nav-top-button"></a>
                    <div class="mobile-order_form--title">Select boat</div>
                    <div class="mobile-order_form--step">
                        <p>Step &nbsp;</p>
                        <p>2/3</p>
                    </div>
                </div>
                <div class="services-chooser filter-dropdown_wrap mobile_charter-type--dropdown">
                    <?php
                    $count = 1;
                    $post_slug = $post->post_name;
                    $the_query = new WP_Query( array(
                        'posts_per_page'=> -1,
                        'post_type'=>'product',
                        'tax_query'      => array( array(
                            'taxonomy'        => 'pa_service',
                            'field'           => 'slug',
                            'terms'           =>  array($service_attr_name),
                            'operator'        => 'IN',
                        ))
                    ));
                    while ($the_query -> have_posts()) : $the_query -> the_post();
                        $product_id = get_the_ID();
                        $product = wc_get_product( $product_id );
                        $variation_id = get_boat_variation_id( $service_attr_name, $product_id );
                        $is_boat_bookable = get_field('third_party_boat',get_the_ID());
                        $default_people = (int)get_post_meta($variation_id,'_default_people',true);
                        $default_people = $default_people > 0 ? $default_people : 0;
                        $excluded_dates_boat = [];
                        $excluded_dates_boat_day = [];
                        $excluded_dates_boat_evening = [];
                        if(array_key_exists($product_id, $excluded_dates)){
                            $excluded_dates_boat = $excluded_dates[$product_id];
                            if(array_key_exists('Day', $excluded_dates_boat)){
                                $excluded_dates_boat_day = $excluded_dates_boat['Day'];
                            }
                            if(array_key_exists('Evening', $excluded_dates_boat)){
                                $excluded_dates_boat_evening = $excluded_dates_boat['Evening'];
                            }
                        }
                        ?>
                        <div class="service-boat_mobile-item charter-type_item services-chooser_item chooser-item chooser-item_mobile charter-type_item-<?php the_title(); ?>"
                        data-persons="<?php the_field('persons_count', $product_id); ?>" data-bookable="<?php echo $is_boat_bookable ? 0 : 1; ?>"
                        data-booked='<?php echo json_encode($booked_days); ?>' variation_id="<?php echo $variation_id ?>" service_id="<?php echo $post_id; ?>"
                        data-booked-day='<?php echo json_encode($excluded_dates_boat_day); ?>' data-booked-evening='<?php echo json_encode($excluded_dates_boat_evening); ?>'>
                            <label for="<?php echo 'service-index-' . $count; ?>" class="servise-choser_label"><?php the_title(); ?></label>
                            <input type="hidden" class="charter-type_value" id="<?php echo 'service-index-' . $count; ?>"
                            data-default-ppl="<?php echo $default_people;?>" data-id="<?php echo $variation_id; ?>" value="<?php the_title(); ?>"  />
                        </div>
                        <?php
                        $count++;
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                <button class="to-mdate_button mobile-order_form--button" disabled="disabled">Continue</button>
            </div>
        <?php } ?>
        <div class="mobile-order_form--date mobile-order_form--part">
            <div class="mobile-order_form--top">
                <a href="#" class="to_mfleets-button nav-top-button"></a>
                <div class="mobile-order_form--title">Select your day</div>
                <div class="mobile-order_form--step">
                    <p>Final step</p>
                </div>
            </div>
            <button class="to-order_button mobile-order_form--button active">Continue</button>
        </div>
    </div>
    <section class="page-content availability-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="availability-wrap">
                        <div class="left">
                            <div class="availability-top" data-service="<?php echo $post_title; ?>">
                                <div class="availability-titles">
                                    <a href="#" class="back-from_availability"></a>
                                    <h1>Request availability</h1>
                                </div>
                                <?php if(!$is_nonbookable) { ?>
                                    <div class="availability-info_banner">This boat is owned by one of our selected trusted partners. We will check their availability based on the information provided by your good self and will and get back to you soon.</div>
                                <?php } else { ?>
                                    <div class="availability-info_banner">This service is a personalised yacht charter service which can be tailor made for your particular boating requirements. After filling your contact details below, we will contact you to discuss your preferences and provide you with a bespoke sailing charter solution.</div>
                                <?php } ?>
                            </div>
                            <div class="availability-details">
                                <h4>Personal details</h4>
                                <p>Please provide us with your contact details in the form below and we will get back to you shortly</p>
                            </div>
                            <?php echo do_shortcode('[contact-form-7 id="810" title="Check availability form" html_class="availability-form"]'); ?>
                        </div>
                        <div class="right availability-sidebar">
                            <div class="availability-sidebar_inner">
                                <h4 class="mobile-order_title">Order details</h4>
                                <div class="availability-inner_thumb">
                                    <?php if ( $image ) :?>
                                        <img src="<?php echo $image; ?>" alt="" />
                                    <?php endif; ?>
                                </div>
                                <div class="availability-inner_description">
                                    <h4><?php the_title(); ?></h4>
                                    <div class="availability-inner_details">
                                        <p class="top">Order details</p>
                                        <?php if(!$is_nonbookable) { ?>
                                            <p class="charter-type_aviability">Charter type</p>
                                        <?php }?>
                                        <p class="date-reciever_aviability">Date</p>
                                    </div>
                                    <div class="availability-inner_details">
                                        <p class="top">People</p>
                                        <p class="charter-passenger_amount"></p>
                                    </div>
                                    <?php
                           $is_nonbookable = false;
                           if(!empty($service_name)) {
                              $s = get_term_by('slug', $service_name, 'pa_service');
                              $s_id = $s->term_id;
                              $is_nonbookable = get_field('is_non-bookable',$s_id);
                           }
                           $data = sc_gather_data();
                           $prices = sc_get_order_totals($data);
                        ?>
                        <div <?php echo $is_nonbookable ? 'style="display:none"' : ''; ?> class="availability-inner_details price">
                           <p class="top">Price</p>
                           <div class="details-row">
                              <p>Day rate</p>
                              <p><?php echo wc_price($prices['boat-price']); ?></p>
                           </div>
                           <div class="details-row">
                              <p>Total</p>
                              <p>
                                 <?php echo wc_price($prices['boat-price']); ?>
                              </p>
                           </div>
                        </div>
                                </div>
                            </div>
                            <div class="page-bottom_message sidebar-message">
                                <a href="mailto:<?php the_field('email', 'options'); ?>" class="message-email"><?php the_field('email', 'options'); ?></a>
                                <a href="tel:<?php the_field('phone', 'options'); ?>" class="message-phone"><?php the_field('phone', 'options'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php get_footer(); ?>