<?php
/**
 * The shortcode functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WordPress_Coupon
 */

class WordPress_Coupon_Shortcodes {

    /**
     * Register the shortcodes.
     *
     * @since    1.0.0
     */
    public function register_shortcodes() {
        add_shortcode('wp_coupon', array($this, 'render_coupon_shortcode'));
        add_shortcode('wp_coupons', array($this, 'render_coupons_list_shortcode'));
    }

    /**
     * Render a single coupon based on ID.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string             Shortcode output.
     */
    public function render_coupon_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'style' => 'default', // default, minimal, card
        ), $atts, 'wp_coupon');

        if (empty($atts['id'])) {
            return '<p class="wp-coupon-error">' . __('Error: Coupon ID is required.', 'wordpress-coupon') . '</p>';
        }

        $coupon_id = absint($atts['id']);
        $coupon = get_post($coupon_id);

        if (!$coupon || 'wp_coupon' !== $coupon->post_type) {
            return '<p class="wp-coupon-error">' . __('Error: Coupon not found.', 'wordpress-coupon') . '</p>';
        }

        return $this->generate_coupon_html($coupon, $atts);
    }

    /**
     * Render a list of coupons based on query parameters.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string             Shortcode output.
     */
    public function render_coupons_list_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => 5,
            'category' => '',
            'orderby' => 'date',
            'order' => 'DESC',
            'style' => 'default', // default, minimal, card, list
        ), $atts, 'wp_coupons');

        $args = array(
            'post_type' => 'wp_coupon',
            'posts_per_page' => absint($atts['count']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );

        // Filter by category if provided
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'coupon_category',
                    'field' => 'slug',
                    'terms' => explode(',', $atts['category']),
                ),
            );
        }

        $coupons = get_posts($args);

        if (empty($coupons)) {
            return '<p class="wp-coupon-info">' . __('No coupons found.', 'wordpress-coupon') . '</p>';
        }

        $output = '<div class="wp-coupons-list wp-coupons-style-' . esc_attr($atts['style']) . '">';

        foreach ($coupons as $coupon) {
            $output .= $this->generate_coupon_html($coupon, $atts);
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Generate HTML for a single coupon.
     *
     * @param    WP_Post    $coupon    The coupon post object.
     * @param    array      $atts      The shortcode attributes.
     * @return   string                 The coupon HTML.
     */
    private function generate_coupon_html($coupon, $atts) {
        // Get coupon meta data
        $coupon_code = get_post_meta($coupon->ID, '_wp_coupon_code', true);
        $expiry_date = get_post_meta($coupon->ID, '_wp_coupon_expiry', true);
        $store_name = get_post_meta($coupon->ID, '_wp_coupon_store', true);
        $discount_value = get_post_meta($coupon->ID, '_wp_coupon_discount', true);
        $discount_type = get_post_meta($coupon->ID, '_wp_coupon_discount_type', true);
        $reveal_type = get_post_meta($coupon->ID, '_wp_coupon_reveal_type', true);
        $destination_url = get_post_meta($coupon->ID, '_wp_coupon_url', true);

        // Format discount text
        $discount_text = '';
        if (!empty($discount_value)) {
            if ('percent' === $discount_type) {
                $discount_text = $discount_value . '%';
            } else {
                $discount_text = '$' . $discount_value;
            }
            $discount_text .= ' ' . __('Off', 'wordpress-coupon');
        }

        // Check if coupon is expired
        $is_expired = false;
        if (!empty($expiry_date)) {
            $expiry_timestamp = strtotime($expiry_date);
            $current_timestamp = current_time('timestamp');
            $is_expired = $expiry_timestamp < $current_timestamp;
        }

        // Get coupon description
        $description = $coupon->post_content;

        // Build the coupon HTML
        $unique_id = 'wp-coupon-' . $coupon->ID . '-' . uniqid();
        $style_class = 'wp-coupon-style-' . esc_attr($atts['style']);
        $expired_class = $is_expired ? ' wp-coupon-expired' : '';

        $output = '<div id="' . esc_attr($unique_id) . '" class="wp-coupon-container ' . $style_class . $expired_class . '" data-id="' . esc_attr($coupon->ID) . '" data-destination-url="' . esc_url($destination_url) . '">';

        // Coupon header
        $output .= '<div class="wp-coupon-header">';
        
        if (has_post_thumbnail($coupon->ID)) {
            $output .= '<div class="wp-coupon-store-thumb">' . get_the_post_thumbnail($coupon->ID, 'thumbnail') . '</div>';
        }
        
        if (!empty($store_name)) {
            $output .= '<div class="wp-coupon-store-name">' . esc_html($store_name) . '</div>';
        }
        
        if (!empty($discount_text)) {
            $output .= '<div class="wp-coupon-discount">' . esc_html($discount_text) . '</div>';
        }
        
        $output .= '</div>'; // End header

        // Coupon content
        $output .= '<div class="wp-coupon-content">';
        $output .= '<h3 class="wp-coupon-title">' . esc_html($coupon->post_title) . '</h3>';
        
        if (!empty($description)) {
            $output .= '<div class="wp-coupon-description">' . wp_kses_post($description) . '</div>';
        }
        
        // Expiry date display
        if (!empty($expiry_date)) {
            $expiry_formatted = date_i18n(get_option('date_format'), strtotime($expiry_date));
            $expiry_class = $is_expired ? 'wp-coupon-expired-text' : '';
            
            $output .= '<div class="wp-coupon-expiry ' . $expiry_class . '">';
            
            if ($is_expired) {
                $output .= __('Expired on: ', 'wordpress-coupon');
            } else {
                $output .= __('Expires on: ', 'wordpress-coupon');
            }
            
            $output .= esc_html($expiry_formatted) . '</div>';
        }
        
        $output .= '</div>'; // End content

        // Right section with code and button
        $output .= '<div class="wp-coupon-code-section">';
        
        if (!empty($coupon_code)) {
            // Determine if code should be hidden initially
            $hidden_class = ('click' === $reveal_type) ? ' wp-coupon-hidden' : '';
            $button_text = ('click' === $reveal_type) ? __('Show Coupon Code', 'wordpress-coupon') : __('Copy Code', 'wordpress-coupon');
            
            $output .= '<div class="wp-coupon-code-wrap">';
            $output .= '<span class="wp-coupon-code' . $hidden_class . '" data-coupon="' . esc_attr($coupon_code) . '">';
            
            if ('click' === $reveal_type) {
                $output .= __('Click to reveal', 'wordpress-coupon');
            } else {
                $output .= esc_html($coupon_code);
            }
            
            $output .= '</span>';
            $output .= '<button class="wp-coupon-code-button" data-clipboard-text="' . esc_attr($coupon_code) . '">' . esc_html($button_text) . '</button>';
            $output .= '</div>';
        }
        
        if (!empty($destination_url)) {
            $output .= '';
        }
        
        $output .= '</div>'; // End code section

        $output .= '</div>'; // End coupon container

        return $output;
    }
}