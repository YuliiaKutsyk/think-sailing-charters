<?php
   get_header();

   $product_id = get_the_ID();
   $product = wc_get_product($product_id);
   $excluded_dates = get_option('sc_excluded_dates');

   $year = date('Y');

   $from_search = false;
   if(isset($_GET['from_search']) && $_GET['from_search'] == 1) {
    $from_search = true;
   }
   $service_name = '';
   $start_date = '';
   $end_date = '';
   $people_number = 0;
   $adults_number = 0;
   $children_number = 0;
   $infants_number = 0;
    $search_duration = 0;
	$service_cart_title = WC()->session->get( 'fleet_cat' );
   if($from_search) {
      if(isset($_SESSION['s_service_name'])) {
         $service_name = !empty($_SESSION['s_service_name']) ? $_SESSION['s_service_name'] : '';
      }
      if(isset($_SESSION['s_day'])) {
         $start_date = !empty($_SESSION['s_day']) ? $_SESSION['s_day'] : '';
      }
      if(isset($_SESSION['s_endday'])) {
         $end_date = !empty($_SESSION['s_endday']) ? $_SESSION['s_endday'] : '';
      }
      if(isset($_SESSION['s_people_number'])) {
         $people_number = !empty($_SESSION['s_people_number']) ? intval($_SESSION['s_people_number']) : 0;
      }

      if(isset($_SESSION['adult_number'])) {
         $adults_number = !empty($_SESSION['adult_number']) ? $_SESSION['adult_number'] : 0;
      }

      if(isset($_SESSION['children_number'])) {
         $children_number = !empty($_SESSION['children_number']) ? $_SESSION['children_number'] : 0;
      }

      if(isset($_SESSION['infants_number'])) {
         $infants_number = !empty($_SESSION['infants_number']) ? $_SESSION['infants_number'] : 0;
      }


   } else {
      if(isset($_GET['service']) && !empty($_GET['service'])) {
         $service_name = urldecode($_GET['service']);
      }
   }
   $day_diff = '';
   $date1 = null;
   $date2 = null;
   $date_hidden_start = '';
//   $is_summer = is_date_in_range($summer_start,$summer_end,date('d-m-Y'));
   if(!empty($start_date)) {
      $date1 = date_create($start_date . ' ' . date('Y'));
      $date_hidden_start = date('Y-m-d',strtotime($start_date));
   }
   if(!empty($start_date) && !empty($end_date)) {
      $date2 = date_create($end_date . ' ' . date('Y'));
      $day_diff = (int)date_diff($date1, $date2)->d + 1;
   }

//   if($date1) {
////      $is_summer = is_date_in_range($summer_start,$summer_end,date_format($date1, 'd-m-Y'));
//   }
   $is_third_party = get_field('third_party_boat');
