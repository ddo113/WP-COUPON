(function($) {
    'use strict';

    /**
     * Initialize the admin functionality
     */
    $(document).ready(function() {
        // Initialize datepicker for expiry date field if available
        if ($.fn.datepicker && $('#wp_coupon_expiry').length) {
            $('#wp_coupon_expiry').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        }
        
        // Copy shortcode functionality
        $('.wp-coupon-copy-shortcode').on('click', function(e) {
            e.preventDefault();
            
            const shortcodeField = $(this).siblings('.wp-coupon-shortcode-field');
            
            // Select the text field
            shortcodeField.select();
            shortcodeField[0].setSelectionRange(0, 99999); // For mobile devices
            
            // Copy the text inside the text field
            document.execCommand('copy');
            
            // Alert the copied text
            $(this).text('Copied!');
            
            setTimeout(() => {
                $(this).text('Copy');
            }, 2000);
        });
        
        // Toggle settings fields based on dependencies
        function toggleDependentFields() {
            const revealType = $('#wp_coupon_reveal_type').val();
            
            if (revealType === 'click') {
                $('.wp-coupon-reveal-text-field').show();
            } else {
                $('.wp-coupon-reveal-text-field').hide();
            }
        }
        
        $('#wp_coupon_reveal_type').on('change', toggleDependentFields);
        toggleDependentFields();
    });

})(jQuery);