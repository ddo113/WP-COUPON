v<?php
/**
 * Provide a admin area view for the plugin
 *
 * @since      1.0.0
 * @package    WordPress_Coupon
 */
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        // Output security fields
        settings_fields('wp_coupon_settings');
        
        // Output setting sections and their fields
        do_settings_sections('wp-coupon-settings');
        
        // Output save settings button
        submit_button(__('Save Settings', 'wordpress-coupon'));
        ?>
    </form>
    
    <hr>
    
    <h2><?php _e('Shortcode Usage', 'wordpress-coupon'); ?></h2>
    
    <div class="wp-coupon-shortcode-docs">
        <h3><?php _e('Display a single coupon', 'wordpress-coupon'); ?></h3>
        <pre>[wp_coupon id="123" style="default"]</pre>
        <p><?php _e('Parameters:', 'wordpress-coupon'); ?></p>
        <ul>
            <li><strong>id</strong> - <?php _e('The ID of the coupon to display (required)', 'wordpress-coupon'); ?></li>
            <li><strong>style</strong> - <?php _e('The style to use (default, minimal, card)', 'wordpress-coupon'); ?></li>
        </ul>
        
        <h3><?php _e('Display multiple coupons', 'wordpress-coupon'); ?></h3>
        <pre>[wp_coupons count="5" category="fashion" orderby="date" order="DESC" style="default"]</pre>
        <p><?php _e('Parameters:', 'wordpress-coupon'); ?></p>
        <ul>
            <li><strong>count</strong> - <?php _e('Number of coupons to display (default: 5)', 'wordpress-coupon'); ?></li>
            <li><strong>category</strong> - <?php _e('Filter by category slug (comma-separated for multiple)', 'wordpress-coupon'); ?></li>
            <li><strong>orderby</strong> - <?php _e('Sort by field (date, title, etc.)', 'wordpress-coupon'); ?></li>
            <li><strong>order</strong> - <?php _e('Sort order (DESC or ASC)', 'wordpress-coupon'); ?></li>
            <li><strong>style</strong> - <?php _e('The style to use (default, minimal, card, list)', 'wordpress-coupon'); ?></li>
        </ul>
    </div>
</div>
