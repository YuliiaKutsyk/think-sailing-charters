<?php /* Template Name: About Page */ get_header(); ?>

<?php get_template_part('template-parts/search-menu'); ?>

	<div class="about-content">

    <section class="about-gallery">
        <div class="container">
         <div class="row">
          <div class="col-md-12">
            <h1 class="about-title"><?php the_title(); ?></h1>
            <div class="about-gallery_wrap">
        	<?php if( have_rows('about_us_gallery') ):
            while ( have_rows('about_us_gallery') ) : the_row();
            $image = get_sub_field('image'); ?>
              <a href="<?php echo $image['url']; ?>" class="about-gallery_item viewbox">
                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" />
              </a>
            <?php endwhile; endif; ?>
              <a href="<?php echo $image['url']; ?>" class="viewbox-button viewbox" id="viewbox-button">See more</a>
            </div>
          </div>
        </div>
      </div>
      <div class="owl-carousel about-gallery_slider" id="about-gallery_slider">
      	<?php if( have_rows('about_us_gallery') ):
        while ( have_rows('about_us_gallery') ) : the_row();
        $image = get_sub_field('image'); ?>
        <div class="slider-item">
          <a href="<?php echo $image['url']; ?>" class="about-gallery_item viewbox">
            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" />
          </a>
        </div>
    	<?php endwhile; endif; ?>
      </div>
      <div class="owl-custom_counter">
        <div class="owl-dot_current">1</div>
        <div class="separator">/</div>
        <div class="owl-dot_counter"></div>
      </div>
    </section>

    <section class="about-feautures">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="about-feautures_wrap">
        	<?php if( have_rows('about_feautures') ):
    		while ( have_rows('about_feautures') ) : the_row();
    		$image = get_sub_field('image'); ?>
              <div class="column">
                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" />
                <h4><?php the_sub_field('title'); ?></h4>
                <p><?php the_sub_field('desctiprion'); ?></p>
              </div>
            <?php endwhile; endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="about-description">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="about-description_wrap">
              <div class="left">
                <h4><?php the_field('about_description_title'); ?></h4> 
                <div class="description-wrap">
                  <?php the_field('about_description_content'); ?>
                </div>
              </div>
              <div class="right">
                <?php $imageAbout = get_field('about_description_image'); ?>
    			<img src="<?php echo $imageAbout['url']; ?>" alt="<?php echo $imageAbout['alt'] ?>" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="about-mission">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h4 class="mission-title"><?php the_field('our_vision_mission_and_values_title'); ?></h4>
            <div class="about-mission_wrap">
        	<?php if( have_rows('vision_mission_and_values_repeater') ):
    		while ( have_rows('vision_mission_and_values_repeater') ) : the_row();
    		$image = get_sub_field('image'); ?>
              <div class="mission-item">
                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" />
                <h4><?php the_sub_field('title'); ?></h4>
                <p><?php the_sub_field('description'); ?></p>
              </div>
        	<?php 
            endwhile; endif; 
            wp_reset_postdata();
          ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <section class="page-bottom_banner">
            <?php $imageBanner = get_field('about_bottom_image'); ?>
            <img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" />
            <h4><?php the_field('about_banner_title'); ?></h4>
            <p><?php the_field('about_banner_description'); ?></p>
            <a href="/contact/">Contact Us</a>
          </section>
        </div>
      </div>
    </div>

  </div><!-- /about-content -->

<?php get_footer(); ?>