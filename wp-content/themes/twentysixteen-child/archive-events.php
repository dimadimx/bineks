<?php
get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

		<?php if (have_posts()) { ?>

            <header class="page-header">
				<?php
				the_archive_title('<h1 class="page-title">', '</h1>');
				the_archive_description(
					'<div class="taxonomy-description">', '</div>'
				);
				?>
            </header><!-- .page-header -->

			<?php
			// Start the Loop.
			while (have_posts()) {
				the_post();
				?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
						<?php the_title(
							sprintf(
								'<h2 class="entry-title"><a href="%s" rel="bookmark">',
								esc_url(get_permalink())
							), '</a></h2>'
						); ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
						<?php echo wp_trim_words(
							get_the_content(), 100, FALSE
						); ?>

						<?php
						wp_link_pages(
							[
								'before'      => '<div class="page-links"><span class="page-links-title">'
									.__('Pages:', 'twentysixteen').'</span>',
								'after'       => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
								'pagelink'    => '<span class="screen-reader-text">'
									.__('Page', 'twentysixteen').' </span>%',
								'separator'   => '<span class="screen-reader-text">, </span>',
							]
						);
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
                </article><!-- #post-## -->
				<?php
			}

			// Previous/next page navigation.
			the_posts_pagination(
				[
					'prev_text'          => __(
						'Previous page', 'twentysixteen'
					),
					'next_text'          => __('Next page', 'twentysixteen'),
					'before_page_number' => '<span class="meta-nav screen-reader-text">'
						.__('Page', 'twentysixteen').' </span>',
				]
			);

			// If no content, include the "No posts found" template.
		} else {
			get_template_part('template-parts/content', 'none');
		}
		?>

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
