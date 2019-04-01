<?php
get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while (have_posts()) {
			the_post();

			?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
					<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header><!-- .entry-header -->

                <div class="entry-content">
					<?php
					the_content();
					?>
                </div><!-- .entry-content -->

                <footer class="entry-footer">
					<?php
					edit_post_link(
						sprintf(
						/* translators: %s: Name of current post */
							__(
								'Edit<span class="screen-reader-text"> "%s"</span>',
								'twentysixteen'
							),
							get_the_title()
						),
						'<span class="edit-link">',
						'</span>'
					);
					?>
                </footer><!-- .entry-footer -->
                <h2><?php echo __('Events') ?></h2>
				<?php $events = new WP_Query(
					[
						'post_type'      => 'events',
						'posts_per_page' => 3,
						'paged'          => get_query_var('paged')
							? get_query_var('paged') : 1,
						'meta_query'     => [
							[
								'key'     => 'events',
								'value'   => '"'.get_the_ID().'"',
								'compare' => 'LIKE',
							],
						],
					]
				); ?>
				<?php if ($events->have_posts()) { ?>
					<?php set_query_var('events', $events); ?>
                    <div id="events">
						<?php get_template_part(
							'template-parts/content', 'event'
						); ?>
                    </div>
				<?php } ?>

            </article>
			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if (comments_open() || get_comments_number()) {
				comments_template();
			}

			// End of the loop.
		}
		?>

    </main><!-- .site-main -->

	<?php get_sidebar('content-bottom'); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