?>
<section class="page-content <?php echo $from_search ? 'from-search ' : '';?>fleet-content single-content <?php echo $is_third_party ? 'third-party': ''; ?>">
   <div class="fleet-mobile_gallery">
      <div class="owl-carousel about-gallery_slider" id="about-gallery_slider">
         <?php $attachment_ids = $product->get_gallery_image_ids();
            foreach( $attachment_ids as $attachment_id ) { ?>
               <div class="slider-item">
                  <a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" class="about-gallery_item viewbox">
                  <?php echo wp_get_attachment_image($attachment_id, 'full'); ?>
                  </a>
               </div>
         <?php } ?>
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
                  <h1 class="product-single_title"><?php the_title(); ?></h1>
                  <?php
                     if(has_term('fleets','product_cat',get_the_ID())) {
                        if(get_field('boat_name')) {
                           echo '<span style="display: none;" class="boat-custom-name">' . get_field('boat_name') . '</span>';
                        }
                     }
                  ?>
               </div>
               <a href="#" class="back-link">Go back</a>
            </div>
            <div class="single-gallery">
               <?php $attachment_ids = $product->get_gallery_image_ids();
                  foreach( $attachment_ids as $attachment_id ) { ?>
                     <a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" class="viewbox">
                        <?php echo wp_get_attachment_image($attachment_id, 'full'); ?>
                     </a>
               <?php } ?>
               <a href="<?php echo wp_get_attachment_url( $attachment_id ); ?>" class="viewbox-button viewbox" id="viewbox-button">See more</a>
            </div>
            <div class="single-info">
               <?php
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
               <div class="single-booked-block" data-booked-day='<?php echo json_encode($excluded_dates_boat_day); ?>' data-booked-evening='<?php echo json_encode($excluded_dates_boat_evening); ?>' style="display: none;"></div>
               <div class="left">
                  <div class="single-description">
                     <div class="single-description_inner">
                        <?php the_content(); ?>
                     </div>
                     <a href="#" class="read-more">Read more</a>
                  </div>
                  <div class="single-feautures">
                        <?php if( have_rows('features_repeater') ){
                            while ( have_rows('features_repeater') ) { the_row();
                                ?>
                     <div class="feauture-item">
                        <img src="<?php echo get_sub_field('icon')['url']; ?>" alt="<?php echo get_sub_field('icon')['alt'] ?>" />
                        <h4><?php the_sub_field('title'); ?></h4>
                        <p><?php the_sub_field('description'); ?></p>
                     </div>
                     <?php }} ?>
                  </div>
                  <div class="single-accomodation">
                     <h4 class="accomodation-title">Accommodation</h4>
                     <div class="accomodation-wrap">
                        <?php if( have_rows('accommodation_repeater') ):
                           while ( have_rows('accommodation_repeater') ) : the_row();
                        ?>
                        <div class="accomodation-item">
                           <img src="<?php echo get_sub_field('icon')['url']; ?>" alt="icon">
                           <h4><?php the_sub_field('title'); ?></h4>
                           <p><?php the_sub_field('description'); ?></p>
                        </div>
                        <?php endwhile; endif; ?>
                     </div>
                  </div>
               </div>
               <!-- /left -->
               <div class="right">
                  <div class="order-sidebar">
                     <p class="order-sidebar_title">Order details:</p>
                     <form action="#" class="order-sidebar_form">
                         <?php

                         $attrs = wc_get_product_terms( $product_id, 'pa_service' );
                         $service_title = '';
                         $service_slug = '';
                         $sc_service_id = '';
                         $is_in_cart = woo_in_cart($product_id);
                         if(!empty($service_name)) {
                             $sc_service = get_term_by('slug', $service_name, 'pa_service');
                             $sc_service_id = $sc_service->term_id;
                             if($sc_service) {
                                 $service_title = $sc_service->name;
                                 $service_slug = $sc_service->slug;
                             }
                         }
                         $output_title = '';
                        foreach ($attrs  as $term) {
                            $is_nonbookable = get_field('is_non-bookable', 'term_' . $term->term_id);
                            if(!$is_nonbookable) {
                                $output_title = $term->name;
                                $output_slug = $term->slug;
                                $sc_service_id = $term->term_id;
                                $is_nonbookable = get_field('is_non-bookable', 'term_' . $term->term_id);
                                $variation_id = get_boat_variation_id($term->slug,$product_id);
                                break;
                            }}

                         $var_product_id = 0;
                         if($is_in_cart && !$from_search) {
                             if(!empty($service_cart_title)) {
                                 $output_title = $service_cart_title;
                                 $cart_service = get_term_by('slug',$output_title,'pa_service');
                                 if($cart_service) {
                                     $output_slug = $cart_service->slug;
                                     $var_product_id = get_boat_variation_id($output_slug,$product_id);
                                 }
                             }
                         } else {
                             if(!empty($service_title)) {
                                 $output_title = $service_title;
                                 $output_slug = $sc_service->slug;
                                 $sc_service_id = $term->term_id;
                                 $var_product_id = get_boat_variation_id($output_slug,$product_id);
                             }
                         }
                           if(!$var_product_id) {
                              $var_product_id = get_boat_variation_id('day-charters',$product_id);
                           }
                         ?>
                         <input type="hidden" class="sc_to_price" name="sc_people_total" value="<?php echo $people_number; ?>">
                         <input type="hidden" name="sc_people_adults" value="<?php echo $adults_number; ?>">
                         <input type="hidden" name="sc_people_children" value="<?php echo $children_number; ?>">
                         <input type="hidden" name="sc_people_infants" value="<?php echo $infants_number; ?>">
                         <input type="hidden" class="sc_to_price" name="sc_trip_start" value="<?php echo $date_hidden_start; ?>">
                         <input type="hidden" class="sc_to_price" name="sc_trip_end" value="<?php echo $end_date != '' ? date('Y-m-d',strtotime($end_date)) : $date_hidden_start; ?>">
                         <input type="hidden" class="sc_to_price" name="sc_trip_duration" value="<?php echo $day_diff == '' ? 1 : $day_diff; ?>">
                         <input type="hidden" class="sc_to_price" name="sc_variation_id" value="<?php echo $var_product_id; ?>">
                         <input type="hidden" class="sc_to_price" name="sc_service_id" value="<?php echo $sc_service_id != '' ? $sc_service_id : ''; ?>">
                         <input type="hidden" name="sc_service_name" value="<?php echo $service_title != '' ? $service_title : $output_title; ?>">

                        <div class="charter-input charter-type charter-sidebar-cats">
                           <div class="charter-type_current <?php if($is_in_cart) { echo 'charter-type_fixed'; } ?>">
                              <?php
                                 echo $output_title;
                                 $default_people = 0;
                                 $extra_price = 0;
                                 if(!$var_product_id) {
                                    $var_product_id = get_boat_variation_id('day-charters',$product_id);
                                 }
                                 $var_product = new WC_Product_Variation($var_product_id);
                              ?>
                              <input type="hidden" class="charter-type_value" data-slug="<?php echo $output_slug; ?>" data-termid="<?php echo $attrs[0]->term_id; ?>" data-bookable="<?php echo $is_nonbookable ? '0':'1'; ?>" value="<?php echo $output_title; ?>" />
                           </div>
                           <div class="charter-type_dropdown">
                              <?php
                                foreach ($attrs  as $term) {
                                 $attr = $term->slug;
                                 $is_nonbookable = get_field('is_non-bookable', 'term_' . $term->term_id);
                                 $variation_id = get_boat_variation_id($attr,$product_id);
                                    $booked_days = get_product_booked_days($product_id, $attr);
                                    //print_r($attr);
//                                    $booked_days = get_booked_days(null, null);
//                                    if ($product && $product->is_type('variable')) {
//                                        $booked_days = get_booked_days($variation_id, $attr);
//                                    }
                                 if(!$is_nonbookable) {
                              ?>
                              <div class="charter-type_item<?php echo $output_title == $term->name ? ' active' : ''; ?>"  data-slug="<?php echo $term->slug; ?>"
                              data-bookable="<?php echo $is_nonbookable ? '0':'1'; ?>" data-booked='<?php echo json_encode($booked_days); ?>'
                              variation_id="<?php echo $variation_id; ?>" service_id="<?php echo $term->term_id; ?>" max_people="<?php echo get_field('_total_people', $variation_id) ? get_field('_total_people', $variation_id) : get_field('_default_people', $variation_id); ?>">
                                  <?php echo $term->name; ?>
                                 <input type="hidden" class="charter-type_value" data-slug="<?php echo $term->slug; ?>" />
                              </div>
                              <?php }} ?>
                           </div>
                        </div>


                        <?php
                           $is_single = true;
                           $is_date_set = false;
                           $duration = 1;
                           $search_start_date = '';
                           $search_end_date = '';
                           if(!empty($start_date)) {
                              $search_start_date = date('Y-m-d',strtotime($start_date));
                              $is_date_set = true;
                           }
                           if(!empty($end_date)) {
                              $search_end_date = date('Y-m-d',strtotime($end_date));
                              $is_single = false;
                              $date1 = new DateTime($search_start_date);
                              $date2 = new DateTime($search_end_date);
                              $interval = $date1->diff($date2);
                              $duration = $interval->days + 1;
                           }
                           if(!$is_date_set) {
                              if(!empty($service_slug) && ($service_slug == 'multi-day-charters' || $service_slug == 'bareboat-charters')) {
                                 $is_single = false;
                              }
                           }
                        ?>
                        <div class="charter-input charter-time charter-time_single <?php echo $is_single ? '' : 'multi'; ?>" data-startdate="<?php echo $search_start_date; ?>" data-enddate="<?php echo $search_end_date; ?>" data-duration="<?php echo $duration; ?>">
                            <?php if($is_date_set) {
                              if($is_single) {
                                 echo $start_date;
                              } else {
                                 echo '<span class="date-from">From:<br>' . $start_date . '</span>';
                                 echo '<span class="date-to">To:<br>' . $end_date .  '</span>';
                              }
                           } else {
                                 if($is_single) {
                                    echo 'Select date';
                                 } else {
                                    echo '<span class="date-from">From</span><span class="date-to">To</span>';
                                 }
                              }
                           ?>
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
//                                global $product;
//
//                                $terms = get_the_terms( $product->get_id(), 'pa_service' );
//
//                                if ( $terms && ! is_wp_error( $terms ) ) {
//                                    foreach ( $terms as $term ) {
//                                        $term_meta = get_term_meta( $term->term_id, 'time_start', true );
//                                        if ( ! empty( $term_meta ) ) {
//                                            echo '<span>' . date( 'H:i', strtotime( $term_meta ) ) . '</span>';
//                                            break;
//                                        }
//                                    }
//                                }
                            ?>
                        </div>
                        <div class="charter-input charter-passenger" data-max="">
                           <?php if($people_number > 0) { ?>
                           <div class="charter-passenger_amount"><?php echo $people_number . ' People'; ?></div>
                           <?php } else { ?>
                           <div class="charter-passenger_amount">People</div>
                           <?php } ?>
                           <div class="peoples-wrap filter-dropdown_wrap peoples-wrap_inner">
                              <div class="peoples-row">
                                 <div class="left">
                                    <h4>Adults</h4>
                                    <p>Ages 13 or above</p>
                                 </div>
                                 <div class="right">
                                    <input class="less-people less-people_adult <?php echo $adults_number > 0 ? '' : 'disabled'; ?>" <?php echo $adults_number > 0 ? '' : 'disabled'; ?> value="-" readonly />
                                    <input type="text" value="<?php echo $adults_number; ?>" placeholder="0" max="<?php echo $default_total_people; ?>" class="poeple-summury people-calc poeple-summury_adult" data-type="people" readonly />
                                    <input class="more-people more-people_adult" value="+" readonly />
                                 </div>
                              </div>
                              <div class="peoples-row">
                                 <div class="left">
                                    <h4>Children</h4>
                                    <p>Ages 2-12</p>
                                 </div>
                                 <div class="right">
                                    <input class="less-people less-people_children <?php echo $children_number > 0 ? '' : 'disabled'; ?>" <?php echo $children_number > 0 ? '' : 'disabled'; ?> value="-" readonly />
                                    <input type="text" max="<?php echo $default_total_people; ?>" value="<?php echo $children_number; ?>" data-type="children" placeholder="0" class="poeple-children_summury people-calc" readonly />
                                    <input class="more-people more-people_children" value="+" readonly />
                                 </div>
                              </div>
                              <div class="peoples-row">
                                 <div class="left">
                                    <h4>Infants</h4>
                                    <p>Under 2</p>
                                 </div>
                                 <div class="right">
                                    <input class="less-people less-people_infants <?php echo $infants_number > 0 ? '' : 'disabled'; ?>" <?php echo $infants_number > 0 ? '' : 'disabled'; ?> value="-" readonly />
                                    <input type="text" max="<?php echo $default_total_people; ?>" value="<?php echo $infants_number; ?>" data-type="infants" placeholder="0" class="poeple-infants_summury people-calc" readonly />
                                    <input class="more-people more-people_infants" value="+" readonly />
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div <?php echo $is_nonbookable ? 'style="display: none;"' : ''; ?> class="charter-options">
                           <div class="charter-option_row">
                              <p>Charter (+ tax)</p>
                              <p class="medium-text boat-price">
                                 €<span>0</span>
                              </p>
                           </div>
                           <?php if(!$is_third_party) { ?>
                              <div class="charter-option_row">
                                 <p>Deposit</p>
                                 <p class="medium-text deposite-price">€<span></span></p>
                              </div>
                              <div class="charter-option_row">
                                 <p>Online transaction charge</p>
                                 <p class="medium-text cleaning-price">€<span></span></p>
                              </div>
                              <div class="charter-option_row">
                                 <p>Amount Payable Now</p>
                                 <p class="medium-text deposite-price payable">€<span>0</span></p>
                              </div>
                              <div class="charter-option_row">
                                 <p class="medium-text">Pending Amount</p>
                                 <p class="charter-row_price">
                                    € <span class="price-number_text">0</span>
                                 </p>
                              </div>
                           <?php } ?>
                        </div>
                        <?php
                           $is_filled = false;
                           if(!empty($service_name) && $people_number > 0 && !empty($start_date)) {
                              $is_filled = true;
                           }
                           echo '<input type="hidden" id="search-filled">';
                           if (get_field('third_party_boat')) { ?>
                              <a href="#" class="to-checkout_button availability-check_button <?php !$is_filled ? 'disabled' : ''; ?>">Request availability</a>
                        <?php } else {
                        ?>
                           <a href="/checkout/?add-to-cart=<?php echo $product_id; ?>" class="to-checkout_button cart-first_button <?php echo $from_search && $is_filled ? '': 'disabled';?>">Continue to checkout</a>
<!--                           <a href="#" class=" --><?php //echo $is_nonbookable ? '' : 'hidden'; ?><!-- to-checkout_button availability-check_button">Request availability</a>-->
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
            <!-- /single-info -->
            <div class="specification">
               <h4 class=  "spec-title">Yacht Specifications</h4>
               <div class="specification-wrap">
                  <?php if( have_rows('yacht_specifications_repeater') ): ?>
                  <?php while( have_rows('yacht_specifications_repeater') ): the_row(); ?>
                  <div class="column">
                     <div class="spec-description">
                        <h4><?php the_sub_field('title'); ?></h4>
                        <?php the_sub_field('description'); ?>
                     </div>
                     <a href="#" class="view-specification">View all</a>
                  </div>
                  <?php endwhile; ?>
                  <?php endif; ?>
               </div>
            </div>
            <!-- /specification -->
            <?php get_template_part('template-parts/services-banner'); ?>
         </div>
      </div>
   </div>
   </div>
</section>
<?php
if($from_search){
   $data = [
      'people_total' => $people_number ,
      'adults' => $adults_number,
      'children' => $children_number,
      'infants' => $infants_number,
      'trip_start' => $date_hidden_start,
      'trip_end' => empty($end_date)? $date_hidden_start : date('Y-m-d',strtotime($end_date)) ,
      'trip_duration' => $day_diff != "" ? $day_diff : 1,
      'service_id' => $sc_service_id,
      'variation_id' => $var_product_id
  ];
//   print_r($data);
   $prices = sc_get_order_totals($data);
   // print_r($prices);
}

?>
<div class="mobile-order_sidebar">
   <div class="mobile-order_top">
      <?php if(!$is_third_party) { ?>
         <div class="order-top_left">
            <div class="top-left_row">
               <p class="tr1">Total Price</p>
               <p class="tr2 mobile-total"><span class="service-mobile_order-price"><?php echo ($from_search ? wc_price($prices['boat-price'] + $prices['cleaning-price']) : ''); ?></span></p>
            </div>
            <div class="top-left_row">
               <p class="tr3">Deposit</p>
               <p class="tr4 mobile-deposit "><span><?php echo ($from_search ? wc_price($prices['deposite-price']) : ''); ?></span></p>
            </div>
         </div>
      <?php } ?>
      <div class="order-buttons_wrap flex"  style="width: 100%;">
         <?php if (get_field('third_party_boat')) { ?>
             <button class="request-av-more"></button>
            <a href="#" class="to-checkout_button availability-check_button">Request availability</a>
         <?php } else {
            $is_filled = false;
            if(!empty($service_name) && $people_number > 0 && !empty($start_date)) {
               $is_filled = true;
            }
         ?>
            <a href="#" class="to-checkout_button cart-first_button <?php echo $from_search && $is_filled ? '' : 'disabled'; ?>">Continue</a>
         <?php } ?>
      </div>
   </div>
   <div class="mobile-order_details">
      <p class="details-title">Order details:</p>
      <div class="mobile-order_details--wrap">
         <div class="details-left">
            <p class="charter-type_current <?php if(woo_in_cart($product_id)) { echo 'charter-type_fixed'; }?>">
               <?php if(!empty($service_title)) {
                  echo $service_title;
               } else {
                  echo 'Select type';
               } ?>
            </p>
            <p class="charter-time">
            <?php
               if(empty($start_date)) {
                  echo 'Select date';
               } else {
                  echo $start_date;
               }
            ?>
            </p>
            <?php if($people_number > 0) { ?>
            <p class="charter-passenger_amount"><?php echo $people_number . ' People'; ?></p>
            <?php } else { ?>
            <p class="charter-passenger_amount">People</p>
            <?php } ?>
         </div>
         <a href="#" class="edit-order boat">Edit</a>
      </div>
      <div class="order-details_bottom">
         <div class="details-bottom_row charter-price">
            <p>Charter (+ tax)</p>
            <p><span class="service-mobile_order-price"><?php echo ($from_search ? wc_price($prices['boat-price']) : ''); ?></span></p>
         </div>
         <?php if(!$is_third_party) { ?>
            <div class="details-bottom_row">
               <p>Online transaction charge</p>
               <p class="cleaning-price"><?php echo ($from_search ? wc_price($prices['cleaning-price']) : ''); ?></p>
            </div>
            <div class="details-bottom_row mobile-deposit">
               <p>Deposit</p>
               <p><span><?php echo ($from_search ? wc_price($prices['deposite-price']) : ''); ?></span></p>
            </div>
            <div class="details-bottom_row mobile-basefee">
               <p>Pending Amount</p>
               <p><span class="service-mobile_order-price"><?php echo ($from_search ? wc_price($prices['charter-row_price']) : ''); ?></span></p>
            </div>
         <?php } ?>
      </div>
      <p class="bottom-details">
         <?php the_field('sidebar_boat_text', 'options'); ?>
      </p>
   </div>
</div>
<?php
   $cleaning_price = sc_get_cleaning_price();
   $deposite_type = get_field('deposite_type','option');
   $new_total = 0;
   $deposit_percentage = 0;
   $deposit_price = 0;
   if($deposite_type) {
      $deposit_price = (float)get_field('deposit_price','option');
   } else {
      $deposit_percentage = (int)get_field('deposit_percentage','option') / 100;
   }
?>
<div class="mobile-order_form" data-cleaning="<?php echo $cleaning_price; ?>" data-deposit="<?php echo $deposite_type; ?>" data-depprice="<?php echo $deposit_price; ?>" data-deppercentage="<?php echo $deposit_percentage; ?>" data-currency="<?php echo get_woocommerce_currency_symbol(); ?>">
    <div class="mobile-order_form--fleet mobile-order_form--part">
         <div class="mobile-order_form--top">
            <a href="#" class="close-order_form nav-top-button"></a>
            <div class="mobile-order_form--title">Select charter type</div>
            <div class="mobile-order_form--step">
               <p>Step &nbsp;</p>
               <p>1/3</p>
            </div>
         </div>
         <div class="services-chooser filter-dropdown_wrap mobile_charter-type--dropdown">
            <?php
               $count = 1;
               foreach ($attrs  as $term) {
                  $attr = $term->slug;
                  $service_obj_id =  $term->term_id;
                  $is_nonbookable = get_field('is_non-bookable',$service_obj_id);
                  $var_product_id = get_boat_variation_id($attr,$product_id);
                  // $var_product = new WC_Product_Variation($var_product_id);
                  // $var_price = $var_product->get_regular_price();
                  // $var_winter_price = $var_product->get_sale_price();
                  // if(!$var_winter_price) {
                  //    $var_winter_price = $var_price;
                  // }
                  // if(!$is_summer) {
                  //    $var_price = $var_winter_price;
                  // }
                  $default_people = (int)get_post_meta($var_product_id,'_default_people',true);
                  $total_people = (int)get_post_meta($var_product_id,'_total_people',true);
                  // $extra_price = (int)get_post_meta($var_product_id,'_extra_person_price',true);
                  $default_people = $default_people > 0 ? $default_people : 0;
                  // $extra_price = $extra_price > 0 ? $extra_price : 0;
                  // $nightrate_price = (int)get_post_meta($var_product_id,'_nightrate',true);
                  $variation_id = get_boat_variation_id($term->slug,$product_id);
            ?>
            <?php
            $is_nonbookable = get_field('is_non-bookable', 'term_' . $term->term_id);
            if(!$is_nonbookable) { ?>
            <div class="charter-type_item services-chooser_item services-chooser_item__mobile chooser-item chooser-item_mobile charter-type_item-<?php echo $term->name; ?>
                <?php echo ($attr == 'multi-day-charters' || $attr == 'bareboat-charters') ? 'md': ''; ?>
                <?php echo $service_slug == $term->slug ? 'active': ''; ?>" data-slug="<?php echo $term->slug; ?>" data-bookable="<?php echo $is_nonbookable ? '0':'1'; ?>"
                max_people="<?php echo $total_people; ?>"
                variation_id="<?php echo $variation_id; ?>"
                service_id="<?php echo $term->term_id; ?>"
                data-booked='<?php echo json_encode($booked_days); ?>'>
                <label data-bookable="<?php echo $is_nonbookable ? '0':'1'; ?>"
                       for="<?php echo 'service-index-' . $count; ?>"
                       class="servise-choser_label <?php echo $service_slug == $term->slug ? 'active': ''; ?>">
                    <?php echo $term->name; ?></label>

                <input type="checkbox" id="<?php echo 'service-index-' . $count; ?>" class="service-checkbox charter-type_value" data-total-people="<?php echo $total_people; ?>"
                value="<?php echo $term->name; ?>"
                data-termid="<?php echo $term->term_id; ?>"
                data-bookable="<?php echo $is_nonbookable ? '0':'1'; ?>"
                data-default-ppl="<?php echo $default_people;?>"
                data-price="<?php echo $var_price; ?>"
                data-id="<?php echo $var_product_id; ?>"
                data-extra="<?php echo $extra_price; ?>"
                data-nightrate="<?php echo $nightrate_price; ?>" />
            </div>
            <?php $count++; } ?>
            <?php } ?>
         </div>
         <button class="to-fleettype_button boat mobile-order_form--button">Continue</button>
      </div>
   <div class="mobile-order_form--people mobile-order_form--part">
      <div class="mobile-order_form--top">
         <a href="#" class="to_mfleets-button nav-top-button"></a>
         <div class="mobile-order_form--title">Select number of people</div>
         <div class="mobile-order_form--step">
            <p>Step &nbsp;</p>
            <p>2/3</p>
         </div>
      </div>
      <div class="peoples-wrap_inner peoples-wrap_mobile">
         <div class="peoples-row">
            <div class="left">
               <h4>Adults</h4>
               <p>Ages 13 or above</p>
            </div>
            <div class="right">
               <input class="less-people less-people_adult <?php echo $adults_number > 0 ? '' : 'disabled'; ?>"  <?php echo $adults_number > 0 ? '' : 'disabled'; ?> value="-" readonly />
               <input type="text" value="<?php echo $adults_number; ?>" placeholder="0" class="poeple-summury people-calc" name="adult_number" readonly data-type="people" max="<?php echo $default_total_people; ?>" />
               <input class="more-people more-people_adult" value="+" readonly />
            </div>
         </div>
         <div class="peoples-row">
            <div class="left">
               <h4>Children</h4>
               <p>Ages 2-12</p>
            </div>
            <div class="right">
               <input class="less-people less-people_children  <?php echo $children_number > 0 ? '' : 'disabled'; ?>"  <?php echo $children_number > 0 ? '' : 'disabled'; ?> value="-" readonly />
               <input type="text" value="<?php echo $children_number; ?>" placeholder="0" class="poeple-children_summury people-calc" readonly name="children_number" data-type="children" max="<?php echo $default_total_people; ?>" />
               <input class="more-people more-people_children" value="+" readonly />
            </div>
         </div>
         <div class="peoples-row">
            <div class="left">
               <h4>Infants</h4>
               <p>Under 2</p>
            </div>
            <div class="right">
               <input class="less-people less-people_infants <?php echo $infants_number > 0 ? '' : 'disabled'; ?>" <?php echo $infants_number > 0 ? '' : 'disabled'; ?> value="-" readonly />
               <input type="text" value="<?php echo $infants_number; ?>" placeholder="0" class="poeple-infants_summury people-calc" readonly name="infants_number" data-type="infants" max="<?php echo $default_total_people; ?>" />
               <input class="more-people more-people_infants" name="infants_number" value="+" readonly />
            </div>
         </div>
      <button class="to-mdate_button mobile-order_form--button" disabled="disabled">Continue</button>
      </div>
   </div>
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
                  <div class="availability-top">
                     <div class="availability-titles">
                        <a href="#" class="back-from_availability"></a>
                        <h1>Request availability</h1>
                     </div>
                     <div class="availability-info_banner">This boat is owned by one of our selected trusted partners. We will check their availability based on the information provided by your good self and will and get back to you soon.</div>
                  </div>
                  <div class="availability-details">
                     <h4>Personal details</h4>
                  </div>
                  <?php echo do_shortcode('[contact-form-7 id="810" title="Check availability form" html_class="availability-form"]'); ?>
               </div>
               <div class="right availability-sidebar">
                  <div class="availability-sidebar_inner">
                     <h4 class="mobile-order_title">Order details</h4>
                     <div class="availability-inner_thumb">
                        <?php if ( has_post_thumbnail()) :?>
                        <?php the_post_thumbnail(); ?>
                        <?php endif; ?>
                     </div>
                     <div class="availability-inner_description">
                        <h4><?php the_title(); ?></h4>
                        <div class="availability-inner_details">
                           <p class="top">Order details</p>
                           <p class="charter-type_aviability"><?php echo ($from_search && !empty($service_title)) ? 'Charter type: ' . $service_title : 'Charter type'; ?></p>
                           <p class="date-reciever_aviability"><?php echo ($from_search && !empty($start_date)) ? 'Date: ' . $start_date : 'Date'; ?></p>
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
                              <p class="request-boat-price"><?php echo wc_price($prices['boat-price']); ?></p>
                           </div>
                           <div class="details-row nightrate" style="display:none;">
                              <p>Night rate</p>
                              <p>€ <?php the_field('night_rate'); ?></p>
                           </div>
                           <?php if(!$is_third_party) { ?>
                              <div class="details-row">
                                 <p>Online transaction charge</p>
                                 <p>€ <?php echo sc_get_cleaning_price(); ?></p>
                              </div>
                              <div class="details-row">
                                 <p>Deposit</p>
                                 <p>
                                    <?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
                                    <?php echo wc_price( $price ); ?>
                                 </p>
                              </div>
                           <?php } ?>
                           <div class="details-row">
                              <p>Total</p>
                              <p class="request-boat-price">
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