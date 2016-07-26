<?php
/**
 * Post Tabs Widget Class
 *
 * @package TA Meghna
 */

class ta_post_tabs_widget extends WP_Widget {
	/* Constructor method */
	function __construct() {
        $widget_ops = array( 'description' => __( "Display popular posts, recent posts and comments in tabbed format.", 'ta-meghna' ) );
         parent::__construct( '', __( 'TA Meghna: Post Tabs Widget', 'ta-meghna' ), $widget_ops );
    }

	/* Render this widget in the sidebar */
	function widget( $args, $instance ) {
		extract( $args );
		/* Our variables from the widget settings. */
		$number = $instance['number'];
		echo $before_widget;
?>

		<div class="post-tabs">
			<!-- tab nav -->
			<ul class="tab-post-nav clearfix">
				<li class="active"><a href="#popular" data-toggle="tab"><?php _e( 'Popular Posts', 'ta-meghna' ) ?></a></li>
				<li><a href="#recent" data-toggle="tab"><?php _e( 'Recent Posts', 'ta-meghna' ) ?></a></li>
				<li><a href="#most-comments" data-toggle="tab"><?php _e( 'Most Comments', 'ta-meghna' ) ?></a></li>
			</ul>
			<!-- /tab nav -->

			<div class="tab-content">
				<article class="tab-pane active tab-post" id="popular">
					<?php if( function_exists('stats_get_csv') ) { // get popular posts by WordPress.com states if Jetpack plugin installed.
						$count = 0;
						$popular_posts = stats_get_csv( 'postviews', array( 'days' => -1, 'limit' => -1 ) ); ?>
						<?php foreach ( $popular_posts as $p ) {
							if ( $count >= $number ) {
								break;
							}
							
							if ( 'post' == get_post_type( $p['post_id'] ) && 'publish' == get_post_status ( $p['post_id'] ) && false == post_password_required ( $p['post_id'] ) && 0 != $p['post_id'] ) { ?>
								<div class="clearfix">
									<?php if ( has_post_thumbnail( $p['post_id'] ) ) : ?>
									<div class="tab-thumb">
										<a href="<?php echo $p['post_permalink']; ?>" title="<?php echo $p['post_title']; ?>">
											<?php echo get_the_post_thumbnail( $p['post_id'], 'full', array( 'class' => 'img-responsive' ) ); ?>
										</a>
									</div>
									<?php endif; ?>

									<div class="tab-excerpt">
										<h4><a href="<?php echo $p['post_permalink']; ?>" title="<?php echo $p['post_title']; ?>"><?php echo $p['post_title']; ?></a></h4>
										<span><?php echo get_the_date( '', $p['post_id'] ); ?></span>
										<p><?php echo trim_characters( get_post_field( 'post_content', $p['post_id'] ) );?></p>
									</div>
								</div>
							<?php $count++; }
						} ?>
					<?php }

					else { // get popular posts by comment count.
						$popular_posts = new WP_Query( array( 'showposts' => $number, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'has_password' => false, 'orderby' => 'comment_count', 'order'=> 'DESC', ) );
						while( $popular_posts->have_posts() ): $popular_posts->the_post(); ?>

							<div class="clearfix">
								<?php if ( has_post_thumbnail() ) : ?>
								<div class="tab-thumb">
									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
										<?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) ); ?>
									</a>
								</div>
								<?php endif; ?>

								<div class="tab-excerpt">
									<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
									<span><?php echo get_the_date(); ?></span>
									<p><?php echo trim_characters( get_the_content() );?></p>
								</div>
							</div>

						<?php endwhile;
					} ?>
				</article>
				<?php wp_reset_query(); ?>

				<article class="tab-pane tab-post" id="recent">
					<?php $recent_posts = new WP_Query( array( 'showposts' => $number, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'has_password' => false ) ); ?>
					<?php while( $recent_posts->have_posts() ): $recent_posts->the_post(); ?>

						<div class="clearfix">
							<?php if ( has_post_thumbnail() ) : ?>
							<div class="tab-thumb">
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
									<?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) ); ?>
								</a>
							</div>
							<?php endif; ?>

							<div class="tab-excerpt">
								<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
								<span><?php echo get_the_date(); ?></span>
								<p><?php echo trim_characters( get_the_content() );?></p>
							</div>
						</div>

					<?php endwhile; ?>
				</article>
				<?php wp_reset_query(); ?>

				<article class="tab-pane tab-post" id="most-comments">
					<?php $recent_comments = get_comments( array ( 'number' => $number, 'status' => 'approve' ) ); ?>
					<?php foreach( $recent_comments as $comment ) : ?>
					
						<div class="clearfix">
							<i class="fa fa-comment-o"></i>
							<?php if ( $comment->comment_author ) { echo $comment->comment_author; } else { _e( 'Anonymous','ta-meghna' ); } ?> <?php _e( 'on','ta-meghna' ); ?>
								<a href="<?php echo get_permalink( $comment->comment_post_ID ) ?>" rel="bookmark" title="<?php echo get_the_title( $comment->comment_post_ID ); ?>">
									<?php echo get_the_title( $comment->comment_post_ID ); ?>
								</a>
							<p>
								<i class="fa fa-quote-left"></i>
								<?php echo wp_trim_words( $comment->comment_content, 15 ) ;?>
								<i class="fa fa-quote-right"></i>
							</p>
						</div>

					<?php endforeach; ?>
				</article>
			</div>
		</div>

		<?php echo $after_widget;
	}

	/* Output user options */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'number' => 5 );
		$instance = wp_parse_args( ( array ) $instance, $defaults ); ?>

		<!-- Number of posts -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show', 'ta-meghna' ) ?>:</label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" size="1" />
		</p>

	<?php }
	
	/* Update the widget settings */
	function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['number'] = strip_tags( $new_instance['number'] );

		return $instance;
	}

}// end ta_post_tabs_widget

?>