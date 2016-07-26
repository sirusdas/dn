<?php
/**
 * The template for displaying search results pages.
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
						<i class="fa fa-search fa-4x"></i>
					</div>
					<div class="blog-title">
						<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'ta-meghna' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
					</div>

				</div><!-- .col-lg-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
	</section>
	<!-- End blog banner -->

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
								/**
								 * Run the loop for the search to output the results.
								 * If you want to overload this in a child theme then include a file
								 * called content-search.php and that will be used instead.
								 */
								get_template_part( 'content', 'search' );
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
