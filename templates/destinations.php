<?php /* Template Name: Destinations Page */ get_header(); ?>

<?php get_template_part('template-parts/search-menu'); ?>

  <section class="page-banner">
    <div class="parallax-image_wrap">
      <?php $imageBanner = get_field('destinations_banner_background'); ?>
      <img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" class="parallax-image" />
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="page-titles">
            <h1><?php the_field('destinations_banner_title'); ?></h1>
            <p><span style="font-size: 19px; line-height: 28px; margin-bottom: 15px;">
            <?php the_field('destinations_banner_undertitle'); ?></span></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="page-content destinations-content">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="destinations-description_wrap">
            <div class="destinations-description_content">
              <?php the_field('destinations_big_description'); ?>
            </div>
          </div>
          <div class="destinations-content_wrap">
          	<?php
            $categories = get_categories( [
              'taxonomy'     => 'category',
              'type'         => 'destination',
              'include' => '47,48,49',
              'orderby' => 'include',
            ]);
            if( $categories ){
            foreach ( $categories as $category ) {
            $image = get_field('image', 'term_' . $category->term_id); ?>
            <div class="destinations-content_item">
              <div class="left">
                <h4><?php echo $category->name; ?></h4>
                <div class="destinations-item_description">
                  <?php echo get_field('description', 'term_' . $category->term_id); ?>
                </div>
                <a href="#" class="destination-button more-button">Explore Destinations</a>
              </div>
              <div class="right">
                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" />
              </div>
            </div>
            <?php }} ?>
          </div>

          <?php
            $banner_template = get_field('banner')['value'];
            if($banner_template){
                get_template_part('template-parts/' . $banner_template . '-banner');
            } ?>
        </div>
      </div>
    </div>
  </section>

<div class="destinations-popup">
	<?php
  if( $categories ){
    foreach ( $categories as $category ) {
      $destinations = get_posts([
        'numberposts' => -1,
        'category' => $category->term_id,
        'post_type' => 'destination'
      ]);
      ?>
  <div class="destinations-popup_inner">
    <a href="#" class="close-destinations"></a>
    <div class="destinations-titles">
      <h4><?php the_sub_field('popup_title'); ?></h4>
      <p>16 Results found</p>
    </div>
      <div class="destinations-items">
      	<?php if( $destinations ) {
        foreach ( $destinations as $destination ) {
        $image = get_the_post_thumbnail($destination->ID);?>
        <div class="destination-item">
          <div class="thumb-wrap">
            <?php echo $image; ?>
          </div>
          <div class="desctination-description">
            <h4 class="destination-title"><?php echo $destination->post_title; ?></h4>
            <p><span style="font-weight: 400;">
            <?php echo $destination->post_content; ?>
            </span></p>
          </div>
        </div>
        <?php }} ?>
      </div>
  </div>
  <?php }} ?>
</div>

<?php get_footer(); ?>