<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package TA Meghna
 */

if ( ! is_active_sidebar( 'sidebar-right' ) ) {
	return;
}
?>
			<!-- Widget section -->
			<div id="right-sidebar" class="col-md-4 col-sm-4 widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar-right' ); ?>
			</div><!-- #right-sidebar -->
			<!-- End widget section -->

		</div><!-- .row -->
	</div><!-- .container -->
</section>
<!-- End blog post section -->
