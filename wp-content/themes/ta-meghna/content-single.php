<?php
/**
 * @package TA Meghna
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'entry', 'wow', 'fadeIn' ) ); ?> data-wow-duration="1000ms" data-wow-delay="300ms">
	<!-- Post format begin -->
	<?php if( get_post_format() ) {
		get_template_part( 'inc/post-formats' );
	} elseif ( has_post_thumbnail() ) { ?>
		<div class="media-wrapper">
			<a href="<?php echo get_permalink() ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'full', array( 'class' => "img-responsive" ) ); ?></a>
		</div>
	<?php } ?>
	<!-- Post format end -->

	<div class="post-excerpt">
		<header class="entry-header">
		
		<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

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
		<?php ta_meghna_posted_on(); ?>
		<?php ta_meghna_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->