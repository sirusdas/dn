<?php
/**
 * Post formats template for this theme.
 *
 * @package TA Meghna
 */
 ?>

<?php $meta = get_post_custom($post->ID); ?>

<?php if ( has_post_format('audio') ): // Audio ?>
	
	<div class="post-format">
		<?php if ( isset( $meta['_cmb_audio_code'][0] ) && !empty($meta['_cmb_audio_code'][0]) ) {
			echo '<div class="media-wrapper">';
			echo $meta['_cmb_audio_code'][0];
			echo '</div>';
		} ?>
	</div>
	
<?php endif; ?>

<?php if ( has_post_format( 'gallery' ) ): // Gallery ?>

	<div class="post-format">
		<?php
			$images = ta_post_images();
			if ( !empty( $images ) ):
		?>

		<div id="gallery-post" class="media-wrapper">
		<?php
			foreach ( $images as $image ):
			$imageurl = wp_get_attachment_image_src( $image->ID, 'full' );
		?>
			<div class="item">
				<img class="img-responsive" src="<?php echo $imageurl[0]; ?>" alt="<?php echo $image->post_title; ?>" />
			</div>
		<?php endforeach; ?>
		</div>

		<?php endif; ?>
	</div>
	
<?php endif; ?>

<?php if ( has_post_format( 'video' ) ): // Video ?>

	<div class="post-format">
		<?php if ( isset( $meta['_cmb_video_code'][0] ) && !empty( $meta['_cmb_video_code'][0] ) ) {
			echo '<div class="media-wrapper">';
			echo $meta['_cmb_video_code'][0];
			echo '</div>';
		} ?>
	</div>
	
<?php endif; ?>