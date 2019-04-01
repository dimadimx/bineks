<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

		<?php
		$posts = get_posts(
			[
				'post_type' => 'events',
				'posts_per_page' => 5,
			]
		);

		if ($posts) { ?>
			<?php foreach ($posts as $post) {
				setup_postdata($post)
				?>
                <div>
                    <h1>
                        <a href="<?php the_permalink(); ?>"><?php the_title(
							); ?></a>
                    </h1>
                    <div>
						<?php the_field('start_date');
						if (get_field('end_date')) { ?>
                            - <?php the_field('end_date');
						} ?>
                    </div>
                    <div>
						<?php echo wp_trim_words(
							$post->post_content, 20, FALSE
						); ?>
                    </div>
					<?php if (get_field('select')) { ?>
						<?php if (get_field('news')) { ?>
                            <a href="<?php echo get_page_link(
								array_shift(get_field('news'))
							); ?>"><?php echo __('More information') ?></a>
						<?php } else { ?>l
							<?php $link = get_field('link'); ?>
                            <a target="_blank"
                               href="<?php echo esc_url($link['url']); ?>">
								<?php echo __('More information'); ?>
                            </a>
						<?php } ?>
					<?php } ?>
                </div>
			<?php } ?>
			<?php wp_reset_postdata(); ?>
            <hr>
		<?php } ?>
        <a href="<?php echo get_post_type_archive_link(
			'events'
		); ?>"><?php echo __('Event index page') ?></a>

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
