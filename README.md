# WordPress Coupon Plugin

A WordPress plugin to display coupons on your website with copy-to-clipboard and click-to-reveal functionality.

## Features

- Create and manage coupons using a custom post type
- Display coupons using shortcodes
- Click-to-reveal functionality for coupon codes
- Copy-to-clipboard button for easy coupon usage
- Multiple display styles (default, minimal, card, list)
- Expiry date support with expired coupon indication
- Coupon usage tracking
- Coupon categories for organization
- Responsive design for all screen sizes

## Installation

1. Download the plugin zip file
2. Upload the plugin to your WordPress site via the Plugins section
3. Activate the plugin
4. Create your first coupon by going to Coupons > Add New

## Usage

### Creating Coupons

1. Go to Coupons > Add New in your WordPress admin
2. Enter the coupon title and description
3. Fill in the coupon details in the Coupon Details meta box:
   - Coupon Code: The code users will copy
   - Expiry Date: When the coupon expires
   - Store Name: The store or website name
   - Discount Value: Amount or percentage of the discount
   - Reveal Type: Choose between "Click to Reveal" or "Show Immediately"
   - Destination URL: Where users will go to use the coupon
4. Publish your coupon

### Displaying Coupons

Use the following shortcodes to display coupons on your site:

#### Display a single coupon

```
[wp_coupon id="123" style="default"]
```

Parameters:
- `id` - The ID of the coupon to display (required)
- `style` - The style to use (default, minimal, card)

#### Display multiple coupons

```
[wp_coupons count="5" category="fashion" orderby="date" order="DESC" style="default"]
```

Parameters:
- `count` - Number of coupons to display (default: 5)
- `category` - Filter by category slug (comma-separated for multiple)
- `orderby` - Sort by field (date, title, etc.)
- `order` - Sort order (DESC or ASC)
- `style` - The style to use (default, minimal, card, list)

## Customization

You can customize the appearance of coupons by:

1. Going to Coupons > Settings
2. Setting your preferred defaults
3. Adding custom CSS in the settings page

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Uses [Clipboard.js](https://clipboardjs.com/) for copy functionality