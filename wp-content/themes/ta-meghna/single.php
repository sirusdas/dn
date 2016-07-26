<?php
/**
 * The template for displaying all single posts.
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
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', 'single' ); ?>

						<?php ta_post_navigation(); ?>

						<?php if ( ! post_password_required() && get_the_author_meta( 'description' ) != '' ) : ?>
						<div class="author-about clearfix">
							<h4><?php _e( 'About', 'ta-meghna' ); ?> <?php the_author_posts_link(); ?></h4>
							<div class="post-author pull-left">
								<?php if ( function_exists( 'get_avatar' ) ) { echo get_avatar( get_the_author_meta( 'ID' ), 120 ); }?>
							</div>
							<div class="author-bio">
								<p><?php the_author_meta( 'description' ) ?></p>
								<h5><?php _e( 'Follow The Author', 'ta-meghna' ); ?></h5>
								<div class="social-profile">
								<?php
									// Retrieve a custom field value
									$twitterHandle = get_the_author_meta( 'twitter' ); 
									$fbHandle = get_the_author_meta( 'facebook' );
									$gHandle = get_the_author_meta( 'gplus' );
								?>
									<ul>
									<?php if ( get_the_author_meta( 'twitter' ) != '' ) : ?>
										<li><a href="<?php echo $twitterHandle; ?>" target="_blank"><i class="fa fa-facebook-square fa-2x"></i></a></li>
									<?php endif; // no twitter handle ?>

									<?php if ( get_the_author_meta( 'facebook' ) != '' ) : ?>
										<li><a href="<?php echo $fbHandle; ?>" target="_blank"><i class="fa fa-twitter-square fa-2x"></i></a></li>
									<?php endif; // no facebook url ?>

									<?php if ( get_the_author_meta( 'gplus' ) != '' ) : ?>
										<li><a href="<?php echo $gHandle; ?>" target="_blank"><i class="fa fa-linkedin-square fa-2x"></i></a></li>
									<?php endif; // no google+ url ?>
									</ul>
								</div>
							</div>
						</div><!-- .author-about -->
						<?php endif; ?>

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

<?php get_sidebar(); ?>
<?php get_footer(); ?>
