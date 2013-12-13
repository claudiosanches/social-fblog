<?php
/**
 * Social FBlog.
 *
 * @package   Social_FBlog
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @link      https://github.com/claudiosmweb/social-fblog
 * @copyright 2013 Claudio Sanches
 */

/**
 * Social FBlog class.
 *
 * @package Social_FBlog_Admin
 * @author  Claudio Sanches <contato@claudiosmweb.com>
 */
class Social_FBlog {

	/**
	 * Plugin version.
	 *
	 * @since 3.1.0
	 *
	 * @var string
	 */
	const VERSION = '3.1.0';

	/**
	 * Plugin slug for text domain.
	 *
	 * @since 3.1.0
	 *
	 * @var string
	 */
	protected $plugin_slug = 'social-fblog';

	/**
	 * Instance of this class.
	 *
	 * @since 3.1.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 *
	 * @since 3.1.0
	 */
	private function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Front-end scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'front_end_scripts' ) );

		// Adds footer js.
		add_filter( 'wp_footer', array( $this, 'footer_js' ), 999 );

		// Display buttons.
		add_filter( 'the_content', array( $this, 'display_buttons' ), 999 );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since 3.1.0
	 *
	 * @return Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
	 * Gets the options.
	 *
	 * @since 3.1.0
	 *
	 * @return array Plugin default options.
	 */
	public function get_options() {

		$settings = array(
			'twitter' => array(
				'title' => __( 'Twitter', $this->plugin_slug ),
				'type'  => 'section',
				'menu'  => 'socialfblog_buttons'
			),
			'twitter_active' => array(
				'title'   => __( 'Display Twitter button', $this->plugin_slug ),
				'default' => 1,
				'type'    => 'checkbox',
				'section' => 'twitter',
				'menu'    => 'socialfblog_buttons'
			),
			'twitter_user' => array(
				'title'       => __( 'Twitter username', $this->plugin_slug ),
				'default'     => 'ferramentasblog',
				'type'        => 'text',
				'description' => __( 'Just insert the username. Example: ferramentasblog', $this->plugin_slug ),
				'section'     => 'twitter',
				'menu'        => 'socialfblog_buttons'
			),
			'google' => array(
				'title' => __( 'Google Plus', $this->plugin_slug ),
				'type'  => 'section',
				'menu'  => 'socialfblog_buttons'
			),
			'google_active' => array(
				'title'   => __( 'Display Google Plus button', $this->plugin_slug ),
				'default' => 1,
				'type'    => 'checkbox',
				'section' => 'google',
				'menu'    => 'socialfblog_buttons'
			),
			'facebook' => array(
				'title' => __( 'Facebook', $this->plugin_slug ),
				'type'  => 'section',
				'menu'  => 'socialfblog_buttons'
			),
			'facebook_active' => array(
				'title'   => __( 'Display Facebook button', $this->plugin_slug ),
				'default' => 1,
				'type'    => 'checkbox',
				'section' => 'facebook',
				'menu'    => 'socialfblog_buttons'
			),
			'facebook_send' => array(
				'title'   => __( 'Display Facebook Send button', $this->plugin_slug ),
				'default' => 1,
				'type'    => 'checkbox',
				'section' => 'facebook',
				'menu'    => 'socialfblog_buttons'
			),
			'linkedin' => array(
				'title' => __( 'LinkedIn', $this->plugin_slug ),
				'type'  => 'section',
				'menu'  => 'socialfblog_buttons'
			),
			'linkedin_active' => array(
				'title'   => __( 'Display LinkedIn button', $this->plugin_slug ),
				'default' => null,
				'type'    => 'checkbox',
				'section' => 'linkedin',
				'menu'    => 'socialfblog_buttons'
			),
			'pinterest' => array(
				'title' => __( 'Pinterest', $this->plugin_slug ),
				'type'  => 'section',
				'menu'  => 'socialfblog_buttons'
			),
			'pinterest_active' => array(
				'title'   => __( 'Display Pinterest button', $this->plugin_slug ),
				'default' => null,
				'type'    => 'checkbox',
				'section' => 'pinterest',
				'menu'    => 'socialfblog_buttons'
			),
			'email' => array(
				'title' => __( 'Email', $this->plugin_slug ),
				'type'  => 'section',
				'menu'  => 'socialfblog_buttons'
			),
			'email_active' => array(
				'title'   => __( 'Display Email button', $this->plugin_slug ),
				'default' => null,
				'type'    => 'checkbox',
				'section' => 'email',
				'menu'    => 'socialfblog_buttons'
			),
			'settings' => array(
				'title' => __( 'Settings', $this->plugin_slug ),
				'type'  => 'section',
				'menu'  => 'socialfblog_settings'
			),
			'display_in' => array(
				'title'   => __( 'Display Buttons in', $this->plugin_slug ),
				'default' => 1,
				'type'    => 'select',
				'options' => array(
					__( 'Posts and Pages', $this->plugin_slug ),
					__( 'Only in Posts', $this->plugin_slug ),
					__( 'Only in Pages', $this->plugin_slug ),
				),
				'section' => 'settings',
				'menu'    => 'socialfblog_settings'
			),
			'horizontal_align' => array(
				'title'       => __( 'Horizontal Alignment', $this->plugin_slug ),
				'default'     => -80,
				'type'        => 'text',
				'description' => __( 'This option is used to control the distance of the sharing buttons on the content. Use only integer numbers.', $this->plugin_slug ),
				'section'     => 'settings',
				'menu'        => 'socialfblog_settings'
			),
			'top_distance' => array(
				'title'       => __( 'Initial Distance', $this->plugin_slug ),
				'default'     => 360,
				'type'        => 'text',
				'description' => __( 'This option controls the distance that the sharing buttons will appear to load page. Use only integer numbers.', $this->plugin_slug ),
				'section'     => 'settings',
				'menu'        => 'socialfblog_settings'
			),
			'border_radius' => array(
				'title'       => __( 'Add Rounded Edges', $this->plugin_slug ),
				'default'     => null,
				'type'        => 'checkbox',
				'description' => __( 'Does not work in old browsers.', $this->plugin_slug ),
				'section'     => 'settings',
				'menu'        => 'socialfblog_settings'
			),
			'effects' => array(
				'title'   => __( 'Motion Effects', $this->plugin_slug ),
				'default' => 0,
				'type'    => 'select',
				'options' => array(
					__( 'Elastic', $this->plugin_slug ),
					__( 'Static', $this->plugin_slug )
				),
				'section' => 'settings',
				'menu'    => 'socialfblog_settings'
			),
			'opacity' => array(
				'title'       => __( 'Opacity Effects', $this->plugin_slug ),
				'default'     => 0,
				'type'        => 'select',
				'description' => __( 'Does not work in versions 6, 7 ​​and 8 of Internet Explorer.', $this->plugin_slug ),
				'options'     => array(
					__( 'No Effect (default)', $this->plugin_slug ),
					__( 'Initial Opacity', $this->plugin_slug ),
					__( 'Continuous Opacity', $this->plugin_slug )
				),
				'section' => 'settings',
				'menu'    => 'socialfblog_settings'
			),
			'opacity_intensity' => array(
				'title'       => __( 'Opacity Intensity', $this->plugin_slug ),
				'default'     => '0.7',
				'type'        => 'text',
				'description' => __( 'Enter values ​​between "0.1" to "1".<br />This option works only if it has been activated the "Opacity Effects" as "Initial Opacity" or "Continuous Opacity"', $this->plugin_slug ),
				'section'     => 'settings',
				'menu'        => 'socialfblog_settings'
			)
		);

		return $settings;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 3.1.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Installs default settings on plugin activation.
	 *
	 * @since 3.1.0
	 *
	 * @return void
	 */
	public static function install() {
		$instance = new self;
		$buttons = array();
		$settings = array();

		foreach ( $instance->get_options() as $key => $value ) {
			if ( 'section' != $value['type'] ) {
				if ( 'socialfblog_buttons' == $value['menu'] ) {
					$buttons[ $key ] = $value['default'];
				} else {
					$settings[ $key ] = $value['default'];
				}
			}
		}

		add_option( 'socialfblog_buttons', $buttons );
		add_option( 'socialfblog_settings', $settings );
	}

	/**
	 * Register front-end scripts.
	 *
	 * @since 3.1.0
	 *
	 * @return void
	 */
	public function front_end_scripts() {
		if ( is_single() || is_page() ) {
			$settings = get_option( 'socialfblog_settings' );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( $this->plugin_slug, plugins_url( 'assets/css/' . $this->plugin_slug . '.css', dirname( __FILE__ ) ), array(), null );
			wp_enqueue_script( $this->plugin_slug, plugins_url( 'assets/js/' . $this->plugin_slug . '.min.js', dirname( __FILE__ ) ), array( 'jquery' ), null, true );
			wp_localize_script(
				$this->plugin_slug,
				'social_fblog_params',
				array(
					'effect'            => $settings['effects'],
					'opacity'           => $settings['opacity'],
					'top_distance'      => $settings['top_distance'],
					'opacity_intensity' => $settings['opacity_intensity']
				)
			);
		}
	}

	/**
	 * Twitter button.
	 *
	 * @since 3.1.0
	 *
	 * @param  string $title    Post or page title.
	 * @param  string $url      Post or page url.
	 * @param  string $username Twitter username.
	 *
	 * @return string           Twitter button html.
	 */
	protected function button_twitter( $title, $url, $username ) {
		$button = '<div id="socialfblog-twitter">';
		$button .= sprintf( '<a href="https://twitter.com/share" class="twitter-share-button" data-url="%s" data-text="%s" data-via="%s" data-lang="en" data-count="vertical">Tweet</a>', $url, $title, $username );
		$button .= '</div>';

		return $button;
	}

	/**
	 * Google Plus button.
	 *
	 * @since 3.1.0
	 *
	 * @return string Google Plus button html.
	 */
	protected function button_googleplus() {
		$button = '<div id="socialfblog-googleplus">';
		$button .= '<div class="g-plusone" data-size="tall"></div>';
		$button .= '</div>';

		return $button;
	}

	/**
	 * Facebook button.
	 *
	 * @since 3.1.0
	 *
	 * @param  string  $url  Post or page title.
	 * @param  boolean $send Display send button.
	 *
	 * @return string        Facebook button html.
	 */
	protected function button_facebook( $url, $send = false ) {
		$send = ( true == $send ) ? 'true' : 'false';

		$button = '<div id="socialfblog-facebook">';
		$button .= sprintf( '<div class="fb-like" data-href="%s" data-send="%s" data-layout="box_count" data-width="54" data-show-faces="false" data-font="arial"></div>', $url, $send );
		$button .= '</div>';

		return $button;
	}

	/**
	 * LinkedIn button.
	 *
	 * @since 3.1.0
	 *
	 * @param  string  $url  Post or page title.
	 *
	 * @return string        LinkedIn button html.
	 */
	protected function button_linkedin( $url ) {
		$button = '<div id="socialfblog-linkedin">';
		$button .= sprintf( '<script type="IN/Share" data-url="%s" data-counter="top"></script>', $url );
		$button .= '</div>';

		return $button;
	}

	/**
	 * Pinterest button.
	 *
	 * @since 3.1.0
	 *
	 * @param  string $title  Post or page title.
	 * @param  string $url    Post or page url.
	 * @param  string $id     Post or page id.
	 *
	 * @return string         Pinterest button html.
	 */
	protected function button_pinterest( $title, $url, $id ) {
		$button = '<div id="socialfblog-pinterest">';
		$button .= sprintf( '<a href="http://pinterest.com/pin/create/button/?url=%s&amp;media=%s&amp;description=%s" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>', urlencode( $url ), urlencode( wp_get_attachment_url( get_post_thumbnail_id( $id ), 'full' ) ), urlencode( $title ) );
		$button .= '</div>';

		return $button;
	}

	/**
	 * Email button.
	 *
	 * @since 3.1.0
	 *
	 * @param  string $title  Post or page title.
	 * @param  string $url    Post or page url.
	 *
	 * @return string         Email button html.
	 */
	protected function button_email( $title, $url ) {
		$button = '<div id="socialfblog-email">';
		$button .= sprintf( '<a href="mailto:?subject=%1$s&amp;body=%1$s:%%20%2$s" title="%3$s">%4$s</a>', rawurlencode( $title ), urlencode( $url ), __( 'Share by Email', $this->plugin_slug ), __( 'Email', $this->plugin_slug ) );
		$button .= '</div>';

		return $button;
	}

	/**
	 * Display jQuery validate options in footer.
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */
	public function footer_js() {
		if ( is_single() || is_page() ) {
			$settings = get_option( 'socialfblog_settings' );
			$buttons = get_option( 'socialfblog_buttons' );
			$scripts = '';

			$twitter = '<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>' . "\n";

			$google = sprintf( '<script type="text/javascript">window.___gcfg = {lang: "%s"};(function() {var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);})();</script>', __( 'en-US', $this->plugin_slug ) ) . "\n"; // pt-BR

			$facebook = sprintf( '<div id="fb-root"></div><script type="text/javascript">(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) {return;}js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/%s/all.js#xfbml=1&appId=228619377180035";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>', __( 'en_US', $this->plugin_slug ) ) . "\n"; // pt_BR

			$linkedin = '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';

			$pinterest = '<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>';

			$scripts .= isset( $buttons['twitter_active'] ) ? $twitter : '';
			$scripts .= isset( $buttons['google_active'] ) ? $google : '';
			$scripts .= isset( $buttons['facebook_active'] ) ? $facebook : '';
			$scripts .= isset( $buttons['linkedin_active'] ) ? $linkedin : '';
			$scripts .= isset( $buttons['pinterest_active'] ) ? $pinterest : '';

			$scripts = apply_filters( 'socialfblog_scripts', $scripts );

			switch ( $settings['display_in'] ) {
				case '1':
					if ( is_single() ) echo $scripts;
					break;
				case '2':
					if ( is_page() ) echo $scripts;
					break;

				default:
					echo $scripts;
					break;
			}
		}
	}

	/**
	 * Display buttons in the_content().
	 *
	 * @since 3.1.0
	 *
	 * @param  string $content Post or page content.
	 *
	 * @return string          Content with socialfblog buttons.
	 */
	public function display_buttons( $content ) {

		if ( is_single() || is_page() ) {
			global $post;

			$title = $post->post_title;
			$id = $post->ID;
			$url = get_permalink( $id );

			$settings = get_option( 'socialfblog_settings' );
			$buttons = get_option( 'socialfblog_buttons' );

			$facebook_send = isset( $buttons['facebook_send'] ) ? true : false;

			// Display buttons.
			$display = isset( $buttons['twitter_active'] ) ? $this->button_twitter( $title, $url, $buttons['twitter_user'] ) : '';
			$display .= isset( $buttons['google_active'] ) ? $this->button_googleplus() : '';
			$display .= isset( $buttons['facebook_active'] ) ? $this->button_facebook( $url, $facebook_send ) : '';
			$display .= isset( $buttons['linkedin_active'] ) ? $this->button_linkedin( $url ) : '';
			$display .= isset( $buttons['pinterest_active'] ) ? $this->button_pinterest( $title, $url, $id ) : '';
			$display .= isset( $buttons['email_active'] ) ? $this->button_email( $title, $url ) : '';

			$display = apply_filters( 'socialfblog_buttons', $display );

			// Styles.
			$border_radius = isset( $settings['border_radius'] ) ? 'rounded' : '';
			$opacity = ( 0 != $settings['opacity'] ) ? ' opacity: ' . $settings['opacity_intensity'] . ';' : '';

			// Plugin HTML.
			$html = '<div id="socialfblog">';
				$html .= sprintf( '<div id="socialfblog-box" class="%s" style="margin-left: %spx; top: %spx;%s">', $border_radius, $settings['horizontal_align'], $settings['top_distance'], $opacity );
					$html .= $display;
				$html .= '</div>';
			$html .= '</div>' . "\n";

			switch ( $settings['display_in'] ) {
				case '1':
					if ( is_single() )
						return $content . $html;
					break;
				case '2':
					if ( is_page() )
						return $content . $html;
					break;

				default:
					return $content . $html;
					break;
			}

		}

		return $content;
	}
}
