<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WordPress_Coupon
 */

class WordPress_Coupon_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of the plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, WP_COUPON_PLUGIN_URL . 'public/css/wordpress-coupon-public.css', array(), $this->version, 'all');
        
        // Add custom CSS if set in options
        $options = get_option('wp_coupon_options', array());
        if (!empty($options['custom_css'])) {
            wp_add_inline_style($this->plugin_name, $options['custom_css']);
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // Enqueue Clipboard.js for the copy to clipboard functionality
        wp_enqueue_script('clipboard', WP_COUPON_PLUGIN_URL . 'public/js/clipboard.min.js', array(), '2.0.11', true);
        
        // Enqueue the main plugin script
        wp_enqueue_script($this->plugin_name, WP_COUPON_PLUGIN_URL . 'public/js/wordpress-coupon-public.js', array('jquery', 'clipboard'), $this->version, true);
        
        // Pass data to the script
        $options = get_option('wp_coupon_options', array());
        $success_message = isset($options['success_message']) ? $options['success_message'] : __('Coupon code copied!', 'wordpress-coupon');
        
        wp_localize_script($this->plugin_name, 'wpCouponData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'successMessage' => esc_html($success_message),
            'errorMessage' => __('Failed to copy code. Please try again.', 'wordpress-coupon'),
            'clickToReveal' => __('', 'wordpress-coupon'),
            'revealBtnText' => __('Show Code', 'wordpress-coupon'),
            'copyBtnText' => __('Copy Code', 'wordpress-coupon'),
            'copiedBtnText' => __('Copied!', 'wordpress-coupon'),
            'nonce' => wp_create_nonce('wp-coupon-nonce'),
        ));
    }

    /**
     * Track coupon usage via AJAX.
     *
     * @since    1.0.0
     */
    public function track_coupon_usage() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wp-coupon-nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        // Get the coupon ID
        $coupon_id = isset($_POST['coupon_id']) ? absint($_POST['coupon_id']) : 0;
        
        if (!$coupon_id) {
            wp_send_json_error('Invalid coupon ID');
        }

        // Get current count
        $count = get_post_meta($coupon_id, '_wp_coupon_use_count', true);
        if (empty($count)) {
            $count = 0;
        }

        // Increment and update
        $count++;
        update_post_meta($coupon_id, '_wp_coupon_use_count', $count);

        wp_send_json_success(array(
            'count' => $count
        ));
    }
}