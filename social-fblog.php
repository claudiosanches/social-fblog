<?php
/**
 * Social FBlog.
 *
 * @package   Social_FBlog
 * @author    Claudio Sanches <contato@claudiosmweb.com>
 * @license   GPL-2.0+
 * @link      https://github.com/claudiosmweb/social-fblog
 * @copyright 2013 Claudio Sanches
 *
 * @wordpress-plugin
 * Plugin Name: Social FBlog
 * Plugin URI: https://github.com/claudiosmweb/social-fblog
 * Description: Inserts a floating box next to your blog posts to share your content on Twitter, Facebook and Google Plus and others.
 * Version: 3.2.0
 * Author: claudiosanches
 * Author URI: http://claudiosmweb.com/
 * Text Domain: social-fblog
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/claudiosmweb/social-fblog
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

require_once plugin_dir_path( __FILE__ ) . '/includes/class-social-fblog.php';

/**
 * Install default settings.
 */
register_activation_hook( __FILE__, array( 'Social_FBlog', 'install' ) );

/**
 * Load plugin instance.
 */
add_action( 'plugins_loaded', array( 'Social_FBlog', 'get_instance' ) );

/**
 * Plugin admin.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/includes/class-social-fblog-admin.php';
	add_action( 'plugins_loaded', array( 'Social_FBlog_Admin', 'get_instance' ) );
}
