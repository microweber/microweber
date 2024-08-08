console.log('microweber-filament-theme.js');


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




