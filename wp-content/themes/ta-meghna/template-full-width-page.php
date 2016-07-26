<?php
/**
 * The template for displaying full-width pages.
 *
 * @package TA Meghna
 *
 * Template Name: Full Width Page
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
						<h1><?php the_title(); ?></h1>
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

						<div class="post-item">
						<?php while ( have_posts() ) : the_post(); ?>

							<?php get_template_part( 'content', 'page' ); ?>

							<?php
								// If comments are open or we have at least one comment, load up the comment template
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;
							?>

						<?php endwhile; // end of the loop. ?>
						</div><!-- .post-item -->

					</main><!-- #main -->
				</div><!-- #blog-posts -->
			</div><!-- .row -->
		</div><!-- .container -->
	</section>

<?php get_footer(); ?>