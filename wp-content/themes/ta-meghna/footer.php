<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package TA Meghna
 */
?>

	</div><!-- #content -->

	<footer id="footer" class="bg-one">
		<div class="container">
			<div class="row wow fadeInUp" data-wow-duration="500ms">
				<div class="col-lg-12">

					<!-- Footer social links -->
					<div class="social-icon">
						<ul>
						<?php
							if ( ta_option( 'social_icons' ) != '' ) :
							$social_options = ta_option( 'social_icons' );
							foreach ( $social_options as $key => $value ) :
						?>
							<?php if ( $value && $key == 'Twitter' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ) ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Facebook' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ) ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Google Plus' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( strtr( $key, " ", "-" ) ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( strtr( $key, " ", "-" ) ) ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Instagram' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ); ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'LinkedIn' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ) ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Tumblr' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ) ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Pinterest' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ) ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Dribbble' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ); ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Flickr' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ); ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'DeviantArt' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ); ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Skype' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ); ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'YouTube' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ); ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'Vimeo' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ) . "-square" ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'SoundCloud' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ); ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'GitHub' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ) ?>"></i>
								</a></li>
							<?php } elseif ( $value && $key == 'RSS' ) { ?>
								<li><a href="<?php echo $value; ?>" title="<?php echo $key; ?>" class="<?php echo strtolower( $key ); ?>" target="_blank">
									<i class="fa fa-<?php echo strtolower( $key ) ?>"></i>
								</a></li>
							<?php } ?>
						<?php
							endforeach;
							endif;
						?>
						</ul>
					</div>
					<!-- End footer social links -->

					<!-- Copyright -->
					<div class="copyright text-center">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
							$logo = ta_option( 'custom_logo', false, 'url' );
							$logo_id = ta_option( 'custom_logo', false, 'id' );
						?>
						<?php if( $logo !== '' ) { ?>
							<img src="<?php echo $logo ?>" alt="<?php echo get_post_meta( $logo_id, '_wp_attachment_image_alt', true); ?>" />
						<?php } else { ?>
							<img src="<?php echo get_template_directory_uri(); ?>/images/logo-meghna.png" alt="<?php esc_attr( bloginfo('name') ); ?>" />
						<?php } ?>
						</a>
						<p><?php if ( ta_option( 'custom_copyright' ) != '') :  echo ta_option( 'custom_copyright' ); ?><?php endif; ?></p>
					</div>
					<!-- End copyright -->

				</div> <!-- .col-lg-12 -->
			</div> <!-- .row -->
		</div> <!-- .container -->
	</footer>
</div><!-- #page -->

<!-- Back to top -->
<a href="javascript:;" id="scrollUp">
	<i class="fa fa-angle-up fa-2x"></i>
</a>
<!-- End back to top -->

<?php wp_footer(); ?>

</body>
</html>