<?php
   /**
    * Checkout Form
    *
    * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
    *
    * HOWEVER, on occasion WooCommerce will need to update template files and you
    * (the theme developer) will need to copy the new files to your theme to
    * maintain compatibility. We try to do this as little as possible, but it does
    * happen. When this occurs the version of the template file will be bumped and
    * the readme will list any important changes.
    *
    * @see https://docs.woocommerce.com/document/template-structure/
    * @package WooCommerce\Templates
    * @version 3.5.0
    // */
   $service_name = '';
   $service_title = '';

   foreach( WC()->cart->get_cart() as $cart_item ) {
      $variation_id = $cart_item['data']->get_id();
      $product = wc_get_product($variation_id);
      if($product->is_type('variation')) {
         $attrs = wc_get_product_variation_attributes( $variation_id );
         $service_name = $attrs['attribute_pa_service'];
         break;
      }
   }
   if(!empty($service_name)) {
      $service = get_term_by('slug',$service_name,'pa_service');
      $food_cats = get_field('services_menus','term_' . $service->term_id);
   }

   $is_food_in_cart = sc_check_category_in_cart('food');
   $is_dest_enabled = get_field('service_dest', 'term_' . $service->term_id);

   $tomorrow = strtotime('tomorrow');
   $is_tomorrow = false;
//   $booking_start = sc_get_booking_data()['start'];
   $booking_start = WC()->session->get('s_day_f');
   if (date('Y-m-d', $tomorrow) == $booking_start) {
      $is_tomorrow = true;
   }

