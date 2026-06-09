document.addEventListener('alpine:init', () => {
    Alpine.data('contactForm', (formId) => ({
        loading: false,
        success: false,
        // task-2026-06-08-contacterr — inline error feedback instead of a native alert().
        errorMessage: '',
        formData: {},
        formId: formId,

        init() {
            // Initialize form data from input fields
            const form = document.querySelector(`form[data-form-id="${this.formId}"]`);
            if (form) {
                const formElements = form.elements;
                for (let i = 0; i < formElements.length; i++) {
                    const element = formElements[i];
                    if (element.name) {
                        this.formData[element.name] = element.value;
                    }
                }
            }
        },

        async submitForm(event) {
            event.preventDefault();
            this.loading = true;
            this.success = false;
            this.errorMessage = '';


            setTimeout(() => {
                this.loading = false;
            }, 5000); // Fallback loading timeout


            try {
                const form = event.target;
                const formData = new FormData(form);

                // Use Laravel's route helper if available, otherwise fallback to direct URL
                let action;
                try {
                    action = typeof route !== 'undefined' ? route('api.contact_form_submit') : '/api/contact_form_submit';
                } catch (e) {
                    action = '/api/contact_form_submit';
                }

                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const ajaxSettings = {
                    url: action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                };

                // Add CSRF token to headers if found
                if (csrfToken) {
                    ajaxSettings.headers['X-CSRF-TOKEN'] = csrfToken;
                }

                const data = await new Promise((resolve, reject) => {
                    $.ajax({
                        ...ajaxSettings,
                        success: function(response) {
                            resolve(response);
                        },

                        error: function(xhr, status, error) {

                            reject(new Error(error || 'Ajax request failed'));
                        }
                    });
                });

                if (data.success) {
                    this.success = true;
                    this.errorMessage = '';
                    form.reset();
                } else {
                    // task-2026-06-08-contacterr — show the server error inline
                    // (x-text="errorMessage") instead of a native alert(), so error
                    // feedback is styled + accessible like the success message.
                    this.errorMessage = data.message || data.error || 'Error submitting form';
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                this.errorMessage = 'Error submitting form. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }));
});
