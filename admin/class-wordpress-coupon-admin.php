<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WordPress_Coupon
 */

class WordPress_Coupon_Admin {

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
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, WP_COUPON_PLUGIN_URL . 'admin/css/wordpress-coupon-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, WP_COUPON_PLUGIN_URL . 'admin/js/wordpress-coupon-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Add options page for plugin settings.
     *
     * @since    1.0.0
     */
    public function add_options_page() {
        add_submenu_page(
            'edit.php?post_type=wp_coupon',
            __('Coupon Settings', 'wordpress-coupon'),
            __('Settings', 'wordpress-coupon'),
            'manage_options',
            'wp-coupon-settings',
            array($this, 'display_options_page')
        );
    }

    /**
     * Display the options page content.
     *
     * @since    1.0.0
     */
    public function display_options_page() {
        include_once WP_COUPON_PLUGIN_DIR . 'admin/partials/wordpress-coupon-admin-display.php';
    }

    /**
     * Register settings for the plugin.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // Register a setting
        register_setting(
            'wp_coupon_settings',
            'wp_coupon_options',
            array($this, 'validate_settings')
        );

        // Add a section for general settings
        add_settings_section(
            'wp_coupon_general_section',
            __('General Settings', 'wordpress-coupon'),
            array($this, 'general_section_callback'),
            'wp-coupon-settings'
        );

        // Add fields to the general section
        add_settings_field(
            'default_style',
            __('Default Coupon Style', 'wordpress-coupon'),
            array($this, 'default_style_callback'),
            'wp-coupon-settings',
            'wp_coupon_general_section'
        );

        add_settings_field(
            'default_reveal',
            __('Default Reveal Type', 'wordpress-coupon'),
            array($this, 'default_reveal_callback'),
            'wp-coupon-settings',
            'wp_coupon_general_section'
        );

        add_settings_field(
            'success_message',
            __('Copy Success Message', 'wordpress-coupon'),
            array($this, 'success_message_callback'),
            'wp-coupon-settings',
            'wp_coupon_general_section'
        );

        // Add a section for display settings
        add_settings_section(
            'wp_coupon_display_section',
            __('Display Settings', 'wordpress-coupon'),
            array($this, 'display_section_callback'),
            'wp-coupon-settings'
        );

        // Add fields to the display section
        add_settings_field(
            'show_expired',
            __('Show Expired Coupons', 'wordpress-coupon'),
            array($this, 'show_expired_callback'),
            'wp-coupon-settings',
            'wp_coupon_display_section'
        );

        add_settings_field(
            'show_count',
            __('Show Coupon Use Count', 'wordpress-coupon'),
            array($this, 'show_count_callback'),
            'wp-coupon-settings',
            'wp_coupon_display_section'
        );

        add_settings_field(
            'custom_css',
            __('Custom CSS', 'wordpress-coupon'),
            array($this, 'custom_css_callback'),
            'wp-coupon-settings',
            'wp_coupon_display_section'
        );
    }

    /**
     * Validate settings before saving.
     *
     * @param    array    $input    The settings input.
     * @return   array              Sanitized settings.
     */
    public function validate_settings($input) {
        $output = array();

        // Sanitize the default style
        if (isset($input['default_style'])) {
            $output['default_style'] = sanitize_text_field($input['default_style']);
        }

        // Sanitize the default reveal type
        if (isset($input['default_reveal'])) {
            $output['default_reveal'] = sanitize_text_field($input['default_reveal']);
        }

        // Sanitize the success message
        if (isset($input['success_message'])) {
            $output['success_message'] = sanitize_text_field($input['success_message']);
        }

        // Sanitize the show expired option
        if (isset($input['show_expired'])) {
            $output['show_expired'] = (bool) $input['show_expired'] ? 1 : 0;
        }

        // Sanitize the show count option
        if (isset($input['show_count'])) {
            $output['show_count'] = (bool) $input['show_count'] ? 1 : 0;
        }

        // Sanitize the custom CSS
        if (isset($input['custom_css'])) {
            $output['custom_css'] = wp_strip_all_tags($input['custom_css']);
        }

        return $output;
    }

    /**
     * Callback for the general section.
     */
    public function general_section_callback() {
        echo '<p>' . __('Configure general settings for your coupons.', 'wordpress-coupon') . '</p>';
    }

    /**
     * Callback for the display section.
     */
    public function display_section_callback() {
        echo '<p>' . __('Configure how coupons are displayed on your site.', 'wordpress-coupon') . '</p>';
    }

    /**
     * Callback for the default style field.
     */
    public function default_style_callback() {
        $options = get_option('wp_coupon_options', array());
        $default_style = isset($options['default_style']) ? $options['default_style'] : 'default';
        ?>
        <select name="wp_coupon_options[default_style]">
            <option value="default" <?php selected($default_style, 'default'); ?>><?php _e('Default', 'wordpress-coupon'); ?></option>
            <option value="minimal" <?php selected($default_style, 'minimal'); ?>><?php _e('Minimal', 'wordpress-coupon'); ?></option>
            <option value="card" <?php selected($default_style, 'card'); ?>><?php _e('Card', 'wordpress-coupon'); ?></option>
        </select>
        <p class="description"><?php _e('Select the default style for coupons.', 'wordpress-coupon'); ?></p>
        <?php
    }

    /**
     * Callback for the default reveal field.
     */
    public function default_reveal_callback() {
        $options = get_option('wp_coupon_options', array());
        $default_reveal = isset($options['default_reveal']) ? $options['default_reveal'] : 'click';
        ?>
        <select name="wp_coupon_options[default_reveal]">
            <option value="click" <?php selected($default_reveal, 'click'); ?>><?php _e('Click to Reveal', 'wordpress-coupon'); ?></option>
            <option value="show" <?php selected($default_reveal, 'show'); ?>><?php _e('Show Immediately', 'wordpress-coupon'); ?></option>
        </select>
        <p class="description"><?php _e('Select the default method for revealing coupon codes.', 'wordpress-coupon'); ?></p>
        <?php
    }

    /**
     * Callback for the success message field.
     */
    public function success_message_callback() {
        $options = get_option('wp_coupon_options', array());
        $success_message = isset($options['success_message']) ? $options['success_message'] : __('Coupon code copied!', 'wordpress-coupon');
        ?>
        <input type="text" name="wp_coupon_options[success_message]" value="<?php echo esc_attr($success_message); ?>" class="regular-text" />
        <p class="description"><?php _e('Message displayed when a coupon code is successfully copied.', 'wordpress-coupon'); ?></p>
        <?php
    }

    /**
     * Callback for the show expired field.
     */
    public function show_expired_callback() {
        $options = get_option('wp_coupon_options', array());
        $show_expired = isset($options['show_expired']) ? $options['show_expired'] : 0;
        ?>
        <input type="checkbox" name="wp_coupon_options[show_expired]" value="1" <?php checked($show_expired, 1); ?> />
        <p class="description"><?php _e('Show expired coupons in coupon lists.', 'wordpress-coupon'); ?></p>
        <?php
    }

    /**
     * Callback for the show count field.
     */
    public function show_count_callback() {
        $options = get_option('wp_coupon_options', array());
        $show_count = isset($options['show_count']) ? $options['show_count'] : 0;
        ?>
        <input type="checkbox" name="wp_coupon_options[show_count]" value="1" <?php checked($show_count, 1); ?> />
        <p class="description"><?php _e('Show how many times a coupon has been used.', 'wordpress-coupon'); ?></p>
        <?php
    }

    /**
     * Callback for the custom CSS field.
     */
    public function custom_css_callback() {
        $options = get_option('wp_coupon_options', array());
        $custom_css = isset($options['custom_css']) ? $options['custom_css'] : '';
        ?>
        <textarea name="wp_coupon_options[custom_css]" rows="10" cols="50" class="large-text code"><?php echo esc_textarea($custom_css); ?></textarea>
        <p class="description"><?php _e('Add custom CSS to style your coupons.', 'wordpress-coupon'); ?></p>
        <?php
    }
}