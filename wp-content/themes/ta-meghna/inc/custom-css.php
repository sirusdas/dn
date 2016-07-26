<?php
/**
 * Adds custom CSS to the wp_head() hook.
 *
 * @package WordPress
 * @subpackage TA Meghna
 */

if ( !function_exists( 'ta_custom_css' ) ) {

	add_action( 'wp_head', 'ta_custom_css' );
	function ta_custom_css() {

			$custom_css = '';

			if( ta_option( 'slider_1_bg', false, 'url' ) != '' ) {
				$slider_1_bg_url = ta_option( 'slider_1_bg', false, 'url' );
				$custom_css .= '#header-slider .bg-img-1 { background-image: url('.$slider_1_bg_url.'); }';
			}

			if( ta_option( 'slider_2_bg', false, 'url' ) != '' ) {
				$slider_2_bg_url = ta_option( 'slider_2_bg', false, 'url' );
				$custom_css .= '#header-slider .bg-img-2 { background-image: url('.$slider_2_bg_url.'); }';
			}

			if( ta_option( 'slider_3_bg', false, 'url' ) != '' ) {
				$slider_3_bg_url = ta_option( 'slider_3_bg', false, 'url' );
				$custom_css .= '#header-slider .bg-img-3 { background-image: url('.$slider_3_bg_url.'); }';
			}

			if( ta_option( 'counter_bg', false, 'url' ) != '' ) {
				$counter_bg_url = ta_option( 'counter_bg', false, 'url' );
				$custom_css .= '.counter-section { background-image: url('.$counter_bg_url.'); }';
			}

			if( ta_option( 'skills_bg', false, 'url' ) != '' ) {
				$skills_bg_url = ta_option( 'skills_bg', false, 'url' );
				$custom_css .= '.team-skills { background-image: url('.$skills_bg_url.'); }';
			}

			if( ta_option( 'twitter_bg', false, 'url' ) != '' ) {
				$twitter_bg_url = ta_option( 'twitter_bg', false, 'url' );
				$custom_css .= '.twitter-feed { background-image: url('.$twitter_bg_url.'); }';
			}

			if( ta_option( 'testimonial_bg', false, 'url' ) != '' ) {
				$testimonial_bg_url = ta_option( 'testimonial_bg', false, 'url' );
				$custom_css .= '.testimonial-section { background-image: url('.$testimonial_bg_url.'); }';
			}

			if( ta_option( 'custom_css' ) != '' ) {
				$custom_css .= ta_option( 'custom_css' );
			}

			//Trim white space for faster page loading
			$custom_css_trimmed =  preg_replace( '/\s+/', ' ', $custom_css );

			//Echo CSS
			$css_output = "<!-- Custom CSS -->\n<style type=\"text/css\">\n" . $custom_css_trimmed . "\n</style>";

			if( !empty( $custom_css ) ) {
				echo $css_output;
			}
	}

}