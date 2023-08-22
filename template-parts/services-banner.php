<div class="page-bottom_banner">
	<?php $imageBanner = get_field('services_banner_background', 'options'); ?>
	<img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" />
	<h4><?php the_field('services_banner_title', 'options'); ?></h4>
	<p><?php the_field('services_banner_description', 'options'); ?></p>
	<a href="/services/">Services</a>
</div>