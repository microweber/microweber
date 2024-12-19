export default function captchaAlpine() {
    return {
        message: '',
        captchaValue: '',

        init() {
            this.$watch('captchaValue', value => {
                if (value.length > 0) {
                    // Dispatch event for external handlers
                    window.dispatchEvent(new CustomEvent('captcha-input', {
                        detail: {value: value}
                    }));

                    // Call the callback via $dispatch
                    this.$dispatch('callback', value);
                }
            });
        },

        refreshCaptcha(imgElement) {
            const currentSrc = imgElement.src;
            const newSrc = currentSrc.split('?')[0] + '?w=100&h=60&uid=' + Date.now() + '&rand=' + Math.random();
            imgElement.src = newSrc;
        }
    }
}
