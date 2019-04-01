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

                <div>
					<?php the_field('start_date');
					if (get_field('end_date')) { ?>
                        - <?php the_field('end_date');
					} ?>
                </div>

                <div class="entry-content">
					<?php
					the_content();
					?>
                </div><!-- .entry-content -->

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
