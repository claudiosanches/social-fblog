<?php
/**
 * Social FBlog.
 *
 * @package   Social_FBlog_Admin
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @link      https://github.com/claudiosmweb/social-fblog
 * @copyright 2013 Claudio Sanches
 */

/**
 * Social FBlog Admin class.
 *
 * @package Social_FBlog_Admin
 * @author  Claudio Sanches <contato@claudiosmweb.com>
 */
class Social_FBlog_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since 3.1.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 3.1.0
	 */
	private function __construct() {

		$this->main_plugin = Social_FBlog::get_instance();
		$this->plugin_slug = $this->main_plugin->get_plugin_slug();

		// Adds admin menu.
		add_action( 'admin_menu', array( $this, 'menu' ) );

		// Init plugin options form.
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 3.1.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Update plugin settings.
	 * Makes upgrades of legacy versions.
	 *
	 * @since 3.1.0
	 *
	 * @return void
	 */
	public function update() {
		if ( get_option( 'social_fblog_twitter_on' ) ) {

			$buttons = array(
				'twitter_active'   => ( 'true' == get_option( 'social_fblog_twitter_on' ) ) ? 1 : 0,
				'twitter_user'     => get_option( 'social_fblog_twitter' ),
				'google_active'    => ( 'true' == get_option( 'social_fblog_gplusone_on' ) ) ? 1 : 0,
				'facebook_active'  => ( 'true' == get_option( 'social_fblog_face_on' ) ) ? 1 : 0,
				'facebook_send'    => ( 'true' == get_option( 'social_fblog_face_share' ) ) ? 1 : 0,
				// 'linkedin_active'  => 0,
				// 'pinterest_active' => 0,
				// 'email_active'     => 0
			);

			switch ( get_option( 'social_fblog_local' ) ) {
				case 'post':
					$display_in = 1;
					break;
				case 'page':
					$display_in = 2;
					break;

				default:
					$display_in = 0;
					break;
			}

			switch ( get_option( 'social_fblog_opacity' ) ) {
				case 'inicial':
					$opacity = 1;
					break;
				case 'continua':
					$opacity = 2;
					break;

				default:
					$opacity = 0;
					break;
			}

			$settings = array(
				'display_in'        => $display_in,
				'horizontal_align'  => get_option( 'social_fblog_margin' ),
				'top_distance'      => get_option( 'social_fblog_top' ),
				'border_radius'     => ( 'true' == get_option( 'social_fblog_border' ) ) ? 1 : null,
				'effects'           => ( 'true' == get_option( 'social_fblog_effect' ) ) ? 0 : 1,
				'opacity'           => $opacity,
				'opacity_intensity' => get_option( 'social_fblog_opacity_valor' )
			);

			// Updates options
			update_option( 'socialfblog_buttons', $buttons );
			update_option( 'socialfblog_settings', $settings );

			// Removes old options.
			delete_option( 'social_fblog_twitter_on' );
			delete_option( 'social_fblog_twitter' );
			delete_option( 'social_fblog_gplusone_on' );
			delete_option( 'social_fblog_face_on' );
			delete_option( 'social_fblog_face_share' );
			delete_option( 'social_fblog_local' );
			delete_option( 'social_fblog_margin' );
			delete_option( 'social_fblog_top' );
			delete_option( 'social_fblog_border' );
			delete_option( 'social_fblog_effect' );
			delete_option( 'social_fblog_opacity' );
			delete_option( 'social_fblog_opacity_valor' );
			delete_option( 'social_fblog_fixed' );
			delete_option( 'social_fblog_fixed_position' );
			delete_option( 'social_fblog_fix_face' );
			delete_option( 'social_fblog_extra' );

		} else {
			// Install default options.
			$buttons = array();
			$settings = array();

			foreach ( $this->main_plugin->get_options() as $key => $value ) {
				if ( 'section' != $value['type'] ) {
					if ( 'socialfblog_buttons' == $value['menu'] )
						$buttons[ $key ] = $value['default'];
					else
						$settings[ $key ] = $value['default'];
				}
			}

			add_option( 'socialfblog_buttons', $buttons );
			add_option( 'socialfblog_settings', $settings );
		}
	}

	/**
	 * Add plugin settings menu.
	 *
	 * @since 3.1.0
	 *
	 * @return void
	 */
	public function menu() {
		add_options_page(
			__( 'Social FBlog', $this->plugin_slug ),
			__( 'Social FBlog', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Plugin settings page.
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */
	public function settings_page() {
		// Create tabs current class.
		$current_tab = '';
		if ( isset( $_GET['tab'] ) ) {
			$current_tab = $_GET['tab'];
		} else {
			$current_tab = 'buttons';
		}
		?>

		<div class="wrap">
			<h2 class="nav-tab-wrapper">
				<a href="admin.php?page=<?php echo $this->plugin_slug; ?>&amp;tab=buttons" class="nav-tab <?php echo $current_tab == 'buttons' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Buttons', $this->plugin_slug ); ?></a><a href="admin.php?page=<?php echo $this->plugin_slug; ?>&amp;tab=settings" class="nav-tab <?php echo $current_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', $this->plugin_slug ); ?></a>
			</h2>

			<form method="post" action="options.php">
				<?php
					if ( $current_tab == 'settings' ) {
						settings_fields( 'socialfblog_settings' );
						do_settings_sections( 'socialfblog_settings' );
					} else {
						settings_fields( 'socialfblog_buttons' );
						do_settings_sections( 'socialfblog_buttons' );
					}

					submit_button();
				?>
			</form>
		</div>

		<?php
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @since 3.1.0
	 *
	 * @return void
	 */
	public function plugin_settings() {
		$buttons = 'socialfblog_buttons';
		$settings = 'socialfblog_settings';

		// Create option in wp_options.
		if ( false == get_option( $settings ) ) {
			$this->update();
		}

		foreach ( $this->main_plugin->get_options() as $key => $value ) {

			switch ( $value['type'] ) {
				case 'section':
					add_settings_section(
						$key,
						$value['title'],
						'__return_false',
						$value['menu']
					);
					break;
				case 'text':
					add_settings_field(
						$key,
						$value['title'],
						array( $this, 'text_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu' => $value['menu'],
							'id' => $key,
							'class' => 'regular-text',
							'description' => isset( $value['description'] ) ? $value['description'] : ''
						)
					);
					break;
				case 'checkbox':
					add_settings_field(
						$key,
						$value['title'],
						array( $this, 'checkbox_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu' => $value['menu'],
							'id' => $key,
							'description' => isset( $value['description'] ) ? $value['description'] : ''
						)
					);
					break;
				case 'select':
					add_settings_field(
						$key,
						$value['title'],
						array( $this, 'select_element_callback' ),
						$value['menu'],
						$value['section'],
						array(
							'menu' => $value['menu'],
							'id' => $key,
							'description' => isset( $value['description'] ) ? $value['description'] : '',
							'options' => $value['options']
						)
					);
					break;

				default:
					break;
			}

		}

		// Register settings.
		register_setting( $buttons, $buttons, array( $this, 'validate_options' ) );
		register_setting( $settings, $settings, array( $this, 'validate_options' ) );
	}

	/**
	 * Text element fallback.
	 *
	 * @since 3.1.0
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Text field.
	 */
	public function text_element_callback( $args ) {
		$menu  = $args['menu'];
		$id    = $args['id'];
		$class = isset( $args['class'] ) ? $args['class'] : 'small-text';

		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '';
		}

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" />', $id, $menu, $current, $class );

		// Displays option description.
		if ( isset( $args['description'] ) ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Checkbox field fallback.
	 *
	 * @since 3.1.0
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Checkbox field.
	 */
	public function checkbox_element_callback( $args ) {
		$menu = $args['menu'];
		$id   = $args['id'];

		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '';
		}

		$html = sprintf( '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1"%3$s />', $id, $menu, checked( 1, $current, false ) );

		$html .= sprintf( '<label for="%s"> %s</label><br />', $id, __( 'Activate/Deactivate', $this->plugin_slug ) );

		// Displays option description.
		if ( isset( $args['description'] ) ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Select element fallback.
	 *
	 * @since 3.1.0
	 *
	 * @param  array $args Field arguments.
	 *
	 * @return string      Select field.
	 */
	function select_element_callback( $args ) {
		$menu = $args['menu'];
		$id   = $args['id'];

		$options = get_option( $menu );

		if ( isset( $options[ $id ] ) ) {
			$current = $options[ $id ];
		} else {
			$current = isset( $args['default'] ) ? $args['default'] : '#ffffff';
		}

		$html = sprintf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $menu );
		$key = 0;
		foreach ( $args['options'] as $label ) {
			$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $label );

			$key++;
		}
		$html .= '</select>';

		// Displays option description.
		if ( isset( $args['description'] ) ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Valid options.
	 *
	 * @since 3.1.0
	 *
	 * @param  array $input options to valid.
	 *
	 * @return array        validated options.
	 */
	public function validate_options( $input ) {
		$output = array();

		foreach ( $input as $key => $value ) {
			if ( isset( $input[ $key ] ) ) {
				$output[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}

		return $output;
	}
}
