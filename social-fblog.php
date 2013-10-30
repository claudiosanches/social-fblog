<?php
/**
 * Plugin Name: Social FBlog
 * Plugin URI: https://github.com/claudiosmweb/social-fblog
 * Description: Inserts a floating box next to your blog posts to share your content on Twitter, Facebook and Google Plus and others.
 * Author: claudiosanches
 * Author URI: http://claudiosmweb.com/
 * Version: 3.1.0
 * License: GPLv2 or later
 * Text Domain: social-fblog
 * Domain Path: /languages/
 */

class Social_FBlog {

    /**
     * Class construct.
     */
    public function __construct() {

        // Load textdomain.
        add_action( 'plugins_loaded', array( &$this, 'languages' ), 0 );

        // Adds admin menu.
        add_action( 'admin_menu', array( &$this, 'menu' ) );

        // Init plugin options form.
        add_action( 'admin_init', array( &$this, 'plugin_settings' ) );

        // Front-end scripts.
        add_action( 'wp_enqueue_scripts', array( &$this, 'front_end_scripts' ) );

        // Adds footer js.
        add_filter( 'wp_footer', array( &$this, 'footer_js' ), 999 );

        // Display buttons.
        add_filter( 'the_content', array( &$this, 'display_buttons' ), 999 );

        // Install default settings.
        register_activation_hook( __FILE__, array( &$this, 'install' ) );
    }

