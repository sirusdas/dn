<?php
    /**
     * ReduxFramework Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux_Framework_ta_config' ) ) {

        class Redux_Framework_ta_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Set the default arguments
                $this->setArguments();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            /**
             * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
             * Simply include this function in the child themes functions.php file.
             * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
             * so you must use get_template_directory_uri() if you want to use any of the built in icons
             * */
            function dynamic_section( $sections ) {
                //$sections = array();
                $sections[] = array(
                    'title'  => __( 'Section via hook', 'ta-meghna' ),
                    'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'ta-meghna' ),
                    'icon'   => 'el el-paper-clip',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );

                return $sections;
            }

            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             * */
            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }

            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             * */
            function change_defaults( $defaults ) {
                $defaults['str_replace'] = 'Testing filter hook!';

                return $defaults;
            }

            public function setSections() {

				// Array of social options
                $social_options = array(
                    'Twitter'       => 'Twitter',
                    'Facebook'      => 'Facebook',
                    'Google Plus'   => 'Google Plus',
                    'Instagram'     => 'Instagram',
                    'LinkedIn'      => 'LinkedIn',
                    'Tumblr'        => 'Tumblr',
                    'Pinterest'     => 'Pinterest',
                    'Dribbble'      => 'Dribbble',
                    'Flickr'        => 'Flickr',
					'DeviantArt'    => 'DeviantArt',
                    'Skype'         => 'Skype',
                    'YouTube'       => 'YouTube',
                    'Vimeo'         => 'Vimeo',
                    'GitHub'        => 'GitHub',
                    'RSS'           => 'RSS',
					'SoundCloud'    => 'SoundCloud',
                );

                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'ta-meghna' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'ta-meghna' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'ta-meghna' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo $this->theme->display( 'Name' ); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'ta-meghna' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'ta-meghna' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'ta-meghna' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'ta-meghna' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'ta-meghna' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

				//General Settings
				$this->sections[] = array(
                    'title'         => __( 'General', 'ta-meghna' ),
                    'heading'       => __( 'General Settings', 'ta-meghna' ),
                    'desc'          => __( 'Here you can upload site logo and favicon, and set site preloader.', 'ta-meghna' ),
                    'icon'          => 'el el-cog',
                    'fields'        => array(
						array(
                            'title'     => __( 'Site Preloader', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable site preloader.', 'ta-meghna' ),
                            'id'        => 'disable_preloader',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
                       ),

					   array(
                            'title'     => __( 'Loading GIF', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your site loading GIF.', 'ta-meghna' ),
                            'id'        => 'site_preloader',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                       ),

					   array( 
                            'title'     => __( 'Favicon', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your custom favicon.', 'ta-meghna' ),
                            'id'        => 'custom_favicon',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array(
                            'title'     => __( 'Logo', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your custom logo. Recommended dimensions are 160x45 pixels', 'ta-meghna' ),
                            'id'        => 'custom_logo',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                       ),
                   ),
				);

				//Header Slider Settings
                $this->sections[] = array(
					'title'         => __('Header Slider', 'ta-meghna'),
                    'heading'       => __('Header Slider Settings', 'ta-meghna'),
                    'icon'          => 'el el-website',
                    'fields'    => array(
						array(
                            'title'     => __( 'Header Slider Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Header Slider Module.', 'ta-meghna' ),
                            'id'        => 'disable_header_slider',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
                       ),
					)
				);

				$this->sections[] = array(
					'title'         => __( 'Header Slider 1 Settings', 'ta-meghna' ),
                    'heading'       => __( 'Header Slider 1 Settings', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set your responsive header image slider 1.', 'ta-meghna' ),
					'subsection' => true,
                    'fields'    => array(
                        array( 
                            'title'     => __( 'Background Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your background image for slider 1.', 'ta-meghna' ),
                            'id'        => 'slider_1_bg',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array( 
                            'title'     => __( 'Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your image for slider 1 title.', 'ta-meghna' ),
                            'id'        => 'slider_1_img',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array( 
                            'title'     => __( 'Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to add your title text.', 'ta-meghna' ),
                            'id'        => 'slider_1_title',
                            'default'   => __( 'This is a title', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Subtitle', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to add your subtitle text.', 'ta-meghna' ),
                            'id'        => 'slider_1_subtitle',
                            'default'   => __( 'This is a subtitle', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Button Text', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to set your button text.', 'ta-meghna' ),
                            'id'        => 'slider_1_button_text',
                            'default'   => __( 'Click Me', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Button Link', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to set your button link.', 'ta-meghna' ),
                            'id'        => 'slider_1_button_link',
                            'default'   => 'http://themeart.co',
                            'type'      => 'text',
							'validate'  => 'url',
							'msg'       => 'Not a valid URL address.',
                        ),
					)
				);

				$this->sections[] = array(
					'title'         => __( 'Header Slider 2 Settings', 'ta-meghna' ),
                    'heading'       => __( 'Header Slider 2 Settings', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set your responsive header image slider 2.', 'ta-meghna' ),
					'subsection' => true,
                    'fields'    => array(
                        array( 
                            'title'     => __( 'Background Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your background image for slider 2.', 'ta-meghna' ),
                            'id'        => 'slider_2_bg',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array( 
                            'title'     => __( 'Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your image for slider 2 title.', 'ta-meghna' ),
                            'id'        => 'slider_2_img',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array( 
                            'title'     => __( 'Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to add your title text.', 'ta-meghna' ),
                            'id'        => 'slider_2_title',
                            'default'   => __( 'This is a title', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Subtitle', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to add your subtitle text.', 'ta-meghna' ),
                            'id'        => 'slider_2_subtitle',
                            'default'   => __( 'This is a subtitle', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Button Text', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to set your button text.', 'ta-meghna' ),
                            'id'        => 'slider_2_button_text',
                            'default'   => __( 'Click Me', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Button Link', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to set your button link.', 'ta-meghna' ),
                            'id'        => 'slider_2_button_link',
                            'default'   => 'http://themeart.co',
                            'type'      => 'text',
							'validate'  => 'url',
							'msg'       => 'Not a valid URL address.',
                        ),
					)
				);

				$this->sections[] = array(
					'title'         => __( 'Header Slider 3 Settings', 'ta-meghna' ),
                    'heading'       => __( 'Header Slider 3 Settings', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set your responsive header image slider 3.', 'ta-meghna' ),
					'subsection' => true,
                    'fields'    => array(
                        array( 
                            'title'     => __( 'Background Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your background image for slider 3.', 'ta-meghna' ),
                            'id'        => 'slider_3_bg',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array( 
                            'title'     => __( 'Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your image for slider 3 title.', 'ta-meghna' ),
                            'id'        => 'slider_3_img',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array( 
                            'title'     => __( 'Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to add your title text.', 'ta-meghna' ),
                            'id'        => 'slider_3_title',
                            'default'   => __( 'This is a title', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Subtitle', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to add your subtitle text.', 'ta-meghna' ),
                            'id'        => 'slider_3_subtitle',
                            'default'   => __( 'This is a subtitle', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Button Text', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to set your button text.', 'ta-meghna' ),
                            'id'        => 'slider_3_button_text',
                            'default'   => __( 'Click Me', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Button Link', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to set your button link.', 'ta-meghna' ),
                            'id'        => 'slider_3_button_link',
                            'default'   => 'http://themeart.co',
                            'type'      => 'text',
							'validate'  => 'url',
							'msg'       => 'Not a valid URL address.',
                        ),
					)
				);

				// About Us Settings
                $this->sections[] = array(
					'title'         => __( 'About Us', 'ta-meghna' ),
                    'heading'       => __( 'About Us', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set <strong>About Us</strong> section.', 'ta-meghna' ),
                    'icon'          => 'el el-myspace',
                    'fields'    => array(
                        array(
                            'title'     => __( 'About Us Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display About Us section.', 'ta-meghna' ),
                            'id'        => 'disable_about_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'About Us Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for About Us section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_about',
                            'default'   => 'about',
                            'type'      => 'text',
                        ),

						array(
							'title'     => __( 'About Us Section Title', 'ta-meghna' ),
							'subtitle'  => __( 'Add your own title for About Us section.', 'ta-meghna' ),
							'id'        => 'title_about',
							'default'   => 'About <span class="color">Us</span>',
							'type'      => 'text',
						),

						array(
							'id'          => 'about_slides',
							'type'        => 'slides',
							'title'       => __( 'About Us Box', 'ta-meghna' ),
							'subtitle'    => __( 'Unlimited About Us Box with drag and drop sortings.', 'ta-meghna' ),
							'desc'        => __( 'You can get Font Awesome Icon <a href="http://fontawesome.io/icons/" target="_blank">here</a>. e.g. folder-open. Leave blank to upload your image.', 'ta-meghna' ),
							'show'        => array(
								'facode'       => true,
								'title'        => true,
								'description'  => true,
								'image_upload' => true,
								'url'          => false,
							),
							'placeholder' => array(
								'facode'       => __( 'Font Awesome Icon here. e.g. folder-open.', 'ta-meghna' ),
								'title'        => __( 'This is a title.', 'ta-meghna' ),
								'description'  => __( 'Description here.', 'ta-meghna' ),
							),
						),
					),
                );

				// Features Settings
                $this->sections[] = array(
					'title'         => __( 'Features', 'ta-meghna' ),
                    'heading'       => __( 'Features', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set <strong>Features</strong> section.', 'ta-meghna' ),
                    'icon'          => 'el el-th-large',
                    'fields'    => array(
                        array(
                            'title'     => __( 'Features Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Features section.', 'ta-meghna' ),
                            'id'        => 'disable_features_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Features Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Features section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_features',
                            'default'   => 'features',
                            'type'      => 'text',
                        ),

						array(
							'id'          => 'features_slides',
							'type'        => 'slides',
							'title'       => __( 'Features Box', 'ta-meghna' ),
							'subtitle'    => __( 'Unlimited Features Box with drag and drop sortings.', 'ta-meghna' ),
							'desc'        => __( 'You can leave Video Embed Code field blank to upload your image.', 'ta-meghna' ),
							'show'        => array(
								'vcode'        => true,
								'title'        => true,
								'description'  => true,
								'image_upload' => true,
								'btn_a_text'   => true,
								'btn_a_link'   => true,
								'btn_b_text'   => true,
								'btn_b_link'   => true,
								'url'          => false,
							),
							'placeholder' => array(
								'vcode'        => __( 'Video embed code here.', 'ta-meghna' ),
								'title'        => __( 'This is a title.', 'ta-meghna' ),
								'description'  => __( 'Description here.', 'ta-meghna' ),
								'btn_a_text'   => __( 'Text for button 1. Leave blank to disable the button.', 'ta-meghna' ),
								'btn_a_link'   => __( 'Link for button 1.', 'ta-meghna' ),
								'btn_b_text'   => __( 'Text for button 2. Leave blank to disable the button.', 'ta-meghna' ),
								'btn_b_link'   => __( 'Link for button 2.', 'ta-meghna' ),
							),
						),
					),
                );

				// Counter Settings
                $this->sections[] = array(
					'title'         => __( 'Counter', 'ta-meghna' ),
                    'heading'       => __( 'Counter', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set <strong>Counter</strong> section.', 'ta-meghna' ),
                    'icon'          => 'el el-dashboard',
                    'fields'    => array(
                        array(
                            'title'     => __( 'Counter Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Counter section.', 'ta-meghna' ),
                            'id'        => 'disable_counter_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Counter Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Counter section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_counter',
                            'default'   => 'counter',
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Background Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your background image for Counter section.', 'ta-meghna' ),
                            'id'        => 'counter_bg',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array(
							'id'          => 'counter_slides',
							'type'        => 'slides',
							'title'       => __( 'Counter Box', 'ta-meghna' ),
							'subtitle'    => __( 'Unlimited Counter Box with drag and drop sortings.', 'ta-meghna' ),
							'desc'        => __( 'You can get Font Awesome Icon <a href="http://fontawesome.io/icons/" target="_blank">here</a>. e.g. folder-open.', 'ta-meghna' ),
							'show'        => array(
								'facode'       => true,
								'title'        => true,
								'subtitle'     => true,
								'description'  => false,
								'image_upload' => false,
								'url'          => false,
							),
							'placeholder' => array(
								'facode'       => __( 'Font Awesome Icon here. e.g. folder-open.', 'ta-meghna' ),
								'title'        => __( 'This is a title.', 'ta-meghna' ),
								'subtitle'     => __( 'Counter number here. e.g. 100 or 100%.', 'ta-meghna' ),
							),
						),
					),
                );

				// Services Settings
				$this->sections[] = array(
					'title'         => __( 'Services', 'ta-meghna' ),
                    'heading'       => __( 'Services', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set <strong>Services</strong> section.', 'ta-meghna' ),
                    'icon'          => 'el el-smiley',
                    'fields'    => array(
                        array(
                            'title'     => __( 'Services Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Services section.', 'ta-meghna' ),
                            'id'        => 'disable_services_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Services Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Services section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_services',
                            'default'   => 'services',
                            'type'      => 'text',
                        ),

						array(
							'title'     => __( 'Services Section Title', 'ta-meghna' ),
							'subtitle'  => __( 'Add your own title for Services section.', 'ta-meghna' ),
							'id'        => 'title_services',
							'default'   => 'Our <span class="color">Services</span>',
							'type'      => 'text',
						),

						array(
							'id'          => 'services_slides',
							'type'        => 'slides',
							'title'       => __( 'Services Box', 'ta-meghna' ),
							'subtitle'    => __( 'Unlimited Services Box with drag and drop sortings.', 'ta-meghna' ),
							'desc'        => __( 'You can get Font Awesome Icon <a href="http://fontawesome.io/icons/" target="_blank">here</a>. e.g. folder-open. Leave blank to upload your image.', 'ta-meghna' ),
							'show'        => array(
								'facode'       => true,
								'title'        => true,
								'description'  => true,
								'image_upload' => true,
								'url'          => false,
							),
							'placeholder' => array(
								'facode'       => __( 'Font Awesome Icon here. e.g. folder-open.', 'ta-meghna' ),
								'title'        => __( 'This is a title.', 'ta-meghna' ),
								'description'     => __( 'Description here.', 'ta-meghna' ),
							),
						),
					),
                );

				//Portfolio Settings
                $this->sections[] = array(
                    'icon'      => 'el el-folder',
                    'title'     => __( 'Portfolio', 'ta-meghna' ),
                    'fields'    => array(
						array(
                            'title'     => __( 'Portfolio Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Portfolio section.', 'ta-meghna' ),
                            'id'        => 'disable_portfolio_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Portfolio Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Portfolio section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_portfolio',
                            'default'   => 'showcase',
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Display Filter', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable the portfolio filter.', 'ta-meghna' ),
                            'id'        => 'filter_switch',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
                        ),

						array(
                            'title'     => __( 'Portfolio Section Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Portfolio section.', 'ta-meghna' ),
                            'id'        => 'title_portfolio',
                            'default'   => 'Our <span class="color">Works</span>',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Portfolio Link Text', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your link text for view button.', 'ta-meghna' ),
                            'id'        => 'link_portfolio',
                            'default'   => 'View Project',
                            'type'      => 'text',
                        ),
                    ),
                );

				// Team Skills Settings
                $this->sections[] = array(
					'title'         => __( 'Team Skills', 'ta-meghna' ),
                    'heading'       => __( 'Team Skills', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set <strong>Team Skills</strong> section.', 'ta-meghna' ),
                    'icon'          => 'el el-th-list',
                    'fields'    => array(
                        array(
                            'title'     => __( 'Team Skills Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Team Skills section.', 'ta-meghna' ),
                            'id'        => 'disable_skills_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Team Skills ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Team Skills section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_skills',
                            'default'   => 'team-skills',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Team Skills Section Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Team Skills section.', 'ta-meghna' ),
                            'id'        => 'title_skills',
                            'default'   => 'Our <span class="color">Skills</span>',
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Background Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your background image for Team Skills section.', 'ta-meghna' ),
                            'id'        => 'skills_bg',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array(
							'id'          => 'skills_slides',
							'type'        => 'slides',
							'title'       => __( 'Team Skills Box', 'ta-meghna' ),
							'subtitle'    => __( 'Unlimited Team Skills Box with drag and drop sortings.', 'ta-meghna' ),
							'desc'        => __( 'You can get Font Awesome Icon <a href="http://fontawesome.io/icons/" target="_blank">here</a>. e.g. folder-open. Leave blank to disable it.', 'ta-meghna' ),
							'show'        => array(
								'facode'       => true,
								'title'        => true,
								'subtitle'     => true,
								'description'  => true,
								'image_upload' => false,
								'url'          => false,
							),
							'placeholder' => array(
								'facode'       => __( 'Font Awesome Icon here. e.g. folder-open.', 'ta-meghna' ),
								'title'        => __( 'This is a title.', 'ta-meghna' ),
								'subtitle'     => __( 'Skill number here. e.g. 98.', 'ta-meghna' ),
								'description'  => __( 'Description here.', 'ta-meghna' ),
							),
						),
					),
                );

				// Our Team Settings
                $this->sections[] = array(
                    'title'    => __( 'Our Team', 'ta-meghna' ),
                    'heading'  => __( 'Our Team', 'ta-meghna' ),
                    'desc'     => __( 'Here you can set <strong>Our Team</strong> section.', 'ta-meghna' ),
                    'icon'     => 'el el-user',
                    'fields'   => array(
						array(
                            'title'     => __( 'Our Team Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Our Team section.', 'ta-meghna' ),
                            'id'        => 'disable_team_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Our Team Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Our Team section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_team',
                            'default'   => 'our-team',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Our Team Section Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Our Team section.', 'ta-meghna' ),
                            'id'        => 'title_team',
                            'default'   => 'Our <span class="color">Team</span>',
                            'type'      => 'text',
                        ),

						array(
							'id'          => 'team_slides',
							'type'        => 'slides',
							'title'       => __( 'Our Team Box', 'ta-meghna' ),
							'subtitle'    => __( 'Unlimited Our Team Box with drag and drop sortings.', 'ta-meghna' ),
							'show'        => array(
								'title'        => true,
								'subtitle'     => true,
								'description'  => true,
								'image_upload' => true,
								'url'          => false,
								'furl'         => true,
								'turl'         => true,
								'lurl'         => true,
							),
							'placeholder' => array(
								'title'        => __( 'This is a name.', 'ta-meghna' ),
								'subtitle'     => __( 'This is a role.', 'ta-meghna' ),
								'description'  => __( 'Description here.', 'ta-meghna' ),
								'furl'         => __( 'Give me a Facebook link.', 'ta-meghna' ),
								'turl'         => __( 'Give me a Twitter link.', 'ta-meghna' ),
								'lurl'         => __( 'Give me a LinkedIn link.', 'ta-meghna' ),
							),
						),
                    ),
                );

				// Twitter Feed Settings
                $this->sections[] = array(
					'title'         => __( 'Twitter Feed', 'ta-meghna' ),
                    'heading'       => __( 'Twitter Feed', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set <strong>Twitter Feed</strong> section. You can refer to the <a href="http://themeart.co/document/ta-meghna-theme-documentation/#twitter-api-settings" target="_blank">theme documentation</a> to get Twitter API Consumer and Secret Keys.', 'ta-meghna' ),
                    'icon'          => 'el el-twitter',
                    'fields'    => array(
                        array(
                            'title'     => __( 'Twitter Feed Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Twitter Feed section.', 'ta-meghna' ),
                            'id'        => 'disable_twitter_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Twitter Feed ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Twitter Feed section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_twitter',
                            'default'   => 'twitter-feed',
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Background Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your background image for Twitter Feed section.', 'ta-meghna' ),
                            'id'        => 'twitter_bg',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array(
                            'title'     => __( 'Twitter Username', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your Twitter username.', 'ta-meghna' ),
                            'id'        => 'twitter_username',
                            'default'   => '',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Follow Link Text', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your link text for Follow button.', 'ta-meghna' ),
                            'id'        => 'link_follow',
                            'default'   => 'Follow Us',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Twitter Consumer Key', 'ta-meghna' ),
                            'id'        => 'twiiter_consumer_key',
                            'default'   => '',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Twitter Consumer Secret', 'ta-meghna' ),
                            'id'        => 'twiiter_consumer_secret',
                            'default'   => '',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Twitter Access Token', 'ta-meghna' ),
                            'id'        => 'twiiter_access_token',
                            'default'   => '',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Twitter Access Token Secret', 'ta-meghna' ),
                            'id'        => 'twiiter_access_token_secret',
                            'default'   => '',
                            'type'      => 'text',
                        ),
					),
                );

				// Pricing Settings
                $this->sections[] = array(
                    'title'    => __( 'Pricing', 'ta-meghna' ),
                    'heading'  => __( 'Pricing', 'ta-meghna' ),
                    'desc'     => __( 'Here you can set <strong>Pricing</strong> section.', 'ta-meghna' ),
                    'icon'     => 'el el-usd',
                    'fields'   => array(
						array(
                            'title'     => __( 'Pricing Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Pricing section.', 'ta-meghna' ),
                            'id'        => 'disable_price_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Pricing Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Pricing section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_price',
                            'default'   => 'pricing',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Pricing Section Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Pricing section.', 'ta-meghna' ),
                            'id'        => 'title_price',
                            'default'   => 'Our Greatest<span class="color"> Plans</span>',
                            'type'      => 'text',
                        ),

						array(
							'id'          => 'price_slides',
							'type'        => 'slides',
							'title'       => __( 'Pricing Box', 'ta-meghna' ),
							'subtitle'    => __( 'Unlimited Pricing Box with drag and drop sortings.', 'ta-meghna' ),
							'show'        => array(
								'title'        => true,
								'facode'       => true,
								'subtitle'     => true,
								'description'  => true,
								'image_upload' => false,
								'url'          => false,
								'btn_a_text'   => true,
								'btn_a_link'   => true,
							),
							'placeholder' => array(
								'title'        => __( 'This is a title.', 'ta-meghna' ),
								'subtitle'     => __( 'Price for plan here.', 'ta-meghna' ),
								'description'  => __( 'A list of features here. Do not forget to add <ul> and <li>: <ul><li>Feature 1</li><li>Feature 2</li></ul>.', 'ta-meghna' ),
								'facode'       => __( 'Subscription payment cycle here. e.g. month.', 'ta-meghna' ),
								'btn_a_text'   => __( 'Text for button here.', 'ta-meghna' ),
								'btn_a_link'   => __( 'URL for button here.', 'ta-meghna' ),
							),
						),
                    ),
                );

				// Testimonial Settings
                $this->sections[] = array(
                    'title'    => __( 'Testimonial', 'ta-meghna' ),
                    'heading'  => __( 'Testimonial', 'ta-meghna' ),
                    'desc'     => __( 'Here you can set <strong>Testimonial</strong> section.', 'ta-meghna' ),
                    'icon'     => 'el el-thumbs-up',
                    'fields'   => array(
						array(
                            'title'     => __( 'Testimonial Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Testimonial section.', 'ta-meghna' ),
                            'id'        => 'disable_testimonial_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Testimonial Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Testimonial section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_testimonial',
                            'default'   => 'testimonial',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Testimonial Section Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Testimonial section.', 'ta-meghna' ),
                            'id'        => 'title_testimonial',
                            'default'   => 'What People Say About Us',
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Background Image', 'ta-meghna' ),
                            'subtitle'  => __( 'Use this field to upload your background image for Testimonial section.', 'ta-meghna' ),
                            'id'        => 'testimonial_bg',
                            'default'   => '',
                            'type'      => 'media',
                            'url'       => true,
                        ),

						array(
							'id'          => 'testimonial_slides',
							'type'        => 'slides',
							'title'       => __( 'Testimonial Box', 'ta-meghna' ),
							'subtitle'    => __( 'Unlimited Testimonial Box with drag and drop sortings.', 'ta-meghna' ),
							'show'        => array(
								'title'        => true,
								'subtitle'     => true,
								'description'  => true,
								'image_upload' => true,
								'url'          => false,
								'turl'         => true,
								'furl'         => true,
								'lurl'         => true,
							),
							'placeholder' => array(
								'title'        => __( 'This is a name.', 'ta-meghna' ),
								'subtitle'     => __( 'This is a role.', 'ta-meghna' ),
								'description'  => __( 'Description here.', 'ta-meghna' ),
								'furl'         => __( 'Give me a Facebook link.', 'ta-meghna' ),
								'turl'         => __( 'Give me a Twitter link.', 'ta-meghna' ),
								'lurl'         => __( 'Give me a LinkedIn link.', 'ta-meghna' ),
							),
						),
                    ),
                );

				// Blog Settings
                $this->sections[] = array(
                    'title'    => __( 'Blog', 'ta-meghna' ),
                    'heading'  => __( 'Blog', 'ta-meghna' ),
                    'desc'     => __( 'Here you can set <strong>Blog</strong> section.', 'ta-meghna' ),
                    'icon'     => 'el el-pencil',
                    'fields'   => array(
						array(
                            'title'     => __( 'Blog Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Blog section.', 'ta-meghna' ),
                            'id'        => 'disable_blog_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Blog Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Blog section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_blog',
                            'default'   => 'blog',
                            'type'      => 'text',
						),

						array(
                            'title'     => __( 'Blog Section Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Blog section.', 'ta-meghna' ),
                            'id'        => 'title_blog',
                            'default'   => 'Latest <span class="color">Posts</span>',
                            'type'      => 'text',
						),

						array(
                            'title'     => __( 'Read More Button', 'ta-meghna' ),
                            'subtitle'  => __( 'Add text for Read More button.', 'ta-meghna' ),
                            'id'        => 'btn_read_more',
                            'default'   => 'Read More',
                            'type'      => 'text',
						),

						array(
                            'title'     => __( 'View All Posts Button', 'ta-meghna' ),
                            'subtitle'  => __( 'Add text for View All Posts button.', 'ta-meghna' ),
                            'id'        => 'btn_view_all',
                            'default'   => 'View All Posts',
                            'type'      => 'text',
						),

						array(
                            'title'     => __( 'Blog Page Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Blog page.', 'ta-meghna' ),
                            'id'        => 'title_blog_page',
                            'default'   => 'Welcome to Our Blog',
                            'type'      => 'text',
						),

						array(
                            'title'     => __( 'Blog Page Header Icon', 'ta-meghna' ),
                            'subtitle'  => __( 'Set icon for Blog page header. You can get Font Awesome Icon <a href="http://fontawesome.io/icons/" target="_blank">here</a>. e.g. folder-open.', 'ta-meghna' ),
                            'id'        => 'blog_page_icon',
                            'default'   => 'fa-book',
                            'type'      => 'text',
						),
                    ),
                );

				// Contact Us Settings
                $this->sections[] = array(
                    'title'    => __( 'Contact Us', 'ta-meghna' ),
                    'heading'  => __( 'Contact Us', 'ta-meghna' ),
                    'desc'     => __( 'Here you can set <strong>Contact Us</strong> section.', 'ta-meghna' ),
                    'icon'     => 'el el-envelope',
                    'fields'   => array(
						array(
                            'title'     => __( 'Contact Us Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Contact Us section.', 'ta-meghna' ),
                            'id'        => 'disable_contact_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Contact Us Section ID', 'ta-meghna' ),
                            'subtitle'  => __( 'Set id for Contact Us section for one page scrolling.', 'ta-meghna' ),
                            'id'        => 'id_contact',
                            'default'   => 'contact-us',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Contact Us Section Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Contact Us section.', 'ta-meghna' ),
                            'id'        => 'title_contact',
                            'default'   => 'Get In <span class="color">Touch</span>',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Contact Details Title', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own title for Contact Details module.', 'ta-meghna' ),
                            'id'        => 'contact_title',
                            'default'   => 'Contact Details',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Contact Details Description', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own description for Contact Details module.', 'ta-meghna' ),
                            'id'        => 'contact_description',
                            'default'   => '',
                            'type'      => 'textarea',
                        ),

						array( 
                            'title'     => __( 'Contact Email', 'ta-pluton' ),
                            'subtitle'  => __( 'Set your email address. This is where the contact form will send a message to.', 'ta-meghna' ),
                            'id'        => 'contact_email',
                            'default'   => 'yourname@yourdomain.com',
							'validate'  => 'email',
							'msg'       => __( 'Not a valid email address.', 'ta-meghna' ),
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Dislplay Contact Email', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display contact email.', 'ta-meghna' ),
                            'id'        => 'disable_contact_email',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),

						array( 
                            'title'     => __( 'Set Your Address', 'ta-meghna' ),
                            'subtitle'  => __( 'Set your address here.', 'ta-meghna' ),
                            'id'        => 'contact_address',
                            'default'   => '1600 Amphitheatre Parkway Mountain View, CA 94043',
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Set Your Phone Number', 'ta-meghna' ),
                            'subtitle'  => __( 'Set your phone number here.', 'ta-meghna' ),
                            'id'        => 'contact_phone',
                            'default'   => '+01 234 567 890',
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Set Your Fax Number', 'ta-meghna' ),
                            'subtitle'  => __( 'Set your fax number here.', 'ta-meghna' ),
                            'id'        => 'contact_fax',
                            'default'   => '+01 234 567 890',
                            'type'      => 'text',
                        ),

						array(
                            'title'     => __( 'Google Map Module', 'ta-meghna' ),
                            'subtitle'  => __( 'Select to enable/disable display Google Map section.', 'ta-meghna' ),
                            'id'        => 'disable_gogole_map_module',
                            'default'   => true,
                            'on'        => __( 'Enable', 'ta-meghna' ),
                            'off'       => __( 'Disable', 'ta-meghna' ),
                            'type'      => 'switch',
						),
						
						array( 
                            'title'     => __( 'Set Your Latitude on Google Map', 'ta-meghna' ),
                            'subtitle'  => __( 'To set location you will need to find Latitude and Longitude numbers, you can find in <a href="http://www.latlong.net/" target="_blank">this site</a>.', 'ta-pluton' ),
                            'id'        => 'google_map_lat',
                            'default'   => '37.42225',
							'msg'       => __( 'Not a valid numeric', 'ta-meghna' ),
							'validate'  => 'numeric',
                            'type'      => 'text',
                        ),

						array( 
                            'title'     => __( 'Set Your Longitude on Google Map', 'ta-meghna' ),
                            'subtitle'  => __( 'To set location you will need to find Latitude and Longitude numbers, you can find in <a href="http://www.latlong.net/" target="_blank">this site</a>.', 'ta-pluton' ),
                            'id'        => 'google_map_lon',
                            'default'   => '-122.08322',
							'msg'       => __( 'Not a valid numeric', 'ta-meghna' ),
							'validate'  => 'numeric',
                            'type'      => 'text',
                        ),
                    ),
                );

				//Social Settings
                $this->sections[] = array(
					'title'         => __( 'Social Profiles', 'ta-meghna' ),
                    'heading'       => __( 'Social Profiles', 'ta-meghna' ),
                    'desc'          => __( 'Here you can set your social profiles.', 'ta-meghna' ),
                    'icon'          => 'el el-icon-group',
                    'fields'        => array(
                         array(
                            'title'     => __( 'Social Icons', 'ta-meghna' ),
                            'subtitle'  => __( 'Arrange your social icons. Add complete URLs to your social profiles.', 'ta-meghna' ),
                            'id'        => 'social_icons',
                            'type'      => 'sortable',
                            'options'   => $social_options,
                       ),
                   )
               );

				//Footer Settings
                $this->sections[] = array(
					'title'     => __( 'Footer', 'ta-meghna' ),
					'heading'   => __( 'Footer', 'ta-meghna' ),
                    'desc'      => __( 'Here you can set site copyright information.', 'ta-meghna' ),
                    'icon'      => 'el el-download-alt',
                    'fields'    => array(
                        array(
                            'title'     => __( 'Custom Copyright', 'ta-meghna' ),
                            'subtitle'  => __( 'Add your own custom text/html for copyright region. You are <strong style="color:red;">not allowed</strong> to Remove Back Link/Credit unless you <a href="http://themeart.co/support-themeart/" target="_blank">donated us</a>.', 'ta-meghna' ),
                            'id'        => 'custom_copyright',
                            'default'   => 'Copyright &copy; 2015 - <a href="http://themeart.co/free-theme/ta-meghna/" title="TA Meghna Free WordPress Theme" target="_blank">TA Meghna</a>. Design by <a href="http://themefisher.com/" target="_blank">ThemeFisher</a> and Developed by <a href="http://themeart.co/" title="Downlod Free Premium WordPress Themes" target="_blank">ThemeArt</a>.',
                            'type'      => 'editor',
                       ),
                   )
               );

			   //Custom CSS
                $this->sections[] = array(
                    'icon'      => 'el el-css',
                    'title'     => __( 'Custom CSS', 'ta-meghna' ),
                    'fields'    => array(
                         array(
                            'title'     => __( 'Custom CSS', 'ta-meghna' ),
                            'subtitle'  => __( 'Insert any custom CSS.', 'ta-meghna' ),
                            'id'        => 'custom_css',
                            'type'      => 'ace_editor',
                            'mode'      => 'css',
                            'theme'     => 'monokai',
                        ),
                    ),
                );

                $this->sections[] = array(
                    'title'  => __( 'Import / Export', 'ta-meghna' ),
                    'desc'   => __( 'Import and Export your theme settings from file, text or URL.', 'ta-meghna' ),
                    'icon'   => 'el el-refresh',
                    'fields' => array(
                        array(
                            'id'         => 'opt-import-export',
                            'type'       => 'import_export',
                            'full_width' => false,
						),
					),
				);

                $this->sections[] = array(
                    'type' => 'divide',
				);

                $this->sections[] = array(
                    'icon'   => 'el el-info-circle',
                    'title'  => __( 'Theme Information', 'ta-meghna' ),
                    'desc'   => __( '<p class="description">About TA Meghna</p>', 'ta-meghna' ),
                    'fields' => array(
                        array(
                            'id'      => 'opt-raw-info',
                            'type'    => 'raw',
                            'content' => $item_info,
                       )
                   ),
               );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'ta_option',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Panel', 'ta-meghna' ),
                    'page_title'           => __( 'Theme Panel', 'ta-meghna' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => '',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-admin-settings',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => true,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => true,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => '_options',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );

                // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
                $this->args['admin_bar_links'][] = array(
                    'id'    => 'redux-docs',
                    'href'   => 'http://themeart.co/document/ta-meghna-theme-documentation/',
                    'title' => __( 'Documentation', 'ta-meghna' ),
                );

                $this->args['admin_bar_links'][] = array(
                    //'id'    => 'redux-support',
                    'href'   => 'http://themeart.co/support/',
                    'title' => __( 'Support', 'ta-meghna' ),
                );

                // Panel Intro text -> before the form
                if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                    if ( ! empty( $this->args['global_variable'] ) ) {
                        $v = $this->args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $this->args['opt_name'] );
                    }
                    $this->args['intro_text'] = sprintf( __( '<p>You can start customizing your theme with the powerful option panel.</p>', 'ta-meghna' ), $v );
                } else {
                    $this->args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'ta-meghna' );
                }

                // Add content after the form.
                $this->args['footer_text'] = __( '<p>Thanks for using <a href="http://themeart.co/free-theme/ta-meghna/" target="_blank">TA Meghna</a>. This free WordPress theme is designed by <a href=
				"http://themeart.co/" target="_blank">ThemeArt</a>. Please feel free to leave us some feedback about your experience, so we can improve our themes for you.</p>', 'ta-meghna' );
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_ta_config();
    } else {
        echo "The class named Redux_Framework_ta_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;