?>
<div class="container">
   <div class="row">
      <div class="col-md-7 col-lg-7">
         <div class="checkout-undertitle" style="display:none;">
            <?php if($is_tomorrow) { ?>
               <div class="checkout-undertitle_info tomorrow">
                  <p>Since your booking is being received at a short notice, this booking will be subject to further confirmation.</p>
               </div>
            <?php } ?>
            <div class="checkout-undertitle_info">
               <p><?php the_field('grey_background_message_1'); ?></p>
            </div>
         </div>
         <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
               <?php
                  $people = sc_get_booking_people();
                  if(intval($people['infants']) > 0) {
               ?>
                  <input type="hidden" name="booking_infants" value="<?php echo $people['infants']; ?>">
               <?php
                  }
               ?>
            <input type="hidden" name="order_service_type" value="<?php echo $service_name; ?>">
            <?php if ( $checkout->get_checkout_fields() ) : ?>
            <div class="multistep-checkout">
               <div class="multistep-checkout__step active details">
                  <?php if($is_dest_enabled) { ?>
                     <div class="checkbox_titles">
                        <div class="checkbox-titles_wrap">
                           <h4 class="checkout-step_title"><?php the_field('destinations_form_title'); ?></h4>
                           <p><?php the_field('destinations_form_undertitle'); ?></p>
                        </div>
                        <a href="#" class="destinatins-checkbox_toggle checkbox-toggle"></a>
                     </div>
                  <?php } ?>
                  <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                  <div class="col2-set" id="customer_details">
                     <div class="col-1">
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                     </div>
                     <?php if($is_dest_enabled) { ?>
                        <div class="checkout-undertitle">
                           <div class="checkout-undertitle_info">
                              <p><?php the_field('grey_background_message_2'); ?></p>
                           </div>
                        </div>
                     <?php } ?>
                     <?php
                        $booking_people = sc_get_booking_people();
                        $booked_adult = $booking_people['adult'];
                        $booked_children = $booking_people['children'];
                        if(get_field('services_menus', 'term_' . $service->term_id)){
                     ?>
                     <div class="food-container" data-adult="<?php echo $booked_adult; ?>" data-children="<?php echo $booked_children; ?>">
                        <div class="food-titles checkbox_titles">
                           <div class="checkbox-titles_wrap">
                              <h4 class="checkout-step_title"><?php the_field('food_&_drinks_title'); ?></h4>
                              <p><?php the_field('food_&_drinks_undertitle'); ?></p>
                           </div>
                           <a href="#" class="food-checkbox_toggle checkbox-toggle <?php echo $is_food_in_cart ? 'active' : ''; ?>"></a>
                           <input type="hidden" name="is_food" value="0">
                        </div>
                        <div class="food-content_inner <?php echo $is_food_in_cart ? 'active' : ''; ?>">
                           <div class="food-titles-inner checkbox_titles">
                              <div class="checkbox-titles_wrap">
                                 <h4 class="checkout-step_title"><?php the_field('food_&_drinks_not_sure_title'); ?></h4>
                                 <p><?php the_field('food_&_drinks_not_sure_undertitle'); ?></p>
                              </div>
                              <a href="#" class="menu-checkbox_toggle checkbox-toggle"></a>
                              <input type="hidden" name="is_menu" value="0">
                           </div>
                           <div class="food-titles-menu checkbox_titles sure-toggle_visibility">
                              <div class="checkbox-titles_wrap">
                                 <h4 class="checkout-step_title--small"><?php the_field('food_&_drinks_oprion_title'); ?></h4>
                                 <p><?php the_field('food_&_drinks_oprion_undertitle'); ?></p>
                              </div>
                           </div>
                           <?php if($food_cats && $service_name != 'multi-day-charters' && $service_name != 'bareboat-charters') { ?>
                              <div class="checkout-menu_list checkout-menu_list_day sure-toggle_visibility">
                                 <?php
                                    $i = 0;
                                    foreach($food_cats as $cat_id) {
                                    	$cat = get_term($cat_id);
                                    	$cat_menu = get_field('cat_menu','term_' . $cat_id);
                                       $is_active = sc_check_category_in_cart($cat->slug);
                                 ?>
                                 <div class="menu-item ">
                                    <h4><?php echo $cat->name; ?></h4>
                                    <p><?php echo category_description( $cat_id ); ?></p>
                                    <a href="<?php echo $cat_menu['url']; ?>" class="download-button" download>Download menu (<?php echo human_filesize($cat_menu['filesize']); ?>)</a>
                                 </div>
                                 <?php $i++; } ?>
                              </div>
                           <?php
                              }
                              if($service_name == 'multi-day-charters' || $service_name == 'bareboat-charters') {
                              	$cat_id = get_field('services_menus', 'term_' . $service->term_id);

                              	$cat_id = $cat_id[0];
                              	$category = get_term($cat_id);

                              	$cat_menu = get_field('cat_menu','term_' . $cat_id);

                           ?>
                              <div class="checkout-menu_list checkout-menu_list_multiday sure-toggle_visibility">
                                 <div class="menu-item ">
                                    <h4><?php echo $category->name; ?></h4>
                                    <p><?php echo category_description( $cat_id ); ?></p>
                                    <a href="<?php echo $cat_menu['url']; ?>" class="download-button" download>Download menu (<?php echo human_filesize($cat_menu['filesize']); ?>)</a>
                                 </div>
                              </div>
                           <?php } ?>
                           <input type="hidden" value="" class="products-id_input" name="products-id_input" />
                           <?php if($food_cats && $service_name != 'multi-day-charters' && $service_name != 'bareboat-charters') { ?>
                              <div class="menu-content_holder menu-content_holder-day sure-toggle_visibility">
                                 <?php
                                    $i = 0;
                                    foreach($food_cats as $cat_id) {
                                    	$cat = get_term($cat_id);
                                    	$cat_menu = get_field('cat_menu','term_' . $cat_id);
                                       $is_active = sc_check_category_in_cart($cat->slug);
                                    ?>
                                 <div class="checkout-menu_content <?php echo $is_active ? 'active':''; ?>">
                                    <h4>Select one of the following <?php echo $cat->name; ?> options</h4>
                                    <p>Guests may select 1 common menu from the options below, however, we would be able to accomodate the selection of different main courses per head. Please leave a note if so. * Children (12 and under) are charged <?php echo 100 - intval(get_field('discount_for_children','option')); ?>% of the price.</p>
                                    <div class="foods-list">
                                       <?php $the_query = new WP_Query( array(
                                          'posts_per_page'=> -1,
                                          'post_type'=>'product',
                                          'order' => 'ASC',
                                          'tax_query' => array(
                                             array(
                                                 'taxonomy' => 'product_cat',
                                                 'field' => 'id',
                                                 'terms' => $cat_id
                                             )
                                         ),
                                          ) ); ?>
                                       <?php while ($the_query -> have_posts()) : $the_query -> the_post();
                                          $product_id = get_the_ID();
                                          $in_cart = woo_in_cart($product_id);
                                          $product = wc_get_product($product_id);
                                          $adult_q = 0;
                                          $children_q = 0;
                                          if($in_cart) {
                                             foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                                if($cart_item['product_id'] == $product_id) {
                                                   $people = sc_get_booking_people();
                                                   $adult_q = $people['adult'];
                                                   $children_q = $people['children'];
                                                }
                                             }
                                          }
                                          ?>
                                       <li class="food-list_item <?php if($in_cart) { echo "active"; } ?>" data-product-id="<?php echo $product_id; ?>">
                                          <?php the_title(); ?> &nbsp;
                                          <p>
                                             <?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
                                             (<?php echo wc_price( $price ); ?> Per Person)
                                          </p>
                                       </li>
                                       <?php endwhile; ?>
                                       <?php wp_reset_postdata(); ?>
                                    </div>
                                 </div>
                                 <?php $i++; }
                                    $current_cat_id = get_queried_object_id();
                                    $kids = get_terms([
                                    'taxonomy' => get_queried_object()->taxonomy,
                                    'parent'   => $current_cat_id,
                                    ]);
                                	?>
                              </div>
                           <?php } ?>
                           <?php
                              if($service_name == 'multi-day-charters' || $service_name == 'bareboat-charters') {
                              	$days_n = 2;
                              	$day = 1;
                            ?>
                           <div class="menu-content_holder menu-content_holder-multiday sure-toggle_visibility">
                              <div class="checkout-menu_content checkout-menu_content-multy">
                                 <h4><?php the_field('multy-day_menu_title'); ?></h4>
                                 <p><?php the_field('multy-day_menu_title_undertitle'); ?></p>
                                 <div class="miltyday-foods_table">
                                    <div class="multyday-table_header">
                                       <?php for($i = 1; $i <= $days_n; $i++) { ?>
                                       <li <?php echo $day == 1 ? 'class="current"' : ''; ?>>Day <?php echo $day; ?></li>
                                       <?php $day++; } ?>
                                    </div>
                                    <?php
                                       $day = 1;
                                       for($i = 1; $i <= $days_n; $i++) {
                                   	?>
	                                    <div class="multyday-foods_content multyday-foods_day<?php echo $day; ?>">
	                                       <?php
	                                          $md_cats = get_terms([
	                                              'taxonomy' => 'product_cat',
	                                              'parent'   => $cat_id,
	                                          ]);
	                                          foreach($md_cats as $cat) {
	                                          ?>
	                                       <div class="foods-list foods-list_multy">
	                                          <h4 class="meal-type"><?php echo $cat->name; ?></h4>
	                                          <?php $the_query = new WP_Query( array(
	                                             'posts_per_page'=> -1,
                                                'order' => 'ASC',
	                                             'post_type'=>'product',
	                                                'tax_query' => array(
	                                                 'relation' => 'AND',
	                                                   array(
	                                                       'taxonomy' => 'product_cat',
	                                                       'field' => 'id',
	                                                       'terms' => $cat->term_id
	                                                   )
	                                               ),
	                                             ) ); ?>
	                                          <?php
	                                          	while ($the_query -> have_posts()) : $the_query -> the_post();
                                            		$product_id = get_the_ID();
			                                       $in_cart = woo_in_cart($product_id);
			                                       $product = wc_get_product($product_id);
			                                       $adult_q = 0;
                                                $children_q = 0;
			                                       if($in_cart) {
                                                   foreach ( WC()->cart->get_cart() as $cart_item ) {
                                                      if($cart_item['product_id'] == $product_id) {
                                                         $people = sc_get_booking_people();
                                                         $adult_q = $people['adult'];
                                                         $children_q = $people['children'];
                                                         $product_q = $cart_item['day_' . $day];
                                                      }
                                                   }
			                                       }
	                                            ?>
	                                          <li class="food-list_item <?php if($in_cart && $product_q) { echo "active"; } ?>" data-product-id="<?php echo $product_id; ?>">
                                                <?php the_title(); ?> &nbsp;
                                                <p>
                                                   <?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
                                                   (<?php echo wc_price( $price ); ?> Per Person)
                                                </p>
	                                          </li>
	                                          <?php endwhile; ?>
	                                          <?php wp_reset_postdata(); ?>
	                                       </div>
	                                       <?php } ?>
	                                    </div>
                                    <?php $day++; } ?>
                                 </div>
                              </div>
                           </div>
                           <?php } ?>
                           <input type="text" class="menu-note sure-toggle_visibility" name="menu_note" placeholder="Leave a note …" value="" />
                           <div class="checkout-undertitle checkout-undertitle_menu sure-toggle_visibility">
                              <div class="checkout-undertitle_info">
                                 <p><?php the_field('food_&_drinks_grey_background_message'); ?></p>
                              </div>
                           </div>
                        </div>
                        <!-- /food-content_inner -->
                     </div>
                     <?php } ?>
                     <!-- /food-container -->
                     <div class="col-2">
                        <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                     </div>
                  </div>
                  <div class="to-payment-wrapper">
                     <div class="to-payment-btn">Go to payment</div>
                  </div>
               </div>
               <div class="multistep-checkout__step payment">
                  <div class="to-details-btn">Back to details</div>
                  <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
               </div>
            </div>
            <?php endif; ?>
            <?php if ( ! defined( 'ABSPATH' ) ) {
               exit;
               }

               do_action( 'woocommerce_before_checkout_form', $checkout );

               // If checkout registration is disabled and not logged in, the user cannot checkout.
               if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
               echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
               return;
               }

               ?>
         </form>
         <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
      </div>
      <div class="col-md-5 col-lg-4">
            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
            <div id="order_review" class="woocommerce-checkout-review-order">
               <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>
            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
      </div>
   </div>
</div>

<div class="cmp">
   <div class="cmp__inner">
      <div class="cmp__close"></div>
      <div class="cmp__text">After change menu you food items in cart will be lost. Are you sure you want to change food menu?</div>
      <div class="cmp__btn">OK</div>
   </div>
</div>