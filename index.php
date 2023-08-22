<?php
//get_header();
//the_content();
//get_footer();
 get_header(); ?>

 
 <?php
 if(!(is_checkout() && !empty( is_wc_endpoint_url('order-received') ))){
  ?>
  <article id="post-<?php the_ID(); ?>" class="page-content" <?php post_class(); ?>>
  <div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1 class="page-title inner-title default-title"><?php the_title(); ?></h1>
       <div class="page-content_default">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
           <?php the_content(); ?>
        <?php endwhile; endif; ?>
       </div>
    </div>
  </div>
</div>
</article>
<?php
 } else {
  ?>
  <article id="post-<?php the_ID(); ?>" class="page-content" <?php post_class(); ?>>
  <div class="row">
    <div class="col-md-12">
       <div class="page-content_default">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
           <?php the_content(); ?>
        <?php endwhile; endif; ?>
       </div>
    </div>
  </div>
</article>
<?php
 }
  ?>
  

<?php get_footer(); ?>
