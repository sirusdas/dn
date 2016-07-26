<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package TA Meghna
 */

get_header(); ?>

	<section id="blog-banner">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">

					<div class="blog-icon">
						<i class="fa <?php if ( ta_option( 'blog_page_icon' ) != '') :  echo ta_option( 'blog_page_icon' ); ?><?php endif; ?> fa-4x"></i>
					</div>
					<div class="blog-title">
						<h1><?php _e( 'Error #404', 'ta-meghna' ); ?></h1>
					</div>

				</div><!-- .col-lg-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
	</section>

	<section id="blog-page">
		<div class="container">
			<div class="row">
				<div id="blog-posts" class="col-lg-12">
					<main id="main" class="site-main" role="main">

						<header class="entry-header text-center clearfix">
							<h2 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'ta-meghna' ); ?></h2>
							<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ta-meghna' ); ?></p>
							<div class="col-lg-12">
							<?php get_search_form(); ?>
							</div>
						</header><!-- .page-header -->

						<div class="page-content">
							<div class="col-lg-12 clearfix">
								<div class="col-md-4 col-lg-4">
									<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>
								</div>

								<div class="col-md-4 col-lg-4">
									<?php the_widget( 'WP_Widget_Recent_Comments' ); ?>
								</div>

								<div class="col-md-4 col-lg-4">
									<?php if ( ta_meghna_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>
									<div class="widget widget_categories">
										<h2 class="widget-title"><?php _e( 'Most Used Categories', 'ta-meghna' ); ?></h2>
										<ul>
										<?php
											wp_list_categories( array(
												'orderby'    => 'count',
												'order'      => 'DESC',
												'show_count' => 1,
												'title_li'   => '',
												'number'     => 10,
											) );
										?>
										</ul>
									</div><!-- .widget -->
									<?php endif; ?>
								</div>
							</div>

							<div class="col-lg-12 clearfix">
								<div class="col-md-4 col-lg-4">
									<?php
										/* translators: %1$s: smiley */
										$archive_content = '<p>' . sprintf( __( 'Try looking in the monthly archives. %1$s', 'ta-meghna' ), convert_smilies( ':)' ) ) . '</p>';
										the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
									?>
								</div>

								<div class="col-md-4 col-lg-4">
									<?php
										/* translators: %1$s: smiley */
										$category_content = '<p>' . sprintf( __( 'Try looking in the category archives. %1$s', 'ta-meghna' ), convert_smilies( ':)' ) ) . '</p>';
										the_widget( 'WP_Widget_Categories', 'dropdown=1', "after_title=</h2>$category_content" );
									?>
								</div>

								<div class="col-md-4 col-lg-4">
									<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
								</div>
							</div>
						</div><!-- .page-content -->

					</main><!-- #main -->
				</div><!-- #blog-posts -->
			</div><!-- #row -->
		</div><!-- .container -->
	</section>

<?php get_footer(); ?>