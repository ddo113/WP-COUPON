<?php
/**
 * The post types functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WordPress_Coupon
 */

class WordPress_Coupon_Post_Types {

    /**
     * Register the coupon post type.
     *
     * @since    1.0.0
     */
    public function register_coupon_post_type() {
        $labels = array(
            'name'                  => _x('Coupons', 'Post type general name', 'wordpress-coupon'),
            'singular_name'         => _x('Coupon', 'Post type singular name', 'wordpress-coupon'),
            'menu_name'             => _x('Coupons', 'Admin Menu text', 'wordpress-coupon'),
            'name_admin_bar'        => _x('Coupon', 'Add New on Toolbar', 'wordpress-coupon'),
            'add_new'               => __('Add New', 'wordpress-coupon'),
            'add_new_item'          => __('Add New Coupon', 'wordpress-coupon'),
            'new_item'              => __('New Coupon', 'wordpress-coupon'),
            'edit_item'             => __('Edit Coupon', 'wordpress-coupon'),
            'view_item'             => __('View Coupon', 'wordpress-coupon'),
            'all_items'             => __('All Coupons', 'wordpress-coupon'),
            'search_items'          => __('Search Coupons', 'wordpress-coupon'),
            'parent_item_colon'     => __('Parent Coupons:', 'wordpress-coupon'),
            'not_found'             => __('No coupons found.', 'wordpress-coupon'),
            'not_found_in_trash'    => __('No coupons found in Trash.', 'wordpress-coupon'),
            'featured_image'        => __('Coupon Cover Image', 'wordpress-coupon'),
            'set_featured_image'    => __('Set cover image', 'wordpress-coupon'),
            'remove_featured_image' => __('Remove cover image', 'wordpress-coupon'),
            'use_featured_image'    => __('Use as cover image', 'wordpress-coupon'),
            'archives'              => __('Coupon archives', 'wordpress-coupon'),
            'insert_into_item'      => __('Insert into coupon', 'wordpress-coupon'),
            'uploaded_to_this_item' => __('Uploaded to this coupon', 'wordpress-coupon'),
            'filter_items_list'     => __('Filter coupons list', 'wordpress-coupon'),
            'items_list_navigation' => __('Coupons list navigation', 'wordpress-coupon'),
            'items_list'            => __('Coupons list', 'wordpress-coupon'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'coupon'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-tickets-alt',
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
        );

        register_post_type('wp_coupon', $args);
        
        // Register taxonomy for coupon categories
        $category_labels = array(
            'name'              => _x('Coupon Categories', 'taxonomy general name', 'wordpress-coupon'),
            'singular_name'     => _x('Coupon Category', 'taxonomy singular name', 'wordpress-coupon'),
            'search_items'      => __('Search Coupon Categories', 'wordpress-coupon'),
            'all_items'         => __('All Coupon Categories', 'wordpress-coupon'),
            'parent_item'       => __('Parent Coupon Category', 'wordpress-coupon'),
            'parent_item_colon' => __('Parent Coupon Category:', 'wordpress-coupon'),
            'edit_item'         => __('Edit Coupon Category', 'wordpress-coupon'),
            'update_item'       => __('Update Coupon Category', 'wordpress-coupon'),
            'add_new_item'      => __('Add New Coupon Category', 'wordpress-coupon'),
            'new_item_name'     => __('New Coupon Category Name', 'wordpress-coupon'),
            'menu_name'         => __('Categories', 'wordpress-coupon'),
        );

        $category_args = array(
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'coupon-category'),
        );

        register_taxonomy('coupon_category', array('wp_coupon'), $category_args);
        
        // Register custom meta boxes
        add_action('add_meta_boxes', array($this, 'add_coupon_meta_boxes'));
        
        // Save post meta
        add_action('save_post', array($this, 'save_coupon_meta'));
    }
    
    /**
     * Add meta boxes for the coupon post type.
     */
    public function add_coupon_meta_boxes() {
        add_meta_box(
            'wp_coupon_details',
            __('Coupon Details', 'wordpress-coupon'),
            array($this, 'render_coupon_details_meta_box'),
            'wp_coupon',
            'normal',
            'high'
        );
    }
    
    /**
     * Render the coupon details meta box.
     */
    public function render_coupon_details_meta_box($post) {
        // Add a nonce field for security
        wp_nonce_field('wp_coupon_meta_box', 'wp_coupon_meta_box_nonce');
        
        // Retrieve existing values
        $coupon_code = get_post_meta($post->ID, '_wp_coupon_code', true);
        $expiry_date = get_post_meta($post->ID, '_wp_coupon_expiry', true);
        $store_name = get_post_meta($post->ID, '_wp_coupon_store', true);
        $discount_value = get_post_meta($post->ID, '_wp_coupon_discount', true);
        $discount_type = get_post_meta($post->ID, '_wp_coupon_discount_type', true);
        $reveal_type = get_post_meta($post->ID, '_wp_coupon_reveal_type', true);
        $destination_url = get_post_meta($post->ID, '_wp_coupon_url', true);
        
        // Default values
        if (empty($discount_type)) {
            $discount_type = 'percent';
        }
        
        if (empty($reveal_type)) {
            $reveal_type = 'click';
        }
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="wp_coupon_code"><?php _e('Coupon Code', 'wordpress-coupon'); ?></label></th>
                <td>
                    <input type="text" id="wp_coupon_code" name="wp_coupon_code" value="<?php echo esc_attr($coupon_code); ?>" class="regular-text" />
                    <p class="description"><?php _e('Enter the coupon code.', 'wordpress-coupon'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="wp_coupon_expiry"><?php _e('Expiry Date', 'wordpress-coupon'); ?></label></th>
                <td>
                    <input type="date" id="wp_coupon_expiry" name="wp_coupon_expiry" value="<?php echo esc_attr($expiry_date); ?>" />
                    <p class="description"><?php _e('Enter the expiry date of the coupon.', 'wordpress-coupon'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="wp_coupon_store"><?php _e('Store Name', 'wordpress-coupon'); ?></label></th>
                <td>
                    <input type="text" id="wp_coupon_store" name="wp_coupon_store" value="<?php echo esc_attr($store_name); ?>" class="regular-text" />
                    <p class="description"><?php _e('Enter the store or website name.', 'wordpress-coupon'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="wp_coupon_discount"><?php _e('Discount Value', 'wordpress-coupon'); ?></label></th>
                <td>
                    <input type="text" id="wp_coupon_discount" name="wp_coupon_discount" value="<?php echo esc_attr($discount_value); ?>" class="regular-text" />
                    <select id="wp_coupon_discount_type" name="wp_coupon_discount_type">
                        <option value="percent" <?php selected($discount_type, 'percent'); ?>><?php _e('Percentage (%)', 'wordpress-coupon'); ?></option>
                        <option value="fixed" <?php selected($discount_type, 'fixed'); ?>><?php _e('Fixed Amount', 'wordpress-coupon'); ?></option>
                    </select>
                    <p class="description"><?php _e('Enter the discount value and select the type.', 'wordpress-coupon'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="wp_coupon_reveal_type"><?php _e('Reveal Type', 'wordpress-coupon'); ?></label></th>
                <td>
                    <select id="wp_coupon_reveal_type" name="wp_coupon_reveal_type">
                        <option value="click" <?php selected($reveal_type, 'click'); ?>><?php _e('Click to Reveal', 'wordpress-coupon'); ?></option>
                        <option value="show" <?php selected($reveal_type, 'show'); ?>><?php _e('Show Immediately', 'wordpress-coupon'); ?></option>
                    </select>
                    <p class="description"><?php _e('Choose how the coupon code is revealed to users.', 'wordpress-coupon'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="wp_coupon_url"><?php _e('Destination URL', 'wordpress-coupon'); ?></label></th>
                <td>
                    <input type="url" id="wp_coupon_url" name="wp_coupon_url" value="<?php echo esc_url($destination_url); ?>" class="regular-text" />
                    <p class="description"><?php _e('Enter the URL where users can use this coupon.', 'wordpress-coupon'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    /**
     * Save the coupon meta data.
     */
    public function save_coupon_meta($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['wp_coupon_meta_box_nonce'])) {
            return;
        }
        
        // Verify the nonce
        if (!wp_verify_nonce($_POST['wp_coupon_meta_box_nonce'], 'wp_coupon_meta_box')) {
            return;
        }
        
        // If this is an autosave, we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check the user's permissions
        if (isset($_POST['post_type']) && 'wp_coupon' == $_POST['post_type']) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }
        
        // Update the meta fields
        if (isset($_POST['wp_coupon_code'])) {
            update_post_meta($post_id, '_wp_coupon_code', sanitize_text_field($_POST['wp_coupon_code']));
        }
        
        if (isset($_POST['wp_coupon_expiry'])) {
            update_post_meta($post_id, '_wp_coupon_expiry', sanitize_text_field($_POST['wp_coupon_expiry']));
        }
        
        if (isset($_POST['wp_coupon_store'])) {
            update_post_meta($post_id, '_wp_coupon_store', sanitize_text_field($_POST['wp_coupon_store']));
        }
        
        if (isset($_POST['wp_coupon_discount'])) {
            update_post_meta($post_id, '_wp_coupon_discount', sanitize_text_field($_POST['wp_coupon_discount']));
        }
        
        if (isset($_POST['wp_coupon_discount_type'])) {
            update_post_meta($post_id, '_wp_coupon_discount_type', sanitize_text_field($_POST['wp_coupon_discount_type']));
        }
        
        if (isset($_POST['wp_coupon_reveal_type'])) {
            update_post_meta($post_id, '_wp_coupon_reveal_type', sanitize_text_field($_POST['wp_coupon_reveal_type']));
        }
        
        if (isset($_POST['wp_coupon_url'])) {
            update_post_meta($post_id, '_wp_coupon_url', esc_url_raw($_POST['wp_coupon_url']));
        }
    }
}