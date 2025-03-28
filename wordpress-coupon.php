<?php
/**
 * Plugin Name: WordPress Coupon
 * Plugin URI: https://example.com/wordpress-coupon
 * Description: A plugin to display coupons with copy-to-clipboard and click-to-reveal functionality.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: wordpress-coupon
 * Domain Path: /languages
 * License: GPL-2.0+
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('WP_COUPON_VERSION', '1.0.0');
define('WP_COUPON_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_COUPON_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_wordpress_coupon() {
    // Activation tasks (if any)
    flush_rewrite_rules();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wordpress_coupon() {
    // Deactivation tasks (if any)
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'activate_wordpress_coupon');
register_deactivation_hook(__FILE__, 'deactivate_wordpress_coupon');

/**
 * The core plugin class.
 */
require_once WP_COUPON_PLUGIN_DIR . 'includes/class-wordpress-coupon.php';

/**
 * Begins execution of the plugin.
 */
function run_wordpress_coupon() {
    $plugin = new WordPress_Coupon();
    $plugin->run();
}

run_wordpress_coupon();