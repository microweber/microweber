export default function contactForm(formId) {
    return {
        loading: false,
        success: false,
        formData: {},

        init() {
            // Initialize form data from input fields
            const form = document.querySelector(`form[data-form-id="${formId}"]`);
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

            try {
                const form = event.target;
                const formData = new FormData(form);
                const action = route('api.contact_form_submit');



                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const headers = {
                    'X-Requested-With': 'XMLHttpRequest'
                };

                // Add CSRF token to headers if found
                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken;
                }

                const response = await fetch(action, {
                    method: 'POST',
                    body: formData,
                    headers: headers
                });

                const data = await response.json();

                if (data.success) {
                    this.success = true;
                    form.reset();
                } else {
                    alert(data.message || data.error || 'Error submitting form');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                alert('Error submitting form. Please try again.');
            } finally {
                this.loading = false;
            }
        }
    }
}
