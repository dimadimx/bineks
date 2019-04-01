<?php while ($events->have_posts()) {
	$events->the_post(); ?>
    <div>
		<?php the_title(); ?>
    </div>
    <div>
		<?php the_excerpt(); ?>
    </div>
<?php } ?>
<nav id="ajax-pagination">
	<?php newsPagination($events); ?>
</nav>
<?php wp_reset_postdata(); ?>
