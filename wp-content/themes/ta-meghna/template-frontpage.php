<?php
/**
 * Template Name: FrontPage
 *
 * @package TA Meghna
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( ta_option( 'disable_about_module') == '1' ) : ?>
		<!-- About section -->
		<section id="<?php if ( ta_option( 'id_about' ) != '') :  echo ta_option( 'id_about' ); ?><?php endif; ?>" class="about-section bg-one">
			<div class="container">
				<div class="row">

					<!-- Section title -->
					<div class="title text-center wow fadeIn" data-wow-duration="1500ms" >
						<h2><?php if ( ta_option( 'title_about' ) != '') :  echo ta_option( 'title_about' ); ?><?php endif; ?></h2>
						<div class="border"></div>
					</div>
					<!-- End section title -->

					<!-- About item -->
					<?php if ( ta_option( 'about_slides' ) != '') { ?>

					<!-- Loop slide -->
					<?php
						$i = 1;
						foreach( $ta_option['about_slides'] as $about_slide ) :
					?>
					<div id="about-item" class="col-md-4 text-center wow fadeInUp" data-wow-duration="500ms" data-wow-delay="<?php echo ($i-1)*250,'ms'; ?>">
						<div class="wrap-about">
							<div class="icon-box">
							<?php if ( $about_slide['image'] != '') { ?>
								<img class="wow fadeInUp img-responsive" src="<?php echo $about_slide['image']; ?>">
							<?php } else { ?>
								<i class="fa <?php echo $about_slide['facode']; ?> fa-4x"></i>
							<?php } ?>
							</div>					
							<div class="about-content text-center">
								<h3 class="ddd"><?php echo $about_slide['title']; ?></h3>								
								<p><?php echo $about_slide['description']; ?></p>
							</div>
						</div>
					</div>
					<?php
						$i++;
						endforeach;
					?>
					<!-- End loop slide -->

					<?php } ?>
					<!-- End about item -->

				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- /about section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_features_module') == '1' ) : ?>
		<!-- Main features -->
		<section id="<?php if ( ta_option( 'id_features' ) != '') :  echo ta_option( 'id_features' ); ?><?php endif; ?>" class="main-features">
			<div class="container">
				<div class="row">
				
				<!-- Features item -->
				<?php if ( ta_option( 'features_slides' ) != '') : ?>

					<div id="main-features">
					<!-- Loop slide -->
					<?php foreach( $ta_option['features_slides'] as $features_slide ) : ?>
						<div class="item">
							<div class="features-item">
							<?php if ( $features_slide['image'] != '') { ?>
								<!-- Features media -->
								<div class="col-md-6 feature-media wow fadeIn" data-wow-duration="500ms">
									<img src="<?php echo $features_slide['image']; ?>" class="img-responsive">
								</div>
								<!-- End features media -->
							<?php } else { ?>
								<!-- Features media -->
								<div class="col-md-6 feature-media media-wrapper wow fadeIn" data-wow-duration="500ms">
									<?php echo $features_slide['vcode']; ?>
								</div>
								<!-- End features media -->
							<?php } ?>
								
								<!-- Features content -->
								<div class="col-md-6 feature-desc wow fadeInRight" data-wow-duration="500ms" data-wow-delay="300ms">
									<h3><?php echo $features_slide['title']; ?></h3>
									<?php echo $features_slide['description']; ?>

									<?php if ( $features_slide['btn_a_text'] != '' && $features_slide['btn_a_link'] != '' ) : ?>
									<a href="<?php echo $features_slide['btn_a_link']; ?>" class="btn btn-transparent"><?php echo $features_slide['btn_a_text']; ?></a>
									<?php endif; ?>

									<?php if ( $features_slide['btn_b_text'] != '' && $features_slide['btn_b_link'] != '' ) : ?>
									<a href="<?php echo $features_slide['btn_b_link']; ?>" class="btn btn-transparent"><?php echo $features_slide['btn_b_text']; ?></a>
									<?php endif; ?>
								</div>
								<!-- /End features content -->
							</div>
						</div>
					<?php endforeach; ?>
					<!-- End loop slide -->
					</div>

				<?php endif; ?>
				<!-- End features item -->
					
				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- End main features -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_counter_module') == '1' ) : ?>
		<!-- Counter Section -->
		<section id="<?php if ( ta_option( 'id_counter' ) != '') :  echo ta_option( 'id_counter' ); ?><?php endif; ?>" class="counter-section parallax-section">
			<div class="container">
				<div class="row">

				<!-- Count item -->
				<?php if ( ta_option( 'counter_slides' ) != '') { ?>

				<!-- Loop slide -->
				<?php
					$i = 1;
					foreach( $ta_option['counter_slides'] as $counter_slide ) :
				?>
					<div id="count-item" class="col-md-3 col-sm-6 col-xs-12 text-center wow fadeInDown" data-wow-duration="500ms" data-wow-delay="<?php echo ($i-1)*200,'ms'; ?>">
						<div class="counters-item">
							<div>
							<?php if ( strpos( $counter_slide['subtitle'], '%' ) !== false ) { ?>
							    <span data-speed="3000" data-to="<?php echo str_replace( "%", "", $counter_slide['subtitle'] ); ?>"><?php echo str_replace( "%", "", $counter_slide['subtitle'] ); ?></span>
								<span>%</span>
							<?php } else { ?>
								<span data-speed="3000" data-to="<?php echo $counter_slide['subtitle']; ?>"><?php echo $counter_slide['subtitle']; ?></span>
							<?php } ?>
							</div>
							<i class="fa <?php echo $counter_slide['facode']; ?> fa-3x"></i>
							<h3><?php echo $counter_slide['title']; ?></h3>
						</div>
					</div>
				<?php
					$i++;
					endforeach;
				?>
				<!-- End loop slide -->

				<?php } ?>
				<!-- End count item -->

				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- End counter Section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_services_module') == '1' ) : ?>
		<!-- Services Section -->
		<section id="<?php if ( ta_option( 'id_services' ) != '') :  echo ta_option( 'id_services' ); ?><?php endif; ?>" class="services-section bg-one">
			<div class="container">
				<div class="row">
					
					<!-- Section title -->
					<div class="title text-center wow fadeIn" data-wow-duration="500ms">
						<h2><?php if ( ta_option( 'title_services' ) != '') :  echo ta_option( 'title_services' ); ?><?php endif; ?></h2>
						<div class="border"></div>
					</div>
					<!-- End section title -->
								
					<!-- Single service Item -->
					<?php if ( ta_option( 'services_slides' ) != '') { ?>

					<!-- Loop slide -->
					<?php
						$i = 1;
						foreach( $ta_option['services_slides'] as $services_slide ) :
					?>

					<?php
						$fadeIn = "";
						if ( $i <= 3 ) {
							$fadeIn = "fadeInUp";
						} else {
							$fadeIn = "fadeInDown";
						}

						$delay = "";
						if ( $i == 1 ) {
							$delay = "0ms";
						} else if ( $i == 2 || $i == 4 ) {
							$delay = "200ms";
						} else if ( $i == 3 || $i == 5 ) {
							$delay = "400ms";
						} else if ( $i == 6 ) {
							$delay = "600ms";
						}
					?>

					<article id="service-item" class="col-md-4 col-sm-6 col-xs-12 wow <?php echo $fadeIn; ?> data-wow-duration=500ms" data-wow-delay="<?php echo $delay; ?>">
						<div class="service-block text-center">
							<div class="service-icon text-center">
							<?php if ( $services_slide['image'] != '') { ?>
								<img class="wow fadeInUp img-responsive" src="<?php echo $services_slide['image']; ?>">
							<?php } else { ?>
								<i class="fa <?php echo $services_slide['facode']; ?> fa-5x"></i>
							<?php } ?>
							</div>
							<h3><?php echo $services_slide['title']; ?></h3>
							<p><?php echo $services_slide['description']; ?></p>
						</div>
					</article>
					<?php
						$i++;
						endforeach;
					?>
					<!-- End loop slide -->

					<?php } ?>
					<!-- End single service Item -->
						
				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- End services section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_portfolio_module') == '1' ) : ?>
		<!-- Portfolio section -->
		<section id="<?php if ( ta_option( 'id_portfolio' ) != '' ) :  echo ta_option( 'id_portfolio' ); ?><?php endif; ?>">
			<div class="container">
				<div class="row wow fadeInDown" data-wow-duration="500ms">
					<div class="col-lg-12">

						<!-- Section title -->
						<div class="title text-center">
							<h2><?php if ( ta_option( 'title_portfolio' ) != '') :  echo ta_option( 'title_portfolio' ); ?><?php endif; ?></h2>
							<div class="border"></div>
						</div>
						<!-- End section title -->

						<?php if ( ta_option( 'filter_switch') == '1' ) : ?>

						<!-- Portfolio item filtering -->
						<?php
							$terms = get_terms( "portfolio_tags" );
							$count = count( $terms );
						?>

						<div class="portfolio-filter clearfix">
							<ul class="text-center">
							    <li><a href="javascript:void(0)" class="filter" data-filter="all">All</a></li>
								<?php
									if ( $count > 0 ) {   
										foreach ( $terms as $term ) {
											$termname = strtolower( $term->name );
											$termname = str_replace( ' ', '-', $termname );
											echo '<li class="filter" data-filter=".'.$termname.'"><a href="javascript:void(0)">'.$term->name.'</a></li>';
										}
									}
								?>
							</ul>
						</div>
						<!-- End portfolio item filtering -->
						<?php endif; ?>
						
					</div> <!-- .col-lg-12 -->
				</div> <!-- .row -->
			</div>	<!-- .container -->
	
			<!-- portfolio items -->
			<div class="portfolio-item-wrapper wow fadeIn" data-wow-duration="800ms">
                <ul id="og-grid" class="og-grid">

				<?php 
					// the query
					$the_query = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => -1 ) );

					if ( $the_query->have_posts() ) :

					// the loop
					while ( $the_query->have_posts() ) : $the_query->the_post();

					$terms = get_the_terms( $post->ID, 'portfolio_tags' );

					if ( $terms && ! is_wp_error( $terms ) ) :
						$links = array();

					foreach ( $terms as $term ) {
						$links[] = $term->name;
					}

					$links = str_replace(' ', '-', $links);
					$tax = join( " ", $links );

					else :
						$tax = '';
					endif;
				?>

					<!-- single portfolio item -->	
					<li class="mix <?php echo strtolower( $tax ); ?>">
						<a href="<?php echo get_post_meta( $post->ID, '_cmb_portfolio_url', true); ?>" data-largesrc="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>" data-title="<?php the_title(); ?>" data-description="<?php the_content(); ?>" data-button="<?php if ( ta_option( 'link_portfolio' ) != '' ) :  echo ta_option( 'link_portfolio' ); ?><?php endif; ?>">
							<img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>" alt="<?php echo get_post_meta( ( get_post_thumbnail_id( $post->ID ) ), '_wp_attachment_image_alt', true ); ?>">
							<div class="hover-mask">
								<h3><?php the_title(); ?></h3>
								<span>
									<i class="fa fa-plus fa-2x"></i>
								</span>
							</div>
						</a>
					</li>
					<!-- End single portfolio item -->

				<?php
					endwhile;
					// end of the loop

					wp_reset_postdata();

					endif;
				?>

				</ul><!-- #og-grid -->
			</div>
			<!-- End portfolio items wrapper -->
		</section>
		<!-- End portfolio section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_skills_module') == '1' ) : ?>
		<!-- Team skills section -->
		<section id="<?php if ( ta_option( 'id_skills' ) != '') :  echo ta_option( 'id_skills' ); ?><?php endif; ?>" class="team-skills parallax-section">
			<div class="container">
				<div class="row wow fadeInDown" data-wow-duration="500ms">
				
					<!-- Section title -->
					<div class="title text-center">
						<h2><?php if ( ta_option( 'title_skills' ) != '') :  echo ta_option( 'title_skills' ); ?><?php endif; ?></h2>
						<div class="border"></div>
					</div>
					<!-- End section title -->

					<!-- Skill set -->
					<?php if ( ta_option( 'skills_slides' ) != '') { ?>

					<!-- Loop slide -->
					<?php
						$i = 1;
						foreach( $ta_option['skills_slides'] as $skills_slide ) :
					?>
					<div class="col-md-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="500ms" data-wow-delay="<?php echo ($i-1)*200,'ms'; ?>">
						<div class="skill-chart text-center">
							<span class="chart" data-percent="<?php echo $skills_slide['subtitle']; ?>">
								<span class="percent"></span>
							</span>
							<h3><i class="fa <?php echo $skills_slide['facode']; ?>"></i> <?php echo $skills_slide['title']; ?></h3>
							<p><?php echo $skills_slide['description']; ?></p>
						</div>
					</div>
					<?php
						$i++;
						endforeach;
					?>
					<!-- End loop slide -->

					<?php } ?>
					<!-- End skill set -->
					
				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- End team skills section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_team_module') == '1' ) : ?>
		<!-- Our team section -->
		<section id="<?php if ( ta_option( 'id_team' ) != '') :  echo ta_option( 'id_team' ); ?><?php endif; ?>" class="our-team">
			<div class="container">
				<div class="row">

					<!-- Section title -->
					<div class="title text-center wow fadeInUp" data-wow-duration="500ms">
						<h2><?php if ( ta_option( 'title_team' ) != '') :  echo ta_option( 'title_team' ); ?><?php endif; ?></h2>
						<div class="border"></div>
					</div>
					<!-- End section title -->

					<!-- Team member -->
					<?php if ( ta_option( 'team_slides' ) != '') { ?>

					<!-- Loop slide -->
					<?php
						$i = 1;
						foreach( $ta_option['team_slides'] as $team_slide ) :
					?>
					<div id="team-member" class="col-md-3 col-sm-6 wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="<?php echo ($i-1)*250,'ms'; ?>">
                       <article class="team-mate">
							<div class="member-photo">
								<!-- Member photo -->
								<img class="img-responsive" src="<?php echo $team_slide['image']; ?>">
								<!-- End member photo -->

								<!-- Member social profile -->
								<div class="mask">
									<ul class="clearfix">
									<?php if ( $team_slide['furl'] != '' ) : ?>
										<li><a href="<?php echo $team_slide['furl']; ?>"><i class="fa fa-facebook"></i></a></li>
									<?php endif; ?>
									<?php if ( $team_slide['turl'] != '' ) : ?>
										<li><a href="<?php echo $team_slide['turl']; ?>"><i class="fa fa-twitter"></i></a></li>
									<?php endif; ?>
									<?php if ( $team_slide['lurl'] != '' ) : ?>
										<li><a href="<?php echo $team_slide['lurl']; ?>"><i class="fa fa-linkedin"></i></a></li>
									<?php endif; ?>
									</ul>
								</div>
								<!-- End member social profile -->
							</div>

							<!-- Member name & designation -->
							<div class="member-title text-center">
								<h3><?php echo $team_slide['title']; ?></h3>
								<span><?php echo $team_slide['subtitle']; ?></span>
							</div>
							<!-- End member name & designation -->

							<!-- About member -->
                           <div class="member-info text-center">
                               <p><?php echo $team_slide['description']; ?></p>
                           </div>
						   <!-- End about member -->
                       </article>
                    </div>
					<?php
						$i++;
						endforeach;
					?>
					<!-- End loop slide -->

					<?php } ?>
					<!-- End team member -->
			
				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- End Our team section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_twitter_module') == '1' ) : ?>
		<!-- Twitter section-->
		<section id="<?php if ( ta_option( 'id_twitter' ) != '') :  echo ta_option( 'id_twitter' ); ?><?php endif; ?>" class="twitter-feed parallax-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-center">

						<!-- Twitter bird -->
						<div class="twitter-bird wow fadeInDown" data-wow-duration="500ms">
							<span>
								<i class="fa fa-twitter fa-4x"></i>
							</span>
						</div>
						<!-- End Twitter bird -->

						<!-- Fetching tweet -->
						<div class="tweet wow fadeIn" data-wow-duration="2000ms"></div>
						<!-- End fetching tweet -->

						<!-- Follow us button -->
						<a href="https://twitter.com/<?php if ( ta_option( 'twitter_username' ) != '') :  echo ta_option( 'twitter_username' ); ?><?php endif; ?>" title="<?php if ( ta_option( 'link_follow' ) != '') :  echo ta_option( 'link_follow' ); ?><?php endif; ?>" target="_blank" class="btn btn-transparent wow fadeInUp" data-wow-duration="500ms"><?php if ( ta_option( 'link_follow' ) != '') :  echo ta_option( 'link_follow' ); ?><?php endif; ?></a>						
						<!-- End follow us button -->

					</div>
				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- End Twitter section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_price_module') == '1' ) : ?>
		<!-- Pricing section -->
		<section id="<?php if ( ta_option( 'id_price' ) != '') :  echo ta_option( 'id_price' ); ?><?php endif; ?>" class="pricing-section bg-one">
			<div class="container">
				<div class="row">

					<!-- Section title -->
				    <div class="title text-center wow fadeInDown" data-wow-duration="500ms">
			        	<h2><?php if ( ta_option( 'title_price' ) != '') :  echo ta_option( 'title_price' ); ?><?php endif; ?></h2>
				        <div class="border"></div>
				    </div>
				    <!-- End section title -->

					<!-- Single pricing table -->
					<?php if ( ta_option( 'price_slides' ) != '') { ?>

					<!-- Loop slide -->
					<?php
						$i = 1;
						foreach( $ta_option['price_slides'] as $price_slide ) :
					?>
					<div id="single-pricing" class="col-md-3 col-sm-6 col-xs-12 text-center wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="<?php echo ($i-1)*250,'ms'; ?>">
						<div class="pricing">

							<!-- Plan name & value -->
							<div class="price-title">
								<h3><?php echo $price_slide['title']; ?></h3>
								<p><?php _e( 'From', 'ta-meghna' ); ?> <strong class="value"><?php echo $price_slide['subtitle']; ?></strong> <?php echo $price_slide['facode']; ?></p>
							</div>
							<!-- End plan name & value -->

							<!-- Plan description -->
							<?php echo $price_slide['description']; ?>
							<!-- End plan description -->

							<!-- Signup button -->
							<a class="btn btn-transparent" href="<?php echo $price_slide['btn_a_link']; ?>"><?php echo $price_slide['btn_a_text']; ?></a>
							<!-- End signup button -->

						</div>
					</div>
					<?php
						$i++;
						endforeach;
					?>
					<!-- End loop slide -->

					<?php } ?>
					<!-- End single pricing table -->

				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- End pricing section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_testimonial_module') == '1' ) : ?>
		<!-- Testimonial section-->
		<section id="<?php if ( ta_option( 'id_testimonial' ) != '') :  echo ta_option( 'id_testimonial' ); ?><?php endif; ?>" class="testimonial-section parallax-section">
			<div class="container">
				<div class="row">				
					<div class="col-lg-12">
					
						<!-- Section title -->
						<div class="sub-title text-center wow fadeInDown" data-wow-duration="500ms">
							<h3><?php if ( ta_option( 'title_testimonial' ) != '') :  echo ta_option( 'title_testimonial' ); ?><?php endif; ?></h3>
						</div>
						<!-- End section title -->

						<!-- Testimonial wrapper -->
						<?php if ( ta_option( 'testimonial_slides' ) != '') { ?>

						<div id="testimonial-block" class="wow fadeInUp" data-wow-duration="500ms" data-wow-delay="100ms">

						<!-- Loop slide -->
						<?php foreach( $ta_option['testimonial_slides'] as $testimonial_slide ) : ?>
							<!-- Testimonial single -->
							<div class="item text-center">
								<!-- Client photo -->
								<div class="client-thumb">
									<img src="<?php echo $testimonial_slide['image']; ?>" class="img-responsive">
								</div>
								<!-- End client photo -->

								<!-- Client info -->
								<div class="client-info">
									<div class="client-meta">
										<h3><?php echo $testimonial_slide['title']; ?></h3>
										<span><?php echo $testimonial_slide['subtitle']; ?></span>
									</div>
									<div class="client-comment">
										<p><?php echo $testimonial_slide['description']; ?></p>
										<ul class="social-profile">
										<?php if ( $testimonial_slide['furl'] != '' ) : ?>
											<li><a href="<?php echo $testimonial_slide['furl']; ?>"><i class="fa fa-facebook-square fa-lg"></i></a></li>
										<?php endif; ?>
										<?php if ( $testimonial_slide['turl'] != '' ) : ?>
											<li><a href="<?php echo $testimonial_slide['turl']; ?>"><i class="fa fa-twitter-square fa-lg"></i></a></li>
										<?php endif; ?>
										<?php if ( $testimonial_slide['lurl'] != '' ) : ?>
											<li><a href="<?php echo $testimonial_slide['lurl']; ?>"><i class="fa fa-linkedin-square fa-lg"></i></a></li>
										<?php endif; ?>
										</ul>
									</div>
								</div>
								<!-- End client info -->
							</div>
							<!-- End testimonial single -->
						<?php endforeach; ?>
						<!-- End loop slide -->

						<?php } ?>
						</div><!-- End testimonial wrapper -->
					</div><!-- .col-lg-12 -->
				</div><!-- .row -->
			</div><!-- .container -->
		</section>
		<!-- End testimonial section -->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_blog_module') == '1' ) : ?>
		<!-- Blog section -->
		<section id="<?php if ( ta_option( 'id_blog' ) != '') :  echo ta_option( 'id_blog' ); ?><?php endif; ?>" class="blog-section bg-one">
			<div class="container">
				<div class="row">

					<!-- Section title -->
					<div class="title text-center wow fadeInDown">
						<h2><?php if ( ta_option( 'title_blog' ) != '') :  echo ta_option( 'title_blog' ); ?><?php endif; ?></h2>
						<div class="border"></div>
					</div>
					<!-- End section title -->

					<div class="clearfix">
					<?php
						// get recent 4 posts.
						$the_query = new WP_Query( array( 'showposts' => 4, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'has_password' => false, ) );
						if ( $the_query->have_posts() ) :
						$i = 1;
						while( $the_query->have_posts() ): $the_query->the_post();
					?>
						<!-- Single blog post -->
						<article id="single-blog" class="col-md-3 col-sm-12 col-xs-12 wow fadeInUp" data-wow-duration="500ms" data-wow-delay="<?php echo ($i-1)*200,'ms'; ?>">
							<div class="note">
								<?php if( get_post_format() ) {
									get_template_part( 'inc/post-formats' );
									} elseif ( has_post_thumbnail() ) { ?>
									<div class="media-wrapper">
										<a href="<?php echo get_permalink() ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'full', array( 'class' => "img-responsive" ) ); ?></a>
									</div>
								<?php } ?>
								
								<div class="excerpt">
									<h3><a href="<?php echo get_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
									<p>
									<?php if( has_excerpt() ) {
										the_excerpt();
									} else {
										echo trim_characters( get_the_content() );
									} ?>
									</p>
									<a class="btn btn-transparent" href="<?php echo get_permalink() ?>"><?php if ( ta_option( 'btn_read_more' ) != '') :  echo ta_option( 'btn_read_more' ); ?><?php endif; ?></a>
								</div>
							</div>						
						</article>
						<!-- End single blog post -->
					<?php
						$i++;
						endwhile;
						wp_reset_postdata();
						endif;
					?>
					</div>

					<div class="all-post text-center">
						<a class="btn btn-transparent" href="<?php if( get_option( 'show_on_front' ) == 'page' ) echo get_permalink( get_option('page_for_posts' ) ); else echo esc_url( home_url() ); ?>"><?php if ( ta_option( 'btn_view_all' ) != '') :  echo ta_option( 'btn_view_all' ); ?><?php endif; ?></a>
					</div>

				</div> <!-- .row -->
			</div> <!-- .container -->
		</section>
		<!-- End blog section-->
		<?php endif; ?>

		<?php if ( ta_option( 'disable_contact_module') == '1' ) : ?>
		<!-- Contact us section-->		
		<section id="<?php if ( ta_option( 'id_contact' ) != '') :  echo ta_option( 'id_contact' ); ?><?php endif; ?>" class="contact-us">
			<div class="container">
				<div class="row">
					
					<!-- section title -->
					<div class="title text-center wow fadeIn" data-wow-duration="500ms">
						<h2><?php if ( ta_option( 'title_contact' ) != '') :  echo ta_option( 'title_contact' ); ?><?php endif; ?></h2>
						<div class="border"></div>
					</div>
					<!-- End section title -->
					
					<!-- Contact Details -->
					<div class="contact-info col-md-6 wow fadeInUp" data-wow-duration="500ms">
						<h3><?php if ( ta_option( 'contact_title' ) != '') :  echo ta_option( 'contact_title' ); ?><?php endif; ?></h3>
						<p><?php if ( ta_option( 'contact_description' ) != '') :  echo ta_option( 'contact_description' ); ?><?php endif; ?></p>
						<div class="contact-details">
						<?php if ( ta_option( 'contact_address' ) != '') : ?>
							<div class="con-info clearfix">
								<i class="fa fa-map-marker fa-lg"></i>
								<span><?php echo ta_option( 'contact_address' ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ta_option( 'contact_phone' ) != '') : ?>
							<div class="con-info clearfix">
								<i class="fa fa-phone fa-lg"></i>
								<span><?php echo ta_option( 'contact_phone' ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ta_option( 'contact_fax' ) != '') : ?>
							<div class="con-info clearfix">
								<i class="fa fa-fax fa-lg"></i>
								<span><?php echo ta_option( 'contact_fax' ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ta_option( 'disable_contact_email') == '1' ) : ?>
							<div class="con-info clearfix">
								<i class="fa fa-envelope fa-lg"></i>
								<span><?php echo ta_option( 'contact_email' ); ?></span>
							</div>
						<?php endif; ?>
						</div>
					</div>
					<!-- End contact details -->

					<!-- Contact Form -->
					<div class="contact-form col-md-6 wow fadeInUp" data-wow-duration="500ms" data-wow-delay="300ms">
						<?php echo do_shortcode( '[contact-form-7 id="2262" title="Contact form 1"]' ); ?>
					</div>
					<!-- End contact form -->

				</div> <!-- .row -->
			</div> <!-- .container -->

			<?php if ( ta_option( 'disable_gogole_map_module') == '1' ) : ?>
			<!-- Google Map -->
			<div class="google-map wow fadeInDown" data-wow-duration="500ms">
				<div id="map-canvas"></div>
			</div>
			<!-- End Google Map -->
			<?php endif; ?>

		</section>
		<!-- End contact us section -->
		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>