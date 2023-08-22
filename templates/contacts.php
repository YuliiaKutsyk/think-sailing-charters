<?php /* Template Name: Contacts Page */ get_header(); ?>

<?php get_template_part('template-parts/search-menu'); ?>

<section class="contact-info">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="contacts-info_wrap">
            <div class="left">
              <div class="contact-titles">
                <h1><?php the_title(); ?></h1>
                <p class="contact-undertitle"><?php the_field('contacts_undertitle'); ?></p>
              </div>
              <div class="contacts-info_titles">
                <h6><?php the_field('contacts_form_title'); ?></h6>
                <p><?php the_field('contacts_form_undertitle'); ?></p>
              </div>
              <div class="contacts-info_content">
                <div class="email">
                  <a href="mailto:<?php the_field('contacts_email'); ?>"><?php the_field('contacts_email'); ?></a>
                  <p><?php the_field('contacts_email_undertitle'); ?></p>
                </div>
                <div class="phone">
                  <a href="tel:<?php the_field('contacts_phone'); ?>"><?php the_field('contacts_phone'); ?></a>
                  <p><?php the_field('contacts_phone_undertitle'); ?></p>
                </div>
                <div class="adress">
                  <a href="<?php the_field('contacts_adress_maps_link'); ?>" target="_blank">Office: <?php the_field('contacts_adress'); ?></a>
                  <a href="<?php the_field('contacts_adress_maps_link'); ?>" target="_blank" class="contact-map_link"><?php the_field('contacts_adress_undertitle'); ?></a>
                </div>
                <div class="adress">
                  <a href="<?php the_field('contacts_adress_maps_link_2'); ?>" target="_blank"><?php the_field('contacts_adress_2'); ?></a>
                  <a href="<?php the_field('contacts_adress_maps_link_2'); ?>" target="_blank" class="contact-map_link"><?php the_field('contacts_adress_undertitle'); ?></a>
                </div>
              </div>
              <div class="contact-social">
                <a href="<?php the_field('facebook_link', 'options'); ?>" class="facebook" target="_blank"></a>
                <a href="<?php the_field('trip_advisor_link', 'options'); ?>" class="trip-advisor" target="_blank"></a>
                <a href="<?php the_field('twitter_link', 'options'); ?>" class="twitter" target="_blank"></a>
                <a href="<?php the_field('instagram_link', 'options'); ?>" class="instagram" target="_blank"></a>
                <a href="<?php the_field('linkedin_link', 'options'); ?>" class="linkedin" target="_blank"></a>
                <a href="<?php the_field('youtube_link', 'options'); ?>" class="youtube" target="_blank"></a>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', get_field('watsap_link', 'options')); ?>" class="watsap" target="_blank"></a>
              </div>
              <div class="contacts-additional">
                <h4><?php the_field('additional_information_title'); ?></h4>
                <div class="contacts-additional_wrap">
            	<?php if( have_rows('additional_information_repeater') ):
        		while ( have_rows('additional_information_repeater') ) : the_row(); ?>
                  <div class="column">
                    <h6><?php the_sub_field('title'); ?></h6>
                    <p><?php the_sub_field('description'); ?></p>
                  </div>
                <?php endwhile; endif; ?>
                </div>
              </div>
            </div>

            <div class="right">
              <div class="contact-form_wrap">
                <?php echo do_shortcode('[contact-form-7 id="40" title="Contact Us Form"]'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php get_template_part('template-parts/reviews'); ?>

  <section class="reviews-section home-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <?php get_template_part('template-parts/services-banner'); ?>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>