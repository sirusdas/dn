<?php
/**
 * Template Name: Blog
 *
 * @package TA Meghna
 */

get_header(); ?>

	<!-- Blog banner -->
	<section id="blog-banner">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">

					<div class="blog-icon">
						<i class="fa <?php if ( ta_option( 'blog_page_icon' ) != '') :  echo ta_option( 'blog_page_icon' ); ?><?php endif; ?> fa-4x"></i>
					</div>
					<div class="blog-title">
						<h1><?php if ( ta_option( 'title_blog_page' ) != '') :  echo ta_option( 'title_blog_page' ); ?><?php endif; ?></h1>
					</div>

				</div><!-- .col-lg-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
	</section>
	<!-- End blog banner -->

	<!-- Blog post section -->
	<section id="blog-page">
		<div class="container">
			<div class="row">
				<div id="blog-posts" class="col-md-8 col-sm-8">
					<main id="main" class="site-main" role="main">

					<div class="post-item">
					<?php if ( have_posts() ) : ?>

						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
								/* Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'content', get_post_format() );
							?>

						<?php endwhile; ?>

						<?php ta_pagination(); ?>

					<?php else : ?>

						<?php get_template_part( 'content', 'none' ); ?>

					<?php endif; ?>
					</div><!-- .post-item -->

					</main><!-- #main -->
				</div><!-- #blog-posts -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>