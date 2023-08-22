<?php
/** Template Name: Search */
get_header();
?>
<?php

	// Get service from search
	if(isset($_GET['service']) && !empty($_GET['service'])) {
		$_SESSION['s_service_name'] = $_GET['service'];
	} else {
		unset($_SESSION['s_service_name']);
	}

	// Get people from search
	if(isset($_GET['people']) && !empty($_GET['people'])) {
		$total_p = explode(',',$_GET['people']);
		$_SESSION['s_people_number'] = array_sum($total_p);
		if(!empty($total_p)) {
			$_SESSION['adult_number'] = (int)$total_p[0] > 0 ? $total_p[0] : 0;
			$_SESSION['children_number'] = (int)$total_p[1] > 0 ? $total_p[1] : 0;
			$_SESSION['infants_number'] = (int)$total_p[2] > 0 ? $total_p[2] : 0;
		}
		else {
			unset($_SESSION['adult_number']);
			unset($_SESSION['s_people_number']);
			unset($_SESSION['adult_number']);
			unset($_SESSION['children_number']);
			unset($_SESSION['infants_number']);
		}
	} else {
		unset($_SESSION['s_people_number']);
		unset($_SESSION['adult_number']);
		unset($_SESSION['children_number']);
		unset($_SESSION['infants_number']);
	}

	// Get date from search
	if(isset($_GET['start']) && !empty($_GET['start']) && ($_GET['start'] != 'false')) {
		$_SESSION['s_day'] = date('F j', strtotime($_GET['start']));
		$_SESSION['s_day_f'] = date('Y-m-d', strtotime($_GET['start']));
//		$_SESSION['mvvwb_start'] = $_GET['start'];
	} else {
		unset($_SESSION['s_day']);
	}
	if(isset($_GET['duration']) && !empty($_GET['duration']) && $_GET['duration'] > 1) {
		$end_date = new DateTime(date('F j', strtotime($_GET['start'])));
		$end_date->modify('+' . ($_GET['duration'] - 1) .' day');
		$_SESSION['s_endday'] = $end_date->format('F j');
		$_SESSION['s_endday_f'] = $end_date->format('Y-m-d');
		$_SESSION['s_duration'] = $_GET['duration'];
	} else {
		unset($_SESSION['s_endday']);
		unset($_SESSION['s_duration']);
	}

	$service_name = isset($_SESSION['s_service_name']) ? $_SESSION['s_service_name'] : "";
	$fleet_name = isset($_SESSION['s_boat_name']) ? $_SESSION['s_boat_name'] : "";
	$people_number = isset($_SESSION['s_people_number']) ? (int)$_SESSION['s_people_number'] : 0;
	$start_date = (isset($_SESSION['s_day']) && $_SESSION['s_day'] != false) ? $_SESSION['s_day'] : "";
	$start_date_f = (isset($_SESSION['s_day_f']) && $_SESSION['s_day_f'] != false) ? $_SESSION['s_day_f'] : "";
	$end_date = isset($_SESSION['s_endday']) ? $_SESSION['s_endday'] : "";
	$end_date_f = isset($_SESSION['s_endday_f']) ? $_SESSION['s_endday_f'] : "";
	$adults_number = isset($_SESSION['adult_number']) ? $_SESSION['adult_number'] : 0;
	$children_number = isset($_SESSION['children_number']) ? $_SESSION['children_number'] : 0;
	$infants_number = isset($_SESSION['infants_number']) ? $_SESSION['infants_number'] : 0;
	$p_summ = $adults_number + $children_number + $infants_number;
	$people_number = $p_summ > 0 ? $p_summ : 0;
  	$cleaning_id = get_field('cleaning_product_id','option');
	$s = get_search_query();
