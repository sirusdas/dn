<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_slides
 * @author      Luciano "WebCaos" Ubertini
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if ( !defined ( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( !class_exists ( 'ReduxFramework_slides' ) ) {

    /**
     * Main ReduxFramework_slides class
     *
     * @since       1.0.0
     */
    class ReduxFramework_slides {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct ( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;
        }

        /**
         * Field Render Function.
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render () {

            $defaults = array(
                'show' => array(
                    'title'        => true,
                    'description'  => true,
                    'url'          => true,
					'image_upload' => true,
                ),
                'content_title' => __ ( 'Item', 'redux-framework' )
            );

            $this->field = wp_parse_args ( $this->field, $defaults );

            echo '<div class="redux-slides-accordion" data-new-content-title="' . esc_attr ( sprintf ( __ ( 'New %s', 'redux-framework' ), $this->field[ 'content_title' ] ) ) . '">';

            $x = 0;

            $multi = ( isset ( $this->field[ 'multi' ] ) && $this->field[ 'multi' ] ) ? ' multiple="multiple"' : "";

            if ( isset ( $this->value ) && is_array ( $this->value ) && !empty ( $this->value ) ) {

                $slides = $this->value;

                foreach ( $slides as $slide ) {

                    if ( empty ( $slide ) ) {
                        continue;
                    }

                    $defaults = array(
                        'title'         => '',
						'subtitle'      => '',
                        'description'   => '',
                        'sort'          => '',
                        'url'           => '',
						'furl'          => '',
						'turl'          => '',
						'lurl'          => '',
						'facode'        => '',
						'vcode'         => '',
						'btn_a_text'    => '',
						'btn_a_link'    => '',
						'btn_b_text'    => '',
						'btn_b_link'    => '',
                        'image'         => '',
                        'thumb'         => '',
                        'attachment_id' => '',
                        'height'        => '',
                        'width'         => '',
                        'select'        => array(),
                    );
                    $slide = wp_parse_args ( $slide, $defaults );

                    if ( empty ( $slide[ 'thumb' ] ) && !empty ( $slide[ 'attachment_id' ] ) ) {
                        $img = wp_get_attachment_image_src ( $slide[ 'attachment_id' ], 'full' );
                        $slide[ 'image' ] = $img[ 0 ];
                        $slide[ 'width' ] = $img[ 1 ];
                        $slide[ 'height' ] = $img[ 2 ];
                    }

                    echo '<div class="redux-slides-accordion-group"><fieldset class="redux-field" data-id="' . $this->field[ 'id' ] . '"><h3><span class="redux-slides-header">' . $slide[ 'title' ] . '</span></h3><div>';

                    $hide = '';
                    if ( empty ( $slide[ 'image' ] ) ) {
                        $hide = ' hide';
                    }


                    echo '<div class="screenshot' . $hide . '">';
                    echo '<a class="of-uploaded-image" href="' . $slide[ 'image' ] . '">';
                    echo '<img class="redux-slides-image" id="image_image_id_' . $x . '" src="' . $slide[ 'thumb' ] . '" alt="" target="_blank" rel="external" />';
                    echo '</a>';
                    echo '</div>';

					if ( $this->field[ 'show' ][ 'image_upload' ] ) {
						$image_upload = "block";
					} else {
						$image_upload = "none";
					}
                    echo '<div class="redux_slides_add_remove" style="display:'.$image_upload.'">';

                    echo '<span class="button media_upload_button" id="add_' . $x . '">' . __ ( 'Upload', 'redux-framework' ) . '</span>';

                    $hide = '';
                    if ( empty ( $slide[ 'image' ] ) || $slide[ 'image' ] == '' ) {
                        $hide = ' hide';
                    }

                    echo '<span class="button remove-image' . $hide . '" id="reset_' . $x . '" rel="' . $slide[ 'attachment_id' ] . '">' . __ ( 'Remove', 'redux-framework' ) . '</span>';

                    echo '</div>' . "\n";

                    echo '<ul id="' . $this->field[ 'id' ] . '-ul" class="redux-slides-list">';

                    if ( $this->field[ 'show' ][ 'title' ] ) {
                        $title_type = "text";
                    } else {
                        $title_type = "hidden";
                    }

                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'title' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'title' ] ) : __ ( 'Title', 'redux-framework' );
                    echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-title_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][title]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'title' ] ) . '" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

                    if ( isset ( $this->field[ 'show' ][ 'subtitle' ] ) ) {
                        $subtitle_type = "text";
                    } else {
                        $subtitle_type = "hidden";
                    }

                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'subtitle' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'subtitle' ] ) : __ ( 'Subtitle', 'redux-framework' );
                    echo '<li><input type="' . $subtitle_type . '" id="' . $this->field[ 'id' ] . '-subtitle_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][subtitle]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'subtitle' ] ) . '" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

                    if ( $this->field[ 'show' ][ 'description' ] ) {
                        $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'description' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'description' ] ) : __ ( 'Description', 'redux-framework' );
                        echo '<li><textarea name="' . $this->field[ 'name' ] . '[' . $x . '][description]' . $this->field['name_suffix'] . '" id="' . $this->field[ 'id' ] . '-description_' . $x . '" placeholder="' . $placeholder . '" class="large-text" rows="6">' . esc_attr ( $slide[ 'description' ] ) . '</textarea></li>';
                    }

                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'url' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'url' ] ) : __ ( 'URL', 'redux-framework' );
                    if ( $this->field[ 'show' ][ 'url' ] ) {
                        $url_type = "text";
                    } else {
                        $url_type = "hidden";
                    }
					echo '<li><input type="' . $url_type . '" id="' . $this->field[ 'id' ] . '-url_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][url]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'url' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'furl' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'furl' ] ) : __ ( 'FaceBook URL', 'redux-framework' );
                    if ( isset ( $this->field[ 'show' ][ 'furl' ] ) ) {
                        $furl_type = "text";
                    } else {
                        $furl_type = "hidden";
                    }
					echo '<li><input type="' . $furl_type . '" id="' . $this->field[ 'id' ] . '-furl_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][furl]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'furl' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'turl' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'turl' ] ) : __ ( 'Twitter URL', 'redux-framework' );
                    if ( isset ( $this->field[ 'show' ][ 'turl' ] ) ) {
                        $turl_type = "text";
                    } else {
                        $turl_type = "hidden";
                    }
					echo '<li><input type="' . $turl_type . '" id="' . $this->field[ 'id' ] . '-turl_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][turl]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'turl' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'lurl' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'lurl' ] ) : __ ( 'LinkedIn URL', 'redux-framework' );
                    if ( isset ( $this->field[ 'show' ][ 'lurl' ] ) ) {
                        $lurl_type = "text";
                    } else {
                        $lurl_type = "hidden";
                    }
					echo '<li><input type="' . $lurl_type . '" id="' . $this->field[ 'id' ] . '-lurl_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][lurl]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'lurl' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'facode' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'facode' ] ) : __ ( 'Font Awesome Icon', 'redux-framework' );
                    if ( isset ( $this->field[ 'show' ][ 'facode' ] ) ) {
                        $facode_type = "text";
                    } else {
                        $facode_type = "hidden";
                    }
					echo '<li><input type="' . $facode_type . '" id="' . $this->field[ 'id' ] . '-facode_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][facode]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'facode' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

					if ( isset ( $this->field[ 'show' ][ 'vcode' ] ) ) {
                        $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'vcode' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'vcode' ] ) : __ ( 'Video Embed Code', 'redux-framework' );
                        echo '<li><textarea name="' . $this->field[ 'name' ] . '[' . $x . '][vcode]' . $this->field['name_suffix'] . '" id="' . $this->field[ 'id' ] . '-vcode_' . $x . '" placeholder="' . $placeholder . '" class="large-text" rows="6">' . esc_attr ( $slide[ 'vcode' ] ) . '</textarea></li>';
                    }

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'btn_a_text' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'btn_a_text' ] ) : __ ( 'Text for Button 1', 'redux-framework' );
                    if ( isset ( $this->field[ 'show' ][ 'btn_a_text' ] ) ) {
                        $btn_a_text_type = "text";
                    } else {
                        $btn_a_text_type = "hidden";
                    }
					echo '<li><input type="' . $btn_a_text_type . '" id="' . $this->field[ 'id' ] . '-btn_a_text_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][btn_a_text]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'btn_a_text' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'btn_a_link' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'btn_a_link' ] ) : __ ( 'Link for Button 1', 'redux-framework' );
                    if ( isset ( $this->field[ 'show' ][ 'btn_a_link' ] ) ) {
                        $btn_a_link_type = "text";
                    } else {
                        $btn_a_link_type = "hidden";
                    }
					echo '<li><input type="' . $btn_a_link_type . '" id="' . $this->field[ 'id' ] . '-btn_a_link_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][btn_a_link]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'btn_a_link' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'btn_b_text' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'btn_b_text' ] ) : __ ( 'Text for Button 2', 'redux-framework' );
                    if ( isset ( $this->field[ 'show' ][ 'btn_b_text' ] ) ) {
                        $btn_b_text_type = "text";
                    } else {
                        $btn_b_text_type = "hidden";
                    }
					echo '<li><input type="' . $btn_b_text_type . '" id="' . $this->field[ 'id' ] . '-btn_b_text_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][btn_b_text]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'btn_b_text' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

					$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'btn_b_link' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'btn_b_link' ] ) : __ ( 'Link for Button 2', 'redux-framework' );
                    if ( isset ( $this->field[ 'show' ][ 'btn_b_link' ] ) ) {
                        $btn_b_link_type = "text";
                    } else {
                        $btn_b_link_type = "hidden";
                    }
					echo '<li><input type="' . $btn_b_link_type . '" id="' . $this->field[ 'id' ] . '-btn_b_link_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][btn_b_link]' . $this->field['name_suffix'] .'" value="' . esc_attr ( $slide[ 'btn_b_link' ] ) . '" class="full-text" placeholder="' . $placeholder . '" /></li>';

                    echo '<li><input type="hidden" class="slide-sort" name="' . $this->field[ 'name' ] . '[' . $x . '][sort]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-sort_' . $x . '" value="' . $slide[ 'sort' ] . '" />';
                    echo '<li><input type="hidden" class="upload-id" name="' . $this->field[ 'name' ] . '[' . $x . '][attachment_id]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_id_' . $x . '" value="' . $slide[ 'attachment_id' ] . '" />';
                    echo '<input type="hidden" class="upload-thumbnail" name="' . $this->field[ 'name' ] . '[' . $x . '][thumb]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-thumb_url_' . $x . '" value="' . $slide[ 'thumb' ] . '" readonly="readonly" />';
                    echo '<input type="hidden" class="upload" name="' . $this->field[ 'name' ] . '[' . $x . '][image]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_url_' . $x . '" value="' . $slide[ 'image' ] . '" readonly="readonly" />';
                    echo '<input type="hidden" class="upload-height" name="' . $this->field[ 'name' ] . '[' . $x . '][height]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_height_' . $x . '" value="' . $slide[ 'height' ] . '" />';
                    echo '<input type="hidden" class="upload-width" name="' . $this->field[ 'name' ] . '[' . $x . '][width]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_width_' . $x . '" value="' . $slide[ 'width' ] . '" /></li>';
                    echo '<li><a href="javascript:void(0);" class="button deletion redux-slides-remove">' . __ ( 'Delete', 'redux-framework' ) . '</a></li>';
                    echo '</ul></div></fieldset></div>';
                    $x ++;
                }
            }

            if ( $x == 0 ) {
                echo '<div class="redux-slides-accordion-group"><fieldset class="redux-field" data-id="' . $this->field[ 'id' ] . '"><h3><span class="redux-slides-header">New ' . $this->field[ 'content_title' ] . '</span></h3><div>';

                $hide = ' hide';

                echo '<div class="screenshot' . $hide . '">';
                echo '<a class="of-uploaded-image" href="">';
                echo '<img class="redux-slides-image" id="image_image_id_' . $x . '" src="" alt="" target="_blank" rel="external" />';
                echo '</a>';
                echo '</div>';

                //Upload controls DIV
				if ( $this->field[ 'show' ][ 'image_upload' ] ) {
                    $image_upload = "block";
                } else {
                    $image_upload = "none";
                }
                echo '<div class="upload_button_div" style="display:'.$image_upload.'">';

                //If the user has WP3.5+ show upload/remove button
                echo '<span class="button media_upload_button" id="add_' . $x . '">' . __ ( 'Upload', 'redux-framework' ) . '</span>';

                echo '<span class="button remove-image' . $hide . '" id="reset_' . $x . '" rel="' . $this->parent->args[ 'opt_name' ] . '[' . $this->field[ 'id' ] . '][attachment_id]">' . __ ( 'Remove', 'redux-framework' ) . '</span>';

                echo '</div>' . "\n";

                echo '<ul id="' . $this->field[ 'id' ] . '-ul" class="redux-slides-list">';
                if ( $this->field[ 'show' ][ 'title' ] ) {
                    $title_type = "text";
                } else {
                    $title_type = "hidden";
                }
                $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'title' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'title' ] ) : __ ( 'Title', 'redux-framework' );
                echo '<li><input type="' . $title_type . '" id="' . $this->field[ 'id' ] . '-title_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][title]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

				if ( isset ( $this->field[ 'show' ][ 'subtitle' ] ) ) {
					$subtitle_type = "text";
				} else {
					$subtitle_type = "hidden";
				}

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'subtitle' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'subtitle' ] ) : __ ( 'Subtitle', 'redux-framework' );
				echo '<li><input type="' . $subtitle_type . '" id="' . $this->field[ 'id' ] . '-subtitle_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][subtitle]' . $this->field['name_suffix'] . '" value="" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

                if ( $this->field[ 'show' ][ 'description' ] ) {
                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'description' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'description' ] ) : __ ( 'Description', 'redux-framework' );
                    echo '<li><textarea name="' . $this->field[ 'name' ] . '[' . $x . '][description]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-description_' . $x . '" placeholder="' . $placeholder . '" class="large-text" rows="6"></textarea></li>';
                }

                $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'url' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'url' ] ) : __ ( 'URL', 'redux-framework' );
                if ( $this->field[ 'show' ][ 'url' ] ) {
                    $url_type = "text";
                } else {
                    $url_type = "hidden";
                }
				echo '<li><input type="' . $url_type . '" id="' . $this->field[ 'id' ] . '-url_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][url]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'furl' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'furl' ] ) : __ ( 'FaceBook URL', 'redux-framework' );
				if ( isset ( $this->field[ 'show' ][ 'furl' ] ) ) {
					$furl_type = "text";
				} else {
					$furl_type = "hidden";
				}
				echo '<li><input type="' . $furl_type . '" id="' . $this->field[ 'id' ] . '-furl_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][furl]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'turl' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'turl' ] ) : __ ( 'Twitter URL', 'redux-framework' );
				if ( isset ( $this->field[ 'show' ][ 'turl' ] ) ) {
					$turl_type = "text";
				} else {
					$turl_type = "hidden";
				}
				echo '<li><input type="' . $turl_type . '" id="' . $this->field[ 'id' ] . '-turl_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][turl]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'lurl' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'lurl' ] ) : __ ( 'LinkedIn URL', 'redux-framework' );
				if ( isset ( $this->field[ 'show' ][ 'lurl' ] ) ) {
					$lurl_type = "text";
				} else {
					$lurl_type = "hidden";
				}		
				echo '<li><input type="' . $lurl_type . '" id="' . $this->field[ 'id' ] . '-lurl_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][lurl]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'facode' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'facode' ] ) : __ ( 'Font Awesome Icon', 'redux-framework' );
				if ( isset ( $this->field[ 'show' ][ 'facode' ] ) ) {
					$facode_type = "text";
				} else {
					$facode_type = "hidden";
				}		
				echo '<li><input type="' . $facode_type . '" id="' . $this->field[ 'id' ] . '-facode_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][facode]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

				if ( isset ( $this->field[ 'show' ][ 'vcode' ] ) ) {
                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'vcode' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'vcode' ] ) : __ ( 'Video Embed Code', 'redux-framework' );
                    echo '<li><textarea name="' . $this->field[ 'name' ] . '[' . $x . '][vcode]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-vcode_' . $x . '" placeholder="' . $placeholder . '" class="large-text" rows="6"></textarea></li>';
                }

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'btn_a_text' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'btn_a_text' ] ) : __ ( 'Text for Button 1', 'redux-framework' );
				if ( isset ( $this->field[ 'show' ][ 'btn_a_text' ] ) ) {
					$btn_a_text_type = "text";
				} else {
					$btn_a_text_type = "hidden";
				}		
				echo '<li><input type="' . $btn_a_text_type . '" id="' . $this->field[ 'id' ] . '-btn_a_text_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][btn_a_text]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'btn_a_link' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'btn_a_link' ] ) : __ ( 'Link for Button 1', 'redux-framework' );
				if ( isset ( $this->field[ 'show' ][ 'btn_a_link' ] ) ) {
					$btn_a_link_type = "text";
				} else {
					$btn_a_link_type = "hidden";
				}		
				echo '<li><input type="' . $btn_a_link_type . '" id="' . $this->field[ 'id' ] . '-btn_a_link_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][btn_a_link]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'btn_b_text' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'btn_b_text' ] ) : __ ( 'Text for Button 2', 'redux-framework' );
				if ( isset ( $this->field[ 'show' ][ 'btn_b_text' ] ) ) {
					$btn_b_text_type = "text";
				} else {
					$btn_b_text_type = "hidden";
				}		
				echo '<li><input type="' . $btn_b_text_type . '" id="' . $this->field[ 'id' ] . '-btn_b_text_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][btn_b_text]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

				$placeholder = ( isset ( $this->field[ 'placeholder' ][ 'btn_b_link' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'btn_b_link' ] ) : __ ( 'Link for Button 2', 'redux-framework' );
				if ( isset ( $this->field[ 'show' ][ 'btn_b_link' ] ) ) {
					$btn_b_link_type = "text";
				} else {
					$btn_b_link_type = "hidden";
				}		
				echo '<li><input type="' . $btn_b_link_type . '" id="' . $this->field[ 'id' ] . '-btn_b_link_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][btn_b_link]' . $this->field['name_suffix'] .'" value="" class="full-text" placeholder="' . $placeholder . '" /></li>';

                echo '<li><input type="hidden" class="slide-sort" name="' . $this->field[ 'name' ] . '[' . $x . '][sort]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-sort_' . $x . '" value="' . $x . '" />';
                echo '<li><input type="hidden" class="upload-id" name="' . $this->field[ 'name' ] . '[' . $x . '][attachment_id]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_id_' . $x . '" value="" />';
                echo '<input type="hidden" class="upload" name="' . $this->field[ 'name' ] . '[' . $x . '][image]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_url_' . $x . '" value="" readonly="readonly" />';
                echo '<input type="hidden" class="upload-height" name="' . $this->field[ 'name' ] . '[' . $x . '][height]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_height_' . $x . '" value="" />';
                echo '<input type="hidden" class="upload-width" name="' . $this->field[ 'name' ] . '[' . $x . '][width]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-image_width_' . $x . '" value="" /></li>';
                echo '<input type="hidden" class="upload-thumbnail" name="' . $this->field[ 'name' ] . '[' . $x . '][thumb]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-thumb_url_' . $x . '" value="" /></li>';
                echo '<li><a href="javascript:void(0);" class="button deletion redux-slides-remove">' . __ ( 'Delete', 'redux-framework' ) . '</a></li>';
                echo '</ul></div></fieldset></div>';
            }
            echo '</div><a href="javascript:void(0);" class="button redux-slides-add button-primary" rel-id="' . $this->field[ 'id' ] . '-ul" rel-name="' . $this->field[ 'name' ] . '[title][]' . $this->field['name_suffix'] .'">' . sprintf ( __ ( 'Add %s', 'redux-framework' ), $this->field[ 'content_title' ] ) . '</a><br/>';
        }

        /**
         * Enqueue Function.
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue () {
            if ( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            } else {
                wp_enqueue_script( 'media-upload' );
            }
                
            if ($this->parent->args['dev_mode']){
                wp_enqueue_style ('redux-field-media-css');
                
                wp_enqueue_style (
                    'redux-field-slides-css', 
                    ReduxFramework::$_url . 'inc/fields/slides/field_slides.css', 
                    array(),
                    time (), 
                    'all'
                );
            }
            
            wp_enqueue_script(
                'redux-field-media-js',
                ReduxFramework::$_url . 'assets/js/media/media' . Redux_Functions::isMin() . '.js',
                array( 'jquery', 'redux-js' ),
                time(),
                true
            );

            wp_enqueue_script (
                'redux-field-slides-js', 
                ReduxFramework::$_url . 'inc/fields/slides/field_slides' . Redux_Functions::isMin () . '.js', 
                array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-sortable', 'redux-field-media-js' ),
                time (), 
                true
            );
        }
    }

}