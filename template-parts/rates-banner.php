<section class="page-bottom_banner">
	<?php $imageBanner = get_field('rates_banner_background', 'options'); ?>
	<img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" />
	<h4><?php the_field('rates_banner_title', 'options'); ?></h4>
	<p><?php the_field('rates_banner_description', 'options'); ?></p>
	<a href="/rates/">Rates</a>
</section>