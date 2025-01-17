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

                const response = await fetch(form.action || window.location.href, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.success = true;
                    form.reset();
                } else {
                    alert(data.message || 'Error submitting form');
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
