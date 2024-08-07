console.log('microweber-filament-theme.js');


// JavaScript to toggle dropdown visibility
document.addEventListener('DOMContentLoaded', function () {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownButton.addEventListener('click', function () {
        dropdownMenu.classList.toggle('show');
    });

    // Close the dropdown if the user clicks outside of it
    document.addEventListener('click', function (e) {
        if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});

