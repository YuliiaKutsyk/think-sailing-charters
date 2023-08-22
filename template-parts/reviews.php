<section class="reviews-section home-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h4 class="reviews-title"><?php the_field('reviews_title', 'options'); ?></h4>
          <div class="owl-carousel review-slider" id="review-slider">
          	<?php if( have_rows('reviews_slider', 'options') ): ?>
        	  <?php while( have_rows('reviews_slider', 'options') ): the_row(); ?>
	            <div class="slider__item">
	              <p><?php the_sub_field('review_content'); ?></p>
	              <h4><?php the_sub_field('review_author'); ?></h4>
	            </div>
            <?php endwhile; ?>
			      <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>