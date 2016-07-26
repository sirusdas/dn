<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package TA Meghna
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'entry', 'wow', 'fadeIn' ) ); ?> data-wow-duration="1000ms" data-wow-delay="300ms">
	<div class="post-excerpt">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'ta-meghna' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
	</div><!-- .post-excerpt -->

	<footer class="entry-footer post-meta">
		<?php edit_post_link( __( 'Edit', 'ta-meghna' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
