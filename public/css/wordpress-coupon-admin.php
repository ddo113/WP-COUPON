/**
 * All of the CSS for your admin-specific functionality should be
 * included in this file.
 */

/* Admin Settings Page */
.wp-coupon-shortcode-docs {
    background: #fff;
    padding: 20px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    margin-top: 20px;
}

.wp-coupon-shortcode-docs h3 {
    margin-top: 0;
    color: #23282d;
    font-size: 16px;
}

.wp-coupon-shortcode-docs pre {
    background: #f5f5f5;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 3px;
    margin: 10px 0;
    overflow: auto;
}

.wp-coupon-shortcode-docs ul {
    padding-left: 20px;
}

/* Meta Box Styles */
.form-table th {
    width: 25%;
}

#wp_coupon_details .form-table input[type="text"],
#wp_coupon_details .form-table input[type="url"],
#wp_coupon_details .form-table input[type="date"] {
    width: 100%;
    max-width: 400px;
}

#wp_coupon_details .form-table select {
    min-width: 150px;
}

/* Dashboard widgets */
.wp-coupon-dashboard-widget ul {
    margin: 0;
}

.wp-coupon-dashboard-widget li {
    margin-bottom: 8px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}

.wp-coupon-dashboard-widget li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.wp-coupon-dashboard-widget .coupon-title {
    font-weight: bold;
}

.wp-coupon-dashboard-widget .coupon-expiry {
    color: #666;
    font-size: 12px;
    margin-top: 4px;
}

.wp-coupon-dashboard-widget .coupon-expired {
    color: #dc3232;
}

/* Custom columns in admin list */
.column-coupon_code {
    width: 15%;
}

.column-coupon_expires {
    width: 12%;
}

.column-coupon_store {
    width: 12%;
}

.column-coupon_discount {
    width: 10%;
}

/* For the custom CSS textarea */
.wp-coupon-settings textarea.large-text {
    font-family: monospace;
}
