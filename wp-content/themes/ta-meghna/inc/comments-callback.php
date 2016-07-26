<?php
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @package TA Meghna
 */

if ( !function_exists('ta_meghna_comment') ) {
	function ta_meghna_comment( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;
	switch ( $comment -> comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<p><?php _e( 'Pingback:', 'ta_meghna' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'ta_meghna' ), '<span class="ping-meta"><span class="edit-link">', '</span></span>' ); ?></p>
	<?php
		break;
		default :
		// Proceed with normal comments.
	?>
	<li id="li-comment-<?php comment_ID(); ?>" class="media media-comment">
		<div class="comment-wrap">
			<div class="author-avatar pull-left">
				<?php echo get_avatar($comment, 50); ?>
			</div><!-- .comment-author -->
	   
			<div class="author-comment">
				<cite class="pull-left"><?php comment_author_link(); ?></cite>
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'ta_meghna' ) . '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				<div style="clear:both"></div>
				<div class="comment-meta">
				<?php
					printf( '<i class="fa fa-calendar"></i><a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						sprintf( _x( '%1$s at %2$s', '1: date, 2: time', 'ta_meghna' ), get_comment_date(), get_comment_time() )
					);
				?>
				</div>
			</div>

			<div class="comment-content">
			<?php if ('0' == $comment->comment_approved) : ?>
				<p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'ta_meghna'); ?></p>
			<?php endif; ?>

			<?php comment_text(); ?>
			</div>
		</div>
	</li>
	<?php
		break;
		endswitch; // End comment_type check.
	}
}