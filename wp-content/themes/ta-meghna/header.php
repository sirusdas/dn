<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package TA Meghna
 */
?><!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7">
<![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8">
<![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9">
<![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->
<html <?php language_attributes(); ?>>
<head>
<meta name="google-site-verification" content="WDuxZaTU2MeMXy6GFw_UC7JpE0H7Fnh8nvd1v9VlQus" />
<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "Organization",
  "url" : "http://www.webxarc.in",
  "logo" : "http://webxarc.in/developers/wp-content/uploads/2015/07/webxarc-logo-1-new300x1-300x232.png",
  "contactPoint" : [{
    "@type" : "ContactPoint",
    "telephone" : "+91-8888-33-9861",
    "contactType" : "customer service"
  }]
}
</script>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php $fav = ta_option( 'custom_favicon', false, 'url' ); ?>
<?php if ( $fav !== '' ) : ?>
<link rel="icon" type="image/png" href="<?php echo ta_option( 'custom_favicon', false, 'url' ); ?>" />
<?php endif; ?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if ( ta_option( 'disable_preloader') == '1' ) : ?>
<!-- Preloader -->
<div id="loading-mask">
	<div class="loading-img">
		<?php if ( ta_option( 'site_preloader', false, 'url' ) !== '' ) { ?>
		<img alt="<?php esc_attr( bloginfo('name') ); ?>" src="<?php echo ta_option( 'site_preloader', false, 'url' ) ?>"  />
		<?php } else { ?>
		<img alt="<?php esc_attr( bloginfo('name') ); ?>" src="<?php echo get_template_directory_uri(); ?>/images/preloader.gif"  />
		<?php } ?>
	</div>
</div>
 <!-- End preloader -->
<?php endif; ?>

<div id="page" class="hfeed site">
	<a class="sr-only" href="#content"><?php _e( 'Skip to content', 'ta-meghna' ); ?></a>

	<div id="content" class="site-content">

	<?php if ( ta_option( 'disable_header_slider') == '1' && is_front_page() ) : ?>
	<!-- Home slider -->
	<section id="header-slider">
		<div id="slider" class="sl-slider-wrapper">
			<div class="sl-slider">

				<!-- Single slide item -->
				<div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
					<div class="sl-slide-inner">
						<div class="bg-img bg-img-1"></div>
						<div class="carousel-caption">
							<div>
							<?php
								$slider_1_img = ta_option( 'slider_1_img', false, 'url' );
								$slider_1_img_id = ta_option( 'slider_1_img', false, 'id' );
							?>
							<?php if( $slider_1_img !== '' ) : ?>
								<img class="wow fadeInUp" src="<?php echo $slider_1_img ?>" alt="<?php echo get_post_meta( $slider_1_img_id, '_wp_attachment_image_alt', true); ?>">
							<?php endif; ?>

							<?php if ( ta_option( 'slider_1_title' ) != '' ) : ?>
								<h2 data-wow-duration="500ms"  data-wow-delay="500ms" class="heading animated fadeInRight"><?php echo ta_option( 'slider_1_title' ); ?></h2>
							<?php endif; ?>

							<?php if ( ta_option( 'slider_1_subtitle' ) != '' ) : ?>
								<h3 data-wow-duration="500ms"  data-wow-delay="500ms" class="heading animated fadeInLeft"><?php echo ta_option( 'slider_1_subtitle' ); ?></h2>
							<?php endif; ?>

							<?php if ( ta_option( 'slider_1_button_text' ) != '' && ta_option( 'slider_1_button_link' ) != '' ) : ?>
								<a class="btn btn-green animated fadeInUp" href="<?php echo ta_option( 'slider_1_button_link' ); ?>"><?php echo ta_option( 'slider_1_button_text' ); ?></a>
							<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<!-- End single slide item -->

				<!-- Single slide item -->
                                
				<div class="sl-slide" data-orientation="vertical" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="1.5" data-slice2-scale="1.5">
					<div class="sl-slide-inner">
						<div class="bg-img bg-img-2"></div>
						<div class="carousel-caption">
							<div>
							<?php
								$slider_2_img = ta_option( 'slider_2_img', false, 'url' );
								$slider_2_img_id = ta_option( 'slider_2_img', false, 'id' );
							?>
							<?php if( $slider_2_img !== '' ) : ?>
								<img class="wow fadeInUp" src="<?php echo $slider_2_img ?>" alt="<?php echo get_post_meta( $slider_2_img_id, '_wp_attachment_image_alt', true); ?>">
							<?php endif; ?>
                                                        
							<?php if ( ta_option( 'slider_2_title' ) != '' ) : ?>
								<h2 class="heading animated fadeInDown"><?php echo ta_option( 'slider_2_title' ); ?></h2>
							<?php endif; ?>

							<?php if ( ta_option( 'slider_2_subtitle' ) != '' ) : ?>
								<h3 class="animated fadeInUp"><?php echo ta_option( 'slider_2_subtitle' ); ?></h3>
							<?php endif; ?>

							<?php if ( ta_option( 'slider_2_button_text' ) != '' && ta_option( 'slider_2_button_link' ) != '' ) : ?>
								<a class="btn btn-green animated fadeInUp" href="<?php echo ta_option( 'slider_2_button_link' ); ?>"><?php echo ta_option( 'slider_2_button_text' ); ?></a>
							<?php endif; ?>
                                                        
							</div>
						</div>
					</div>
				</div>
                                
				<!-- End single slide item -->

				<!-- Single slide item -->
                                
				<div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="3" data-slice2-rotation="3" data-slice1-scale="2" data-slice2-scale="1">
					<div class="sl-slide-inner">
						<div class="bg-img bg-img-3"></div>
						<div class="carousel-caption">
							<div>
							<?php
								$slider_3_img = ta_option( 'slider_3_img', false, 'url' );
								$slider_3_img_id = ta_option( 'slider_3_img', false, 'id' );
							?>
							<?php if( $slider_3_img !== '' ) : ?>
								<img class="wow fadeInUp" src="<?php echo $slider_3_img ?>" alt="<?php echo get_post_meta( $slider_1_img_id, '_wp_attachment_image_alt', true); ?>">
							<?php endif; ?>

							<?php if ( ta_option( 'slider_3_title' ) != '' ) : ?>
								<h2 class="heading animated fadeInRight"><?php echo ta_option( 'slider_3_title' ); ?></h2>
							<?php endif; ?>

							<?php if ( ta_option( 'slider_3_subtitle' ) != '' ) : ?>
								<h3 class="animated fadeInLeft"><?php echo ta_option( 'slider_3_subtitle' ); ?></h3>
							<?php endif; ?>

							<?php if ( ta_option( 'slider_3_button_text' ) != '' && ta_option( 'slider_3_button_link' ) != '' ) : ?>
								<a class="btn btn-green animated fadeInUp" href="<?php echo ta_option( 'slider_3_button_link' ); ?>"><?php echo ta_option( 'slider_3_button_text' ); ?></a>
							<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
                                
				<!-- End single slide item -->
			</div><!-- .sl-slider -->
			
			<nav id="nav-arrows" class="nav-arrows">
				<span class="nav-arrow-prev"><?php _e( 'Previous', 'ta-meghna' ); ?></span>
				<span class="nav-arrow-next"><?php _e( 'Next', 'ta-meghna' ); ?></span>
			</nav>

			<nav id="nav-dots" class="nav-dots">
				<span class="nav-dot-current"></span>
				<span></span>
				<span></span>
			</nav>
		</div><!-- .slider-wrapper -->
	</section><!-- #header-slider -->
	<!-- End home slider -->
	<?php endif; ?>

	<!-- Fixed navigation -->
	<header id="navigation" class="navbar navbar-inverse" role="banner">
		<div class="container">
			<div class="navbar-header">
				<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'secondary' ) ) { ?>
				<!-- Responsive nav button -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only"><?php _e( 'Toggle navigation', 'ta-meghna' ); ?></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- End responsive nav button -->
				<?php } ?>

				<!-- Logo -->
				<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<h1 id="logo">
					<?php
						$logo = ta_option( 'custom_logo', false, 'url' );
						$logo_id = ta_option( 'custom_logo', false, 'id' );
					?>
					<?php if( $logo !== '' ) { ?>
						<img src="<?php echo $logo ?>" alt="<?php echo get_post_meta( $logo_id, '_wp_attachment_image_alt', true); ?>" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/logo-meghna.png" alt="<?php esc_attr( bloginfo('name') ); ?>" />
					<?php } ?>
					</h1>
				</a>
				<!-- End logo -->
			</div>

			<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'secondary' ) ) { ?>
			<!-- Main nav -->
			<nav class="collapse navbar-collapse navbar-right" role="Navigation">
			<?php if ( is_front_page() ) {
				$args = array(
					'theme_location' => 'primary',
					'depth'          => 2,
					'container'      => false,
					'menu_id'        => 'nav',
					'menu_class'     => 'nav navbar-nav',
					'walker'         => new wp_bootstrap_navwalker()
				);

				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( $args );
				}
			} else {
				$args = array(
					'theme_location' => 'secondary',
					'depth'          => 2,
					'container'      => false,
					'menu_id'        => 'nav',
					'menu_class'     => 'nav navbar-nav',
					'walker'         => new wp_bootstrap_navwalker()
				);

				if ( has_nav_menu( 'secondary' ) ) {
					wp_nav_menu( $args );
				}
			} ?>
			</nav>
			<!-- End main nav -->
			<?php } ?>
		</div>
	</header>
	<!-- End fixed navigation -->