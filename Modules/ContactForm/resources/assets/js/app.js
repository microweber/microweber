// Contact Form Module
const ContactForm = {
    init() {
        this.bindEvents();
    },

    bindEvents() {
        // Find all contact forms on the page
        document.querySelectorAll('.contact-form-container form').forEach(form => {
            form.addEventListener('submit', this.handleSubmit.bind(this));
        });
    },

    async handleSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const formId = form.getAttribute('data-form-id');
        const messageContainer = document.getElementById(`msg${formId}`);
        
        try {
            // Get form data
            const formData = new FormData(form);
            
            // Submit form using fetch
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            if (data.success) {
                // Hide form and show success message
                form.style.display = 'none';
                if (messageContainer) {
                    messageContainer.classList.add('show');
                }

                // Reset form
                form.reset();

                // Optional: Hide success message after delay
                setTimeout(() => {
                    if (messageContainer) {
                        messageContainer.classList.remove('show');
                    }
                    form.style.display = 'block';
                }, 5000);
            } else {
                // Handle error response
                console.error('Form submission error:', data.message);
            }
        } catch (error) {
            console.error('Error submitting form:', error);
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    ContactForm.init();
});

// Re-initialize when content is reloaded dynamically
window.addEventListener('load', () => {
    ContactForm.init();
});

// Export for module usage
export default ContactForm;