    /**
     * Load translations.
     */
    public function languages() {
        load_plugin_textdomain( 'social-fblog', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Sets default settings
     *
     * @return array Plugin default settings.
     */
    protected function default_settings() {

        $settings = array(
            'twitter' => array(
                'title' => __( 'Twitter', 'social-fblog' ),
                'type' => 'section',
                'menu' => 'socialfblog_buttons'
            ),
            'twitter_active' => array(
                'title' => __( 'Display Twitter button', 'social-fblog' ),
                'default' => 1,
                'type' => 'checkbox',
                'section' => 'twitter',
                'menu' => 'socialfblog_buttons'
            ),
            'twitter_user' => array(
                'title' => __( 'Twitter username', 'social-fblog' ),
                'default' => 'ferramentasblog',
                'type' => 'text',
                'description' => __( 'Just insert the username. Example: ferramentasblog', 'social-fblog' ),
                'section' => 'twitter',
                'menu' => 'socialfblog_buttons'
            ),
            'google' => array(
                'title' => __( 'Google Plus', 'social-fblog' ),
                'type' => 'section',
                'menu' => 'socialfblog_buttons'
            ),
            'google_active' => array(
                'title' => __( 'Display Google Plus button', 'social-fblog' ),
                'default' => 1,
                'type' => 'checkbox',
                'section' => 'google',
                'menu' => 'socialfblog_buttons'
            ),
            'facebook' => array(
                'title' => __( 'Facebook', 'social-fblog' ),
                'type' => 'section',
                'menu' => 'socialfblog_buttons'
            ),
            'facebook_active' => array(
                'title' => __( 'Display Facebook button', 'social-fblog' ),
                'default' => 1,
                'type' => 'checkbox',
                'section' => 'facebook',
                'menu' => 'socialfblog_buttons'
            ),
            'facebook_send' => array(
                'title' => __( 'Display Facebook Send button', 'social-fblog' ),
                'default' => 1,
                'type' => 'checkbox',
                'section' => 'facebook',
                'menu' => 'socialfblog_buttons'
            ),
            'linkedin' => array(
                'title' => __( 'LinkedIn', 'social-fblog' ),
                'type' => 'section',
                'menu' => 'socialfblog_buttons'
            ),
            'linkedin_active' => array(
                'title' => __( 'Display LinkedIn button', 'social-fblog' ),
                'default' => null,
                'type' => 'checkbox',
                'section' => 'linkedin',
                'menu' => 'socialfblog_buttons'
            ),
            'pinterest' => array(
                'title' => __( 'Pinterest', 'social-fblog' ),
                'type' => 'section',
                'menu' => 'socialfblog_buttons'
            ),
            'pinterest_active' => array(
                'title' => __( 'Display Pinterest button', 'social-fblog' ),
                'default' => null,
                'type' => 'checkbox',
                'section' => 'pinterest',
                'menu' => 'socialfblog_buttons'
            ),
            'email' => array(
                'title' => __( 'Email', 'social-fblog' ),
                'type' => 'section',
                'menu' => 'socialfblog_buttons'
            ),
            'email_active' => array(
                'title' => __( 'Display Email button', 'social-fblog' ),
                'default' => null,
                'type' => 'checkbox',
                'section' => 'email',
                'menu' => 'socialfblog_buttons'
            ),
            'settings' => array(
                'title' => __( 'Settings', 'social-fblog' ),
                'type' => 'section',
                'menu' => 'socialfblog_settings'
            ),
            'display_in' => array(
                'title' => __( 'Display Buttons in', 'social-fblog' ),
                'default' => 1,
                'type' => 'select',
                'options' => array(
                    __( 'Posts and Pages', 'social-fblog' ),
                    __( 'Only in Posts', 'social-fblog' ),
                    __( 'Only in Pages', 'social-fblog' ),
                ),
                'section' => 'settings',
                'menu' => 'socialfblog_settings'
            ),
            'horizontal_align' => array(
                'title' => __( 'Horizontal Alignment', 'social-fblog' ),
                'default' => -80,
                'type' => 'text',
                'description' => __( 'This option is used to control the distance of the sharing buttons on the content. Use only integer numbers.', 'social-fblog' ),
                'section' => 'settings',
                'menu' => 'socialfblog_settings'
            ),
            'top_distance' => array(
                'title' => __( 'Initial Distance', 'social-fblog' ),
                'default' => 360,
                'type' => 'text',
                'description' => __( 'This option controls the distance that the sharing buttons will appear to load page. Use only integer numbers.', 'social-fblog' ),
                'section' => 'settings',
                'menu' => 'socialfblog_settings'
            ),
            'border_radius' => array(
                'title' => __( 'Add Rounded Edges', 'social-fblog' ),
                'default' => null,
                'type' => 'checkbox',
                'description' => __( 'Does not work in old browsers.', 'social-fblog' ),
                'section' => 'settings',
                'menu' => 'socialfblog_settings'
            ),
            'effects' => array(
                'title' => __( 'Motion Effects', 'social-fblog' ),
                'default' => 0,
                'type' => 'select',
                'options' => array(
                    __( 'Elastic', 'social-fblog' ),
                    __( 'Static', 'social-fblog' )
                ),
                'section' => 'settings',
                'menu' => 'socialfblog_settings'
            ),
            'opacity' => array(
                'title' => __( 'Opacity Effects', 'social-fblog' ),
                'default' => 0,
                'type' => 'select',
                'description' => __( 'Does not work in versions 6, 7 ​​and 8 of Internet Explorer.', 'social-fblog' ),
                'options' => array(
                    __( 'No Effect (default)', 'social-fblog' ),
                    __( 'Initial Opacity', 'social-fblog' ),
                    __( 'Continuous Opacity', 'social-fblog' )
                ),
                'section' => 'settings',
                'menu' => 'socialfblog_settings'
            ),
            'opacity_intensity' => array(
                'title' => __( 'Opacity Intensity', 'social-fblog' ),
                'default' => '0.7',
                'type' => 'text',
                'description' => __( 'Enter values ​​between "0.1" to "1".<br />This option works only if it has been activated the "Opacity Effects" as "Initial Opacity" or "Continuous Opacity"', 'social-fblog' ),
                'section' => 'settings',
                'menu' => 'socialfblog_settings'
            )
        );

        return $settings;
    }

    /**
     * Installs default settings on plugin activation.
     */
    public function install() {
        $buttons = array();
        $settings = array();

        foreach ( $this->default_settings() as $key => $value ) {
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

    /**
     * Update plugin settings.
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
            $this->install();
        }
    }

    /**
     * Add plugin settings menu.
     */
    public function menu() {
        add_options_page(
            __( 'Social FBlog', 'social-fblog' ),
            __( 'Social FBlog', 'social-fblog' ),
            'manage_options',
            'social-fblog',
            array( &$this, 'settings_page' )
        );
    }

    /**
     * Plugin settings page.
     */
    public function settings_page() {
        // Create tabs current class.
        $current_tab = '';
        if ( isset($_GET['tab'] ) )
            $current_tab = $_GET['tab'];
        else
            $current_tab = 'buttons';
        ?>

        <div class="wrap">
            <?php screen_icon( 'options-general' ); ?>
            <h2 class="nav-tab-wrapper">
                <a href="admin.php?page=social-fblog&amp;tab=buttons" class="nav-tab <?php echo $current_tab == 'buttons' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Buttons', 'social-fblog' ); ?></a><a href="admin.php?page=social-fblog&amp;tab=settings" class="nav-tab <?php echo $current_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'social-fblog' ); ?></a>
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
     */
    public function plugin_settings() {
        $buttons = 'socialfblog_buttons';
        $settings = 'socialfblog_settings';

        // Create option in wp_options.
        if ( false == get_option( $settings ) )
            $this->update();

        foreach ( $this->default_settings() as $key => $value ) {

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
                        array( &$this , 'text_element_callback' ),
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
                        array( &$this , 'checkbox_element_callback' ),
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
                        array( &$this , 'select_element_callback' ),
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
        register_setting( $buttons, $buttons, array( &$this, 'validate_options' ) );
        register_setting( $settings, $settings, array( &$this, 'validate_options' ) );
    }

    /**
     * Text element fallback.
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

        if ( isset( $options[ $id ] ) )
            $current = $options[ $id ];
        else
            $current = isset( $args['default'] ) ? $args['default'] : '';

        $html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" />', $id, $menu, $current, $class );

        // Displays option description.
        if ( isset( $args['description'] ) )
            $html .= sprintf( '<p class="description">%s</p>', $args['description'] );

        echo $html;
    }

    /**
     * Checkbox field fallback.
     *
     * @param  array $args Field arguments.
     *
     * @return string      Checkbox field.
     */
    public function checkbox_element_callback( $args ) {
        $menu = $args['menu'];
        $id   = $args['id'];

        $options = get_option( $menu );

        if ( isset( $options[ $id ] ) )
            $current = $options[ $id ];
        else
            $current = isset( $args['default'] ) ? $args['default'] : '';

        $html = sprintf( '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1"%3$s />', $id, $menu, checked( 1, $current, false ) );

        $html .= sprintf( '<label for="%s"> %s</label><br />', $id, __( 'Activate/Deactivate', 'social-fblog' ) );

        // Displays option description.
        if ( isset( $args['description'] ) )
            $html .= sprintf( '<p class="description">%s</p>', $args['description'] );

        echo $html;
    }

    /**
     * Select element fallback.
     *
     * @param  array $args Field arguments.
     *
     * @return string      Select field.
     */
    function select_element_callback( $args ) {
        $menu = $args['menu'];
        $id   = $args['id'];

        $options = get_option( $menu );

        if ( isset( $options[ $id ] ) )
            $current = $options[ $id ];
        else
            $current = isset( $args['default'] ) ? $args['default'] : '#ffffff';

        $html = sprintf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $menu );
        $key = 0;
        foreach ( $args['options'] as $label ) {
            $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $label );

            $key++;
        }
        $html .= '</select>';

        // Displays option description.
        if ( isset( $args['description'] ) )
            $html .= sprintf( '<p class="description">%s</p>', $args['description'] );

        echo $html;
    }

    /**
     * Valid options.
     *
     * @param  array $input options to valid.
     *
     * @return array        validated options.
     */
    public function validate_options( $input ) {
        $output = array();

        foreach ( $input as $key => $value ) {
            if ( isset( $input[ $key ] ) )
                $output[ $key ] = sanitize_text_field( $input[ $key ] );
        }

        return $output;
    }

    /**
     * Twitter button.
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
     * @param  string $title  Post or page title.
     * @param  string $url    Post or page url.
     *
     * @return string         Email button html.
     */
    protected function button_email( $title, $url ) {
        $button = '<div id="socialfblog-email">';
        $button .= sprintf( '<a href="mailto:?subject=%1$s&amp;body=%1$s:%%20%2$s" title="%3$s">%4$s</a>', rawurlencode( $title ), urlencode( $url ), __( 'Share by Email', 'social-fblog' ), __( 'Email', 'social-fblog' ) );
        $button .= '</div>';

        return $button;
    }

    /**
     * Register front-end scripts.
     */
    public function front_end_scripts() {
        if ( is_single() || is_page() ) {
            $settings = get_option( 'socialfblog_settings' );

            wp_enqueue_script( 'jquery' );
            wp_enqueue_style( 'social-fblog', plugins_url( 'assets/css/social-fblog.css', __FILE__ ), array(), null );
        }
    }

    /**
     * Elastic Effect.
     *
     * @param  boolean $opacity   Add opacity.
     * @param  string  $intensity Control opacity intensity.
     *
     * @return string             jQuery with the effect.
     */
    protected function effect_elastic( $opacity = false, $intensity = '0.7' ) {

        $opacity_motion = ( true == $opacity ) ? '.css({opacity:"1"})' : '';
        $opacity_initial = ( true == $opacity ) ? '.css({opacity:"' . $intensity . '"})' : '';

        $script = '<script type="text/javascript">';
            $script .= 'jQuery(document).ready(function($){';
                $script .= 'var socialbox = $("#socialfblog-box");';
                $script .= 'var offset = socialbox.offset();';
                $script .= '$(window).scroll(function(){';
                    $script .= 'if ($(window).scrollTop() > offset.top) {';
                        $script .= 'socialbox.stop()' . $opacity_motion . '.animate({';
                            $script .= 'marginTop:$(window).scrollTop() - offset.top + 60';
                        $script .= '});';
                    $script .= '} else {';
                        $script .= 'socialbox.stop()' . $opacity_initial . '.animate({';
                            $script .= 'marginTop:0';
                        $script .= '});';
                    $script .= '}';
                $script .= '});';
            $script .= '});';
        $script .= '</script>' . "\n";

        return $script;
    }

    /**
     * Static Effect.
     *
     * @param  int     $top       Distance from the page top.
     * @param  boolean $opacity   Add opacity.
     * @param  string  $intensity Control opacity intensity.
     *
     * @return string             jQuery with the effect.
     */
    protected function effect_static( $top, $opacity = false, $intensity = '0.7' ) {

        $opacity_motion = ( true == $opacity ) ? 'opacity:"1",' : '';
        $opacity_initial = ( true == $opacity ) ? 'opacity:"' . $intensity . '",' : '';

        $script = '<script type="text/javascript">';
            $script .= 'jQuery(document).ready(function($){';
                $script .= 'var socialbox = $("#socialfblog-box");';
                $script .= 'var offset = socialbox.offset();';
                $script .= '$(window).scroll(function(){';
                    $script .= 'if ($(window).scrollTop() > offset.top){';
                        $script .= 'socialbox.stop().css({' . $opacity_motion . 'position:"fixed",top:60});';
                    $script .= '} else {';
                        $script .= 'socialbox.stop().css({' . $opacity_initial . 'position:"absolute",top:"' . $top . 'px"});';
                    $script .= '}';
                $script .= '});';
            $script .= '});';
        $script .= '</script>' . "\n";

        return $script;
    }

    /**
     * Display jQuery validate options in footer.
     */
    public function footer_js() {
        if ( is_single() || is_page() ) {
            $settings = get_option( 'socialfblog_settings' );
            $buttons = get_option( 'socialfblog_buttons' );

            $twitter = '<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>' . "\n";

            $google = sprintf( '<script type="text/javascript">window.___gcfg = {lang: "%s"};(function() {var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);})();</script>', __( 'en-US', 'social-fblog' ) ) . "\n"; // pt-BR

            $facebook = sprintf( '<div id="fb-root"></div><script type="text/javascript">(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) {return;}js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/%s/all.js#xfbml=1&appId=228619377180035";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>', __( 'en_US', 'social-fblog' ) ) . "\n"; // pt_BR

            $linkedin = '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';

            $pinterest = '<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>';

            $top = $settings['top_distance'];
            $opacity = ( 0 != $settings['opacity'] ) ? true : false;
            $intensity = $settings['opacity_intensity'];

            $scripts = ( 0 == $settings['effects'] ) ? $this->effect_elastic( $opacity, $intensity ) : $this->effect_static( $top, $opacity, $intensity );

            $scripts .= isset( $buttons['twitter_active'] ) ? $twitter : '';
            $scripts .= isset( $buttons['google_active'] ) ? $google : '';
            $scripts .= isset( $buttons['facebook_active'] ) ? $facebook : '';
            $scripts .= isset( $buttons['linkedin_active'] ) ? $linkedin : '';
            $scripts .= isset( $buttons['pinterest_active'] ) ? $pinterest : '';

            $scripts = apply_filters( 'socialfblog_scripts' , $scripts );

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
            switch ( $settings['opacity'] ) {
                case 1:
                    $opacity = ' opacity:' . $settings['opacity_intensity'] . ';';
                    break;
                case 2:
                    $opacity = ' opacity:' . $settings['opacity_intensity'] . ' !important;';
                    break;

                default:
                    $opacity = '';
                    break;
            }

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

new Social_FBlog();
