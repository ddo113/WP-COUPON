
(function($) {
    'use strict';

    function initCoupons() {
        $('.wp-coupon-code-button').on('click', function(e) {
            e.preventDefault();

            const $this = $(this);
            const couponContainer = $this.closest('.wp-coupon-container');
            const destinationUrl = couponContainer.data('destination-url');
            const codeElement = $this.siblings('.wp-coupon-code');
            const couponCode = codeElement.data('coupon');

            // Elimină butonul Go to Deal (dacă există)
            couponContainer.find('.wp-coupon-go-hidden, .wp-coupon-go').remove();

            if (destinationUrl) {
                window.open(destinationUrl, '_blank');
            }

            showCouponModal(couponCode, destinationUrl);
        });
    }

    function showCouponModal(code, destinationUrl) {
        let modal = document.getElementById('wp-coupon-modal');

        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'wp-coupon-modal';
            modal.innerHTML = `
                <div class="wp-coupon-modal-backdrop"></div>
                <div class="wp-coupon-modal-content">
                    <span class="wp-coupon-modal-close">&times;</span>
                    <h2>Your code is ready 💥</h2>
                    <p class="wp-coupon-modal-description">Use it now to ensure you receive your discount.</p>
                    <div class="wp-coupon-code-display" id="modal-code">${code}</div>
                    <button id="copy-code-button" class="copy-button">Copy Code</button>
                    <button id="redeem-deal-button" class="redeem-button">Redeem Deal</button>
                    <p id="copy-feedback" style="display:none; color: green; margin-top: 10px;">✅ Code copied to clipboard!</p>
                </div>
            `;
            document.body.appendChild(modal);

            modal.querySelector('.wp-coupon-modal-close').onclick = () => modal.remove();
            modal.querySelector('.wp-coupon-modal-backdrop').onclick = () => modal.remove();
        } else {
            modal.querySelector('#modal-code').textContent = code;
        }

        modal.style.display = 'block';

        const copyBtn = document.getElementById('copy-code-button');
        const feedback = document.getElementById('copy-feedback');
        copyBtn.onclick = () => {
            const tempInput = document.createElement('input');
            tempInput.value = code;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            feedback.style.display = 'block';
            setTimeout(() => feedback.style.display = 'none', 2500);
        };

        const redeemBtn = document.getElementById('redeem-deal-button');
        redeemBtn.onclick = () => {
            if (destinationUrl) {
                window.open(destinationUrl, '_blank');
            }
        };

        setTimeout(() => {
            if (modal && modal.parentNode) {
                modal.remove();
            }
        }, 10000);
    }

    $(document).ready(initCoupons);

})(jQuery);
