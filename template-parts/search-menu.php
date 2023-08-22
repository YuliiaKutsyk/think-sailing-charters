<form method="post" action="<?php echo home_url(); ?>" role="search" class="main-banner_filter">
              <input class="search-input hidden-input" type="search" name="s" placeholder="<?php _e( 'To search, type and hit enter.', 'wpeasy' ); ?>">
              <input class="hidden-input people-input_holder" type="text" name="peoplesAmount" />
              <div class="filter-wrap filter-wrap_service">
                <div class="filter-div filter-service">Service</div>
                <div class="mobile-order_form--top">
                  <a href="#" class="close-order_form nav-top-button"></a>
                  <div class="mobile-order_form--title">Select charter type</div>
                  <div class="mobile-order_form--step">
                    <p>Step &nbsp;</p>
                    <p>1/4</p>
                  </div>
                </div>
                <div class="services-chooser filter-dropdown_wrap">
                  <div class="services-chooser_item"></div>
                  <?php
                    $count = 0; ?>
                  <?php
                    $services= get_terms('pa_service');

                    foreach($services as $service) {
                      $is_nonbookable = get_field('is_non-bookable', 'term_' . $service->term_id);
                      if(!$is_nonbookable) { ?>
                      <?php $count++; ?>
                  <div class="services-chooser_item chooser-item">

                    <label for="<?php echo 'service-index-' . $count; ?>" class="<?php echo 'service-label-' . $service->slug; ?>"><?php echo $service->name; ?></label>
                    <input type="text" name="" id="<?php echo 'service-index-' . $count; ?>" class="service-checkbox" value="<?php echo $service->slug; ?>" />

                  </div>
                  <?php }} ?>
                </div>
                <a href="#" class="to_peoples-button mobile-order_form--button" disabled="disabled">Continue</a>
              </div>
              <div class="filter-wrap filter-wrap_peoples">
                <div class="filter-div filter-people">People</div>
                <div class="mobile-order_form--top">
                  <a href="#" class="to_mfleets-button nav-top-button"></a>
                  <div class="mobile-order_form--title">Select number of people</div>
                  <div class="mobile-order_form--step">
                    <p>Step &nbsp;</p>
                    <p>2/4</p>
                  </div>
                </div>
                <div class="peoples-wrap filter-dropdown_wrap">
                  <div class="peoples-row">
                    <div class="left">
                      <h4>Adults</h4>
                      <p>Ages 13 or above</p>
                    </div>
                    <div class="right">
                      <input class="less-people less-people_adult disabled" disabled value="-" readonly />
                      <input type="text" value="0" placeholder="0" class="poeple-summury people-calc" readonly />
                      <input class="more-people more-people_adult" value="+" readonly />
                    </div>
                  </div>
                  <div class="peoples-row">
                    <div class="left">
                      <h4>Children</h4>
                      <p>Ages 2-12</p>
                    </div>
                    <div class="right">
                      <input class="less-people less-people_children disabled" disabled value="-" readonly>
                      <input type="text" value="0" placeholder="0" class="poeple-children_summury people-calc" readonly />
                     <input class="more-people more-people_children" value="+" readonly>
                    </div>
                  </div>
                  <div class="peoples-row">
                    <div class="left">
                      <h4>Infants</h4>
                      <p>Under 2</p>
                    </div>
                    <div class="right">
                      <input class="less-people less-people_infants disabled" disabled value="-" readonly>
                      <input type="text" value="0" placeholder="0" class="poeple-infants_summury people-calc" readonly />
                      <input class="more-people more-people_infants" value="+" readonly>
                    </div>
                  </div>
                </div>
                <a href="#" class="to-mdate_button to-mdate_button--search mobile-order_form--button" disabled="disabled">Continue</a>
              </div>
              <div class="filter-wrap filter-wrap_calendar">
                 <div class="filter-div filter-day">Day</div>
                 <input type="hidden" class="day-search_input" name value />
                 <div class="mobile-order_form--top">
                    <a href="#" class="to_people-button nav-top-button"></a>
                    <div class="mobile-order_form--title">Select your day</div>
                    <div class="mobile-order_form--step">
                      <p>Step &nbsp;</p>
                      <p>3/4</p>
                    </div>
                  </div>
                  <?php if (!is_singular('post') && !is_product()) {
                    get_template_part('template-parts/multiday-calendar-form');
                  } ?>
                 <a href="#" class="to-fleettypeS_button mobile-order_form--button active">Continue</a>
              </div>
             <div class="filter-wrap filter-wrap-boattype">
               <div class="filter-div filter-boat">Boat Type</div>
               <div class="mobile-order_form--top">
                  <a href="#" class="to_calendar-button nav-top-button"></a>
                  <div class="mobile-order_form--title">Select fleet</div>
                  <div class="mobile-order_form--step">
                    <p>Final step</p>
                  </div>
                </div>
               <div class="boats-type_wrap filter-dropdown_wrap">
                <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'fleets',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexA-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexA-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
                 <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'bareboat-charters',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexB-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexB-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
                 <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'corporate-events',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexC-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexC-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
                 <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'day-charters',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexD-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexD-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
                 <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'evening-cruises',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexE-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexE-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
                 <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'flotilla-charters',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexF-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexF-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
                 <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'multi-day-charters',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexG-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexG-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
                 <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'romantic-evening-cruises',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexH-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexH-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
                 <div class="boats-type_category">
                   <?php $countB = 0; ?>
                   <?php $the_query = new WP_Query( array(
                    'posts_per_page'=> -1,
                    'post_type'=>'product',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'special-events',
                        )
                    ),
                    ) ); ?>
                  <?php while ($the_query -> have_posts()) : $the_query -> the_post(); $countB++; ?>
                  <?php $chosenBoat = get_the_ID(); ?>
                  <div class="boats-chooser_item chooser-item">

                    <label for="<?php echo 'boat-indexI-' . $countB; ?>"><?php the_title(); ?></label>
                    <input type="text" name="" id="<?php echo 'boat-indexI-' . $countB; ?>" class="service-checkbox" value="<?php echo $chosenBoat ?>" />

                  </div>
                  <?php $countB++; endwhile; ?>
                  <?php wp_reset_postdata(); ?>
                 </div>
               </div>
               <button class="search-submit_mobile mobile-order_form--button active" type="submit" role="button">Search</button>
             </div>
              <button class="search-submit search-submit_desktop" type="submit" role="button"></button>
            </form>