<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
$order_id = $order->get_id() ? $order->get_id() : 0;
$booking_data = sc_get_booking_data($order_id);
$start_date = $booking_data['start'];
$current_date = date('Y-m-d');
$datetime1 = date_create($current_date);
$datetime2 = date_create($start_date);

// Calculates the difference between DateTime objects
$interval = date_diff($datetime1, $datetime2);
$day_diff = $interval->d;
$is_expired = false;
if($day_diff < 3) {
	$is_expired = true;
}
if(current_user_can( 'administrator')){
	$is_expired = false;
}
$is_visited = get_post_meta($order_id,'is_thankyou_visited',true);
$is_expired_error = $is_expired && $is_visited;
$current_dest = get_post_meta( $order_id, 'destination', true );
?>
<div class="woocommerce-order ty-page <?php echo $is_expired_error ? 'expired': ''; ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="thankyou-wrap">
					<?php if(!$is_expired_error) { ?>
						<h4 class="ty-title"><?php the_field('thank_you_title', 'options'); ?></h4>
						<div class="ty-descr">
							<?php the_field('thank_you_content', 'options'); ?>
							<?php if(!$is_expired) { ?>
								<p>To edit your order, please, follow this link <a class="edit-order_link" href="#edit-open">Edit order</a></p>
							<?php } ?>
						</div>
					<?php } else { ?>
						<h4 class="ty-title">Oops!</h4>
						<div class="ty-descr">
							<?php the_field('expired_edit_error_text', 'options'); ?>
						</div>
						<p class="edit-err-ref">Order Ref. #<?php echo $order_id; ?></p>
					<?php } ?>
				</div>

			</div>
		</div>
	</div>

	<?php if(!$is_expired_error) { ?>
		<div class="edit-order_popup" style="display: none; ">
			<div class="edit-order_popup--inner">
				<div class="edit-order_popup--content">
					<form action="" class="order-edit_form">
						<div class="hidden-data">
							<?php
					        if ( $order ) :
					        	$order_id = $order->get_id() ? $order->get_id() : 0;
					        	$order_service = get_post_meta($order_id,'order_service_type',true);
								$service = get_term_by('slug',$order_service,'pa_service');
								if($service) {
									$food_cats = get_field('services_menus', 'term_' . $service->term_id);
									$is_multiday = get_field('is_multi','term_' . $service->term_id);
									$is_dest_enabled = get_field('service_dest', 'term_' . $service->term_id);
								}
							 	do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
							 	<p class="order-id_holder"><?php $order_id = $order->get_id();
							 	echo $order_id; ?></p>
						    <?php endif; ?>
						</div>
						<?php if($is_dest_enabled) { ?>
							<div class="left-side">
								<h4 class="checkout-title"><?php the_field('edit_destination_title', 'options'); ?></h4>
							    <div class="sc-thank-dest-wrapper">
							    	<p><?php the_field('edit_destination_undertitle', 'options'); ?></p>
                					<a href="#" class="destinatins-checkbox_toggle checkbox-toggle <?php if($current_dest) { ?>active<?php } ?>"></a>
							    </div>
					    		<?php
					    			if($current_dest) {
			    				?>
							    <div class="current-desctination_edit">
							    	<p class="form-row destination-field_edit form-row-first" data-priority="">
										<span class="woocommerce-input-wrapper current-dest-active active">
											<label class="checkbox destination-edit">
											<?php echo $current_dest; ?>
										</label>
									</span>
								  </p>
							    </div>
								<?php }
									$destinations = get_field('destinations_list', 'term_' . $service->term_id);
									if( $destinations ){
										foreach( $destinations as $destination ){
											$destTitle = get_the_title( $destination->ID );
				  				?>
										<p class="form-row destination-field form-row-first" id="destination-<?php echo $destTitle; ?>" data-priority="">
												<span class="woocommerce-input-wrapper">
													<label class="checkbox destination-edit">
													<input type="checkbox" class="input-checkbox " name="destination-<?php echo $destTitle; ?>" id="destination-<?php echo $destTitle; ?>" value="<?php echo $destTitle; ?>"><?php echo $destTitle; ?>
												</label>
											</span>
										</p>
								<?php }} ?>
								<button type="submit" class="button edit-order_button" id="place_order" value="edit order" data-value="edit order">Edit order</button>
							</div>
						<?php } ?>
						<?php if(get_field('services_menus', 'term_' . $service->term_id)){ ?>
						<div class="right-side">
							<h4 class="checkout-title"><?php the_field('edir_food_title', 'options'); ?></h4>
							<p class="checkout-undertitle"><?php the_field('edit_food_undertitle', 'options'); ?></p>
							<div class="checkout-menu_list checkout-menu_list_day sure-toggle_visibility">
								<?php
									$is_food = sc_check_category_in_order($order_id,'food');
									$active_cat_id = 0;
									foreach($food_cats as $cat_id) {
										$cat = get_term($cat_id);
			                          	$cat_menu = get_field('cat_menu','term_' . $cat_id);
			                          	$is_cat_in_order = sc_check_category_in_order($order_id,$cat->slug);
			                          	$is_active = ($is_food && $is_cat_in_order) ? true : false;
										if($is_active) {
											$active_cat_id = $cat_id;
										}
										if($cat->parent == get_term_by( 'slug', 'food', 'product_cat' )->term_id) {
								?>
									<div class="menu-item <?php echo $is_active ? 'current': ''; ?>" data-id="<?php echo $cat->term_id; ?>">
										<h4><?php echo $cat->name; ?></h4>
										<p><?php echo category_description( $cat_id ); ?></p>
										<a href="<?php echo $cat_menu['url']; ?>" class="download-button" download>Download menu (<?php echo human_filesize($cat_menu['filesize']); ?>)</a>
									</div>
								<?php }} ?>
							</div>
							<?php
								$order_items = $order->get_items();
								$items_cat = 0;
								if(sizeof($order_items) > 0) {
							    	foreach( $order_items as $item ) {
							    		$product_id = $item->get_product_id();
							    		foreach($food_cats as $c) {
							        		if( has_term( $c, 'product_cat', $product_id ) ) {
							        			$items_cat = $c;
							        			break;
							        		}
							    		}
						    		}
								}
								if($is_multiday) {
									$days = 2;
							?>
								<div class="ty__day-list">
									<?php for($i = 1; $i <= $days; $i++) { ?>
										<div class="ty__day-item <?php echo $i == 1 ? 'active':''; ?>" data-n="<?php echo $i; ?>">Day <?php echo $i; ?></div>
									<?php } ?>
								</div>
							<?php }
								if(!$is_multiday) {

									if ( sizeof( $order_items ) > 2 ) {
							?>
									<p class="ty-sm-title">Ordered food:</p>
							<?php } ?>
									<ul class="food-list food-list_edit" data-cat="<?php echo $items_cat; ?>">
										<?php
											if ( ! defined( 'ABSPATH' ) ) exit;
											$categories = array('food');
											if ( sizeof( $order_items ) > 0 ) {
											    foreach( $order_items as $item_id => $item ) {
										    		$product_id = $item->get_product_id();
											        if( has_term( $categories, 'product_cat', $product_id ) ) {
														$product = wc_get_product($product_id);
														$product_q = $item->get_quantity();
														$day_1 = wc_get_order_item_meta( $item_id, 'day_1', true);
			  											$day_2 = wc_get_order_item_meta( $item_id, 'day_2', true);
							            ?>
								            <li class="food-list_item active food-list_item--ordered" data-day1="<?php echo $day_1; ?>" data-day2="<?php echo $day_2; ?>" data-product_id="<?php echo $product_id; ?>">
								            	<?php echo $item->get_name(); ?>
								            </li>
							            <?php
						            				}
						            			}
			            				}
											$hidden_ids = array();
											foreach( $order_items as $item ) {
											 if( has_term( $categories, 'product_cat', $item->get_product_id() ) ) {
												 	$id_to_hide = $item->get_product_id();
													array_push($hidden_ids,$id_to_hide);
											    }
											 }
										 ?>
									</ul>
							<?php } else {
								for($i = 1; $i <= $days; $i++) {
							?>
								<div class="food-list food-list_edit" data-listday="<?php echo $i; ?>" data-cat="<?php echo $items_cat; ?>">
									<?php
										if ( ! defined( 'ABSPATH' ) ) exit;
										$categories = array('food');
										$args_query = array(
									        'taxonomy' => 'product_cat',
									        'child_of' => $food_cats[0]
									    );
									    $cats = get_terms( $args_query );
										foreach($cats as $cat) {
											echo '<div class="ty-subcat-title">' . $cat->name. '</div>';
											$the_query = new WP_Query(array(
												'posts_per_page'=> -1,
												'post_type'=>'product',
												'order' => 'ASC',
												'tax_query' => array(
													'relation' => 'AND',
													array(
														'taxonomy' => 'product_cat',
														'field' => 'id',
														'terms' => $cat->term_id
													)
												),
											));
											while ($the_query -> have_posts()) {
												$the_query -> the_post();
												$post_id = get_the_ID();
												$is_active = false;
												foreach( $order_items as $item_id => $item ) {
										    		$product_id = $item->get_product_id();
													$day_q = wc_get_order_item_meta( $item_id, 'day_' . $i, true);

										    		if($post_id == $product_id && (int)$day_q > 0) {
										    			$is_active = true;
										    		}
												}

										?>
											<div class="food-list_item food-list_item--ordered <?php echo $is_active ? ' active': ''; ?>" data-product_id="<?php echo $post_id; ?>" data-group="<?php echo $cat->name; ?>">
								            	<?php the_title(); ?>
								            </div>
										<?php
											}
										}
										$hidden_ids = array();
										$multi_hidden_ids = array();
										foreach( $order_items as $item_id => $item  ) {
											if(!$is_multiday) {
											 if( has_term( $categories, 'product_cat', $item->get_product_id() ) ) {
												 	$id_to_hide = $item->get_product_id();
													array_push($hidden_ids,$id_to_hide);
											    }
											} else {
												if( has_term( $categories, 'product_cat', $item->get_product_id() ) ) {
												 	$id_to_hide = $item->get_product_id();
												 	$day_1 = (int)wc_get_order_item_meta($item_id, 'day_1', true);
												 	$day_2 = (int)wc_get_order_item_meta($item_id, 'day_2', true);
												 	$days_arr = array($day_1,$day_2);
												 	$multi_hidden_ids[$id_to_hide] = $days_arr;
											    }
											}
										}
									 ?>
								</div>
							<?php }} ?>
							<div class="menu-content_holder sure-toggle_visibility edit-order_menu--holder">
								<?php
				         			foreach($food_cats as $cat_id) {
			                        	$cat = get_term($cat_id);
			                        	$cat_menu = get_field('cat_menu','term_' . $cat_id);
			                          	$is_cat_in_order = sc_check_category_in_order($order_id,$cat->slug);
			                          	$is_active = ($is_food && $is_cat_in_order) ? true : false;
								?>
									<div class="checkout-menu_content <?php echo $is_active ? 'active' : ''; ?>">
										<h4>Select one of the following <?php echo $cat->name; ?> options</h4>
										<p>Guests may select 1 common menu from the options below, however, we would be able to accomodate the selection of different main courses per head. Please leave a note if so. * Children (12 and under) are charged <?php echo 100 - intval(get_field('discount_for_children','option')); ?>% of the price.</p>
										<?php if(!$is_multiday) { ?>
											<ul class="foods-list">
												<?php $the_query = new WP_Query( array(
									              'posts_per_page'=> -1,
									              'post_type'=>'product',
									              'post__not_in' => $hidden_ids,
								                  'tax_query' => array(
									                  'relation' => 'AND',
									                    array(
									                        'taxonomy' => 'product_cat',
									                        'field' => 'id',
									                        'terms' => $cat_id
									                    )
									                ),
									            ) ); ?>
									            <?php while ($the_query -> have_posts()) : $the_query -> the_post();
										            $product_id = get_the_ID();
														$product = wc_get_product($product_id);
														$product_q = 0;
														foreach( $order_items as $item ) {
															if($item->get_product_id() == $product_id) {
																$product_q = $item->get_quantity();
																break;
															}
														}
							            		?>
													<li class="food-list_item food-list_item--holder" data-id="<?php echo $product_id; ?>">
														<?php
														$titlePtitleP = get_the_title() . '&nbsp' ?>
														<?php echo $titlePtitleP; ?>
														<p>
															<?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
				              								(<?php echo wc_price( $price ); ?> Per Person)
														</p>
													</li>
												<?php endwhile; ?>
				    							<?php wp_reset_postdata(); ?>
											</ul>
										<?php } else { ?>
											<div class="ty__popup-daylist">
										<?php
											for($i = 1; $i <= $days; $i++) {
										?>
											<div class="ty__popup-dayitem <?php echo $i == 1 ? 'active': ''; ?>"  data-n="<?php echo $i; ?>">Day <?php echo $i; ?></div>
										<?php } ?>
											</div>
										<?php
											for($i = 1; $i <= $days; $i++) {
												$products_to_hide = array();
												foreach($multi_hidden_ids as $id => $q) {
													if($q[$i-1] > 0) {
														array_push($products_to_hide,$id);
													}
												}
										?>
												<ul class="foods-list" data-n="<?php echo $i; ?>">
													<?php $the_query = new WP_Query( array(
										              'posts_per_page'=> -1,
										              'post_type'=>'product',
										              'post__not_in' => $products_to_hide,
									                  'tax_query' => array(
										                  'relation' => 'AND',
										                    array(
										                        'taxonomy' => 'product_cat',
										                        'field' => 'id',
										                        'terms' => $cat_id
										                    )
										                ),
										            ) ); ?>
										            <?php while ($the_query -> have_posts()) : $the_query -> the_post();
											            	$product_id = get_the_ID();
															$product = wc_get_product($product_id);
															$product_q = 0;
								            		?>
														<li class="food-list_item food-list_item--holder" data-id="<?php echo $product_id; ?>">
															<?php
																$titlePtitleP = get_the_title() . '&nbsp';
																echo $titlePtitleP;
															?>
															<p>
																<?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
					              								(<?php echo wc_price( $price ); ?> Per Person)
															</p>
														</li>
													<?php endwhile; ?>
					    							<?php wp_reset_postdata(); ?>
												</ul>
											<?php } ?>
										<?php } ?>
										<a href="#" class="edit-food_popup--button">Done</a>
									</div>
							<?php } ?>
							</div>
						</div>
						<?php } ?>
						<?php if(!$is_dest_enabled) { ?>
							<button type="submit" class="button edit-order_button" id="place_order" value="edit order" data-value="edit order">Edit order</button>
						<?php } ?>
					</form>
				</div>
			</div>
		</div>
	<?php } ?>


</div>
<?php
	update_post_meta($order_id,'is_thankyou_visited',true);
?>