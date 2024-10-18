


// JavaScript to toggle dropdown visibility
document.addEventListener('DOMContentLoaded', function () {


    const dropdown = document.querySelector('.dropdown');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    if (!dropdown || !dropdownMenu) {
        return;
    }

    dropdown.addEventListener('click', function () {
        dropdownMenu.classList.toggle('show');
    });

    // Close the dropdown if the user clicks outside of it
    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });


});

document.addEventListener('DOMContentLoaded', function () {


    // Function to add bottom effect spans
    function addBottomEffect() {
        // Select all inputs within .form-control-live-edit-label-wrapper and .fi-input-wrp
        const inputs = document.querySelectorAll('.form-control-live-edit-label-wrapper .form-control-live-edit-input:not(.form-select), .fi-input-wrp .fi-input, .fi-input-wpr .fi-select-input');

        // Loop through each input element
        inputs.forEach(input => {
            // Check if a span with the class already exists
            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('form-control-live-edit-bottom-effect')) {
                // Create the span element
                const span = document.createElement('span');
                span.className = 'form-control-live-edit-bottom-effect';

                // Insert the span element after the input element
                input.insertAdjacentElement('afterend', span);
            }
        });
    }

// Run the function to add the bottom effect
    addBottomEffect();

// Create a MutationObserver instance
    const observer = new MutationObserver(addBottomEffect);

// Start observing the document with the configured parameters
    observer.observe(document.body, { childList: true, subtree: true });

});

window.addEventListener('livewire:init', function () {

    Livewire.on('mw-redirect-to-url', function (data) {

        if (typeof data['data']['url'] === 'undefined') {
            return;
        }

        if (mw.top().app && mw.top().app.canvas) {
            mw.top().app.canvas.setUrl(data['data']['url']);

        }


    });


});


document.addEventListener("DOMContentLoaded", function() {
    var footer = document.querySelector('.mw-dialog-footer');

    if (footer && footer.innerHTML.trim() === '') {
        footer.style.display = 'none';
    }
});

