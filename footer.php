</div><!-- /wrapper -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="footer-first">
                    <div class="column">
                        <h6><?php echo __( 'Main Pages' ); ?></h6>
                        <nav>
                            <?php wpeFootNav(); ?>
                        </nav>
                    </div>
                    <div class="column">
                        <h6><?php echo __( 'Services' ); ?></h6>
                        <nav>
                            <ul>
                                <?php
                                $services = get_terms('pa_service');
                                if( $services ) {
                                    foreach( $services as $service ) {
                                        $url = get_site_url() . '/services-category/' . $service->slug . '/';?>
                                        <li><a href="<?php echo $url; ?>"><?php echo $service->name; ?></a></li>
                                    <?php }} ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="column">
                        <h6><?php echo __( 'Fleet' ); ?></h6>
                        <nav>
                            <ul>
                                <?php $the_query = new WP_Query( array(
                                    'posts_per_page'=> -1,
                                    'post_type'=>'product',
                                    'orderby' => ['menu_order' => 'ASC',],
                                    'tax_query' => array(
                                        'relation' => 'AND',
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'field' => 'slug',
                                            'terms' => 'fleets'
                                        )
                                    ),
                                ) ); ?>
                                <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
                                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="column column-info">
                        <h6><?php echo __( 'Contact Details' ); ?></h6>
                        <div class="footer-info_wrap">
                            <p><?php the_field('adress', 'options'); ?></p>
                            <a href="mailto:<?php the_field('email', 'options'); ?>"><?php the_field('email', 'options'); ?></a>
                            <a href="https://wa.me/<?php the_field('watsap_link', 'options'); ?>" target="_blank"><?php the_field('watsap_link', 'options'); ?></a>
                            <div class="footer-social">
                                <a href="<?php the_field('facebook_link', 'options'); ?>" class="facebook" target="_blank"></a>
                                <a href="<?php the_field('trip_advisor_link', 'options'); ?>" class="trip-advisor" target="_blank"></a>
                                <a href="<?php the_field('twitter_link', 'options'); ?>" class="twitter" target="_blank"></a>
                                <a href="<?php the_field('instagram_link', 'options'); ?>" class="instagram" target="_blank"></a>
                                <a href="<?php the_field('linkedin_link', 'options'); ?>" class="linkedin" target="_blank"></a>
                                <a href="<?php the_field('youtube_link', 'options'); ?>" class="youtube" target="_blank"></a>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', get_field('watsap_link', 'options')); ?>" class="watsap" target="_blank"></a>
                            </div>
                            <div class="footer-cards">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/visa.svg" alt="icon">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/mastercard.svg" alt="icon">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="banner eu-malta footer-banner">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="inner">
                                    <a href="https://fondi.eu/">
                                        <p>This project is funded by the European Union, NextGenerationEU</p>
                                        <p>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/flag_of_malta.svg" alt="" />
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/flag_eu.svg" alt="" />
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-second">
                    <div class="footer-second_left">
                        <?php $imageFooter = get_field('footer_logotype', 'options'); ?>
                        <img src="<?php echo $imageFooter['url']; ?>" alt="<?php echo $imageFooter['alt'] ?>" />
                        <p class="copyright">© <?php echo date('Y') ?> by Sailing Charters Malta Ltd. <br> All rights reserved</p>
                    </div>
                    <p class="developed">Website designed by
                        <a href="https://think.mt/" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="58px" height="18px" viewBox="0 0 58 18" version="1.1">
                                <title>Think Logo</title>
                                <g id="Desktop" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Think-Logo" fill="#797C84" fill-rule="nonzero">
                                        <polygon id="Path" points="44.4563636 17.6963636 40.8072727 17.6963636 40.8072727 1.84 40.2090909 0.690909091 44.4563636 0.216363636"/>
                                        <path d="M38.4381818,9.23636364 L38.4381818,17.6963636 L34.7872727,17.6963636 L34.7872727,9.49272727 C34.7949183,9.01292567 34.6125769,8.54955632 34.28,8.20363636 C33.6163636,7.52545455 32.5218182,7.52 31.7090909,8.14181818 L31.7090909,17.6963636 L28.0581818,17.6963636 L28.0581818,7.05272727 L27.4254545,5.83636364 L31.7090909,5.83636364 L31.7090909,7.05454545 C31.7294556,7.02960768 31.7513139,7.00592785 31.7745455,6.98363636 C33.0109091,5.72181818 34.7381818,5.32545455 36.1381818,5.83818182 C36.6187719,6.01128781 37.0539034,6.29101525 37.4109091,6.65636364 C38.0811597,7.34660094 38.4505511,8.27432912 38.4381818,9.23636364 L38.4381818,9.23636364 Z" id="Path"/>
                                        <polygon id="Path" points="25.6909091 5.83636364 25.6909091 17.6963636 22.04 17.6963636 22.04 7.05272727 21.4072727 5.83636364"/>
                                        <ellipse id="Oval" cx="23.8654545" cy="1.97090909" rx="2.03818182" ry="1.97090909"/>
                                        <polygon id="Path" points="48.22 11.7654545 51.2945455 17.6963636 47.7454545 17.6963636 44.6690909 11.7654545 47.3454545 6.70727273 46.8909091 5.83636364 51.2945455 5.83636364"/>
                                        <path d="M18.6563636,6.65454545 C18.299358,6.28919707 17.8642264,6.00946963 17.3836364,5.83636364 C15.9763636,5.32363636 14.2472727,5.72 13.02,6.98181818 C12.9967685,7.00410967 12.9749102,7.0277895 12.9545455,7.05272727 L12.9545455,0.216363636 L8.69636364,0.690909091 L9.29272727,1.84 L9.29272727,17.6963636 L12.9436364,17.6963636 L12.9436364,8.14181818 C13.7545455,7.52 14.8509091,7.52545455 15.5145455,8.20363636 C15.8471224,8.54955632 16.0294638,9.01292567 16.0218182,9.49272727 L16.0218182,17.6963636 L19.6727273,17.6963636 L19.6727273,9.23636364 C19.6884967,8.27525053 19.3230763,7.34699129 18.6563636,6.65454545 L18.6563636,6.65454545 Z" id="Path"/>
                                        <path d="M7.80181818,15.6490909 C7.09325994,16.0098583 6.23384936,15.8813483 5.66181818,15.3290909 C5.32879377,14.9827217 5.14641733,14.5186096 5.15454545,14.0381818 L5.15454545,8.20363636 L6.97272727,8.20363636 L7.22727273,5.84 L5.15454545,5.84 L5.15454545,2.84545455 L1.50363636,5.83636364 L0,5.83636364 L0,8.2 L1.50363636,8.2 L1.50363636,14.2909091 C1.48786693,15.2520222 1.85328729,16.1802814 2.52,16.8727273 C2.87741736,17.2380015 3.31238527,17.5182451 3.79272727,17.6927273 C5.2,18.2036364 6.92909091,17.8072727 8.15636364,16.5472727 C8.18,16.5218182 8.20181818,16.5 8.22181818,16.4763636 L7.80181818,15.6490909 Z" id="Path"/>
                                        <polygon id="Path" points="57.0454545 14.2581818 53.6618182 14.2581818 53.0290909 14.2581818 53.6618182 15.4745455 53.6618182 17.6418182 53.6618182 17.6963636 57.1 17.6963636 57.1 14.2581818"/>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </p>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->

    <div class="thankyou-popup">
        <div class="popup-inner">
            <div class="popup-content">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/comment-icon.svg" alt="image">
                <h4 calss="ty-title">Thank you</h4>
                <div class="ty-descr">
                    <?php the_field('contact_form_thank_you','option'); ?>
                    <a class="ty-back-btn" href="/">Back to home</a>
                </div>
            </div>
        </div>
    </div>


    <div class="cookie-popup">
        <div class="cookie-popup_holder">
            <div class="left">
                <h4>Cookie popup</h4>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima esse omnis ullam vitae nobis ipsam voluptates voluptas doloribus a quod accusantium, maiores nihil numquam in iste molestias, deserunt sunt aut.</p>
            </div>
            <a href="#" class="cookoe-accept more-button">Accept Cookies</a>
        </div>
    </div>
</footer><!-- /footer -->

<div class="loading-popup">
    <div class="loading-inner">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    </div>
</div>

<?php wp_footer(); ?>
<script>
    jQuery(document).ready(function($) {
        $('body').on('click', '.terms-conditions_toggle', function() {
            console.log('footer');
            $(this).toggleClass('active');
            if($(this).hasClass('active')) {
                $('.pay-deposit_button').prop('disabled', false);
            }
            else {
                $('.pay-deposit_button').prop('disabled', true);
            }
        });
    });
</script>
</body>
</html>