?>
<section class="search-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="search-top">
					<div class="mobile-search_preform">
						<div class="search-preform_info">
							<div>Select people</div>
							<div>Select day</div>
						</div>
						<a href="#" class="search-submit_mobile"></a>
					</div>
					<form method="post" action="<?php echo home_url(); ?>" role="search" class="main-banner_filter  search-page_filter">
						<div class="filter-options_wrap filter-options_wrap--search">
							<input class="search-input hidden-input" type="search" name="s" placeholder="<?php _e( 'To search, type and hit enter.', 'wpeasy' ); ?>">
							<input class="hidden-input people-input_holder" type="text" name="peoplesAmount" />
							<div class="filter-wrap filter-wrap_service">
								<?php if(empty($service_name)) { ?>
								<div class="filter-div filter-service search-service_recieve">Services</div>
								<?php
									} else {
                                        $service = get_term_by('slug', $service_name, 'pa_service');
								?>
									<div class="filter-div filter-service search-service_recieve"><?php echo $service->name; ?></div>
								<?php
									}
								?>
								<div class="mobile-order_form--top">
									<a href="#" class="close-order_form nav-top-button"></a>
									<div class="mobile-order_form--title">Select charter type</div>
									<div class="mobile-order_form--step">
										<p>Step &nbsp;</p>
										<p>1/3</p>
									</div>
								</div>
								<div class="services-chooser filter-dropdown_wrap">
									<div class="services-chooser_item"></div>
                                    <?php
                                    $count = 0;
                                    $services = get_terms('pa_service');
                                    foreach($services as $service) {
										$is_nonbookable = get_field('is_non-bookable', 'term_' . $service->term_id);
										if(!$is_nonbookable) {
                                        $is_multiday = get_field('is_multi','term_' . $service->term_id);
										$active = $service_name == $service->slug ? true : false;

                                        $count++;
                                        ?>
                                        <div class="services-chooser_item chooser-item <?php echo $is_multiday ? 'md': ''; ?> <?php echo $active ? 'active' : ''; ?>">
                                            <label for="<?php echo 'service-index-' . $count; ?>" class="<?php echo 'service-label-' . $service->slug; ?> <?php echo $active ? 'active' : ''; ?>"><?php echo $service->name; ?></label>
                                            <input type="text" name="<?php echo $active ? 'activeType' : ''; ?>" id="<?php echo 'service-index-' . $count; ?>" class="service-checkbox" value="<?php echo $service->slug; ?>" />
                                        </div>
                                    <?php }} ?>
								</div>
								<a href="#" class="to_peoples-button mobile-order_form--button" disabled="disabled">Continue</a>
							</div>
							<div class="filter-wrap filter-wrap_peoples">
								<?php if(empty($people_number)) { ?>
									<div class="filter-div filter-people filter-wrap_peoples--recieve">People</div>
								<?php }  else { ?>
									<div class="filter-div filter-people filter-wrap_peoples--recieve"><?php echo $people_number . ' People'; ?></div>
								<?php } ?>
								<div class="mobile-order_form--top">
									<a href="#" class="to_mfleets-button nav-top-button"></a>
									<div class="mobile-order_form--title">Select number of people</div>
									<div class="mobile-order_form--step">
										<p>Step &nbsp;</p>
										<p>2/3</p>
									</div>
								</div>
								<div class="peoples-wrap filter-dropdown_wrap">
									<div class="peoples-row">
										<div class="left">
											<h4>Adults</h4>
											<p>Ages 13 or above</p>
										</div>
										<div class="right">
											<input class="less-people less-people_adult" value="-" readonly>
											<input type="text" value="<?php echo $adults_number; ?>" placeholder="0" class="poeple-summury people-calc" data-type="people" name="adult_number" />
											<input class="more-people more-people_adult" value="+" readonly>
										</div>
									</div>
									<div class="peoples-row">
										<div class="left">
											<h4>Children</h4>
											<p>Ages 2-12</p>
										</div>
										<div class="right">
											<input class="less-people less-people_children" value="-" readonly>
											<input type="text" value="<?php echo $children_number; ?>" placeholder="0" class="poeple-children_summury people-calc" data-type="children" name="children_number" />
											<input class="more-people more-people_children" value="+" readonly>
										</div>
									</div>
									<div class="peoples-row">
										<div class="left">
											<h4>Infants</h4>
											<p>Under 2</p>
										</div>
										<div class="right">
											<input class="less-people less-people_infants" value="-" readonly>
											<input type="text" value="<?php echo $infants_number; ?>" placeholder="0" data-type="infants" class="poeple-infants_summury people-calc" name="infants_number" />
											<input class="more-people more-people_infants" value="+" readonly>
										</div>
									</div>
								</div>
								<a href="#" class="to-mdate_button to-mdate_button--search mobile-order_form--button" disabled="disabled">Continue</a>
							</div>
							<div class="filter-wrap filter-wrap_calendar">
								<?php
									$is_multiday = $service_name == 'multi-day-charters';
									$end_date_line = !empty($end_date) ? (' - ' . $end_date) : '';
									if(!empty($start_date)) {
								?>
									<div class="filter-div filter-day">
										<?php echo $start_date . $end_date_line; ?>
									</div>
								<?php } else { ?>
									<div class="filter-div filter-day filter-day_recieve"><?php echo $is_multiday ? 'Days' : 'Day'; ?></div>
								<?php } ?>
								<input type="hidden" class="day-start_input" name="activeDay" value="<?php echo !$start_date ? $start_date : ''; ?>" />
								<input type="hidden" class="day-end_input" name="endDay" value="<?php echo !empty($end_date) ? $end_date : ''; ?>" />
								<div class="mobile-order_form--top">
									<a href="#" class="to_people-button nav-top-button"></a>
									<div class="mobile-order_form--title">Select your day</div>
									<div class="mobile-order_form--step">
										<p>Final step</p>
									</div>
								</div>
								<?php get_template_part('template-parts/multiday-calendar-form'); ?>
								<button class="search-submit_mobile mobile-order_form--button active" type="submit" role="button">Search</button>
							</div>
						</div>
						<button class="search-submit search-submit_desktop" type="submit" role="button">Search</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="search-content">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php
					$search_title = '';
					$search_undertitle = '';
					$search_excerpt = '';
					$search_service_id = '';
					if(empty($fleet_name) && empty($service_name)) {
						$search_title = 'Choose boat or service';
					} else {
						if(!empty($service_name)) {
                            $service = get_term_by('slug', $service_name, 'pa_service');
                            $search_service_id = $service->term_id;
							$search_title = $service->name;
							$search_excerpt = get_field('description_short', 'term_' . $search_service_id);

						} else {
							if(!empty($fleet_name)) {
								$tag = get_term_by('slug', $fleet_name,'product_tag');
								$search_title = $tag->name;
							} else {
								$search_title = 'No boats are available with these criteria';
								$search_excerpt = 'Choose boat or service that matches your criteria';
							}
						}
					}


					$s = get_search_query();
                    remove_all_filters('posts_orderby');
					$args = array(
						'post_type'=>'product_variation',
						'orderby' => 'relevance',
						'order' => 'DESC',
						'meta_query'      => array(
							'relation'    => 'AND',
					        array(
					            'key'     => '_total_people',
					            'value'   => $people_number,
								'type' => 'NUMERIC',
					            'compare' => '>='
					        ),
					        array(
					            'key'     => 'attribute_pa_service', // Product variation attribute
					            'value'   => $service_name, // Term slugs only
					            'compare' => 'IN',
					        ),
					    ),
					);
					$the_query = new WP_Query( $args );
					if ( $the_query->have_posts() ) {
				?>
				<h1 class='search-title'><?php echo $search_title; ?></h1>
				<p class="search-undertitle"><?php echo $search_excerpt; ?></p>
                <div class="search-content_wrap">
                    <?php
                    $available_posts = array_filter($the_query->posts, function($post) use ($start_date_f, $end_date_f, $service_name) {
                        $var_product_id = $post->ID;
                        $var_product = new WC_Product_Variation($var_product_id);
                        $product_id = $var_product->get_parent_id();
                        $product = wc_get_product($product_id);
                        $end_date = '';

                        if ($end_date == '') {
                            $orders = wc_get_orders([
                                'sc_booking_boats_daily' => [$start_date_f, $var_product_id],
                                'return' => 'ids'
                            ]);
                        } else {
                            $orders = wc_get_orders([
                                'sc_booking_boats' => [$start_date_f, $end_date_f, $var_product_id],
                                'return' => 'ids'
                            ]);
                        }

                        $is_booked = false;
                        if ($orders) {
                            foreach ($orders as $order_id) {
                                $order = new WC_Order($order_id);
                                $order_variation_id = get_post_meta($order_id, 'sc_variation_id', true);
                                $var_attrs = wc_get_product_variation_attributes($order_variation_id);
                                $var_service = $var_attrs['attribute_pa_service'];
                                $var_service_obj = get_term_by('slug', $var_service, 'pa_service');
                                $user_service_obj = get_term_by('slug', $service_name, 'pa_service');
                                if ($service_name == $var_service) {
                                    $is_booked = true;
                                    break;
                                } else {
                                    $order_start_day = get_post_meta($order_id, 'sc_day_f', true);
                                    $order_end_day = get_post_meta($order_id, 'sc_endday_f', true);
                                    $order_serv_time_start = get_field('time_start', 'term_' . $var_service_obj->term_id);
                                    $order_serv_time_end = get_field('time_end', 'term_' . $var_service_obj->term_id);
                                    $order_start = $order_start_day . ' ' . $order_serv_time_start;
                                    $order_end = $order_end_day . ' ' . $order_serv_time_end;

                                    $user_start_day = $start_date_f;
                                    $user_end_day = $end_date_f;
                                    $user_serv_time_start = get_field('time_start', 'term_' . $user_service_obj->term_id);
                                    $user_serv_time_end = get_field('time_end', 'term_' . $user_service_obj->term_id);
                                    $user_start = $user_start_day . ' ' . $user_serv_time_start;
                                    $user_end = $user_end_day . ' ' . $user_serv_time_end;

                                    if (dateIsOverlap($order_start, $order_end, $user_start, $user_end)) {
                                        $is_booked = true;
                                        break;
                                    }
                                }
                            }
                        }
                        return !$is_booked;
                    });
                        usort($available_posts, function($a, $b) use ($start_date_f) {
                            $a_price = sc_get_actual_rate($a->ID, $start_date_f);
                            $b_price = sc_get_actual_rate($b->ID, $start_date_f);

                            if ($a_price < $b_price) {
                                return -1;
                            } elseif ($a_price > $b_price) {
                                return 1;
                            } else {
                                return 0;
                            }
                        });

                        foreach ($available_posts as $post) {
                            setup_postdata($GLOBALS['post'] =& $post);

                            $var_product_id = get_the_ID();
                            $var_product = new WC_Product_Variation($var_product_id);
                            $product_id = $var_product->get_parent_id();
                            $product = wc_get_product($product_id);
                            ?>
                            <div class="search-item">
                                <a href="<?php echo get_the_permalink($product_id) . '?from_search=1'; ?>" class="search-item_thumb">
                                    <?php
                                    if (has_post_thumbnail($product_id)) {
                                        echo get_the_post_thumbnail($product_id);
                                    }
                                    ?>
                                </a>
                                <div class="search-item_description">
                                    <?php
                                    $price_text = 'per day';
                                    ?>
                                    <h4><a href="<?php echo get_the_permalink($product_id) . '?from_search=1'; ?>"><?php echo get_the_title($product_id); ?></a></h4>
                                    <?php if (!empty($search_service_id)) {

                                        $is_non_bookable = get_field('is_non-bookable', 'term_' . $search_service_id);
                                        if ($is_non_bookable) {
                                            ?>
                                            <p class="search-item_price">Price Not Available</p>
                                        <?php
                                        } else {
                                            $price = sc_get_actual_rate($var_product_id, $start_date_f);
                                            ?>
                                            <p class="search-item_price">From <?php echo wc_price($price) . ' ' . $price_text; ?></p>
                                        <?php
                                        }
                                    } ?>
                                    <?php
                                    $max_people = intval(get_post_meta($var_product_id, '_total_people', true));
                                    if ($max_people < 100) {
                                        ?>
                                        <p class="search-item_poeple">Max Persons: <?php echo $max_people; ?></p>
                                    <?php
                                    } ?>
                                </div>
                            </div>
                        <?php
                        }
                        wp_reset_postdata();
                    ?>
                </div>
				<?php } else { ?>
					<h1 class="search-title empty">No boats found</h1>
				<?php } ?>
				<div class="page-bottom_message">
					<h4 class="message-title">Haven’t found what you were looking for?</h4>
					<p class="message-text">Try refining your search or contact us directly by clicking <a href="/contact/">here</a>. We look forward to hearing from you.</p>
				</div>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>