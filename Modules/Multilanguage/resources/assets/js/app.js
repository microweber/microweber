console.log('Hello from app.js');

// Multilanguage module functionality
document.addEventListener('DOMContentLoaded', function() {
    // This will be loaded when the module is used
    console.log('Multilanguage module loaded');
});

// Function to test geolocation API
window.testGeoApi = function() {
    const clientDetails = {};
    
    fetch('/api/multilanguage/geolocaiton_test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(clientDetails)
    })
    .then(response => response.text())
    .then(data => {
        // Display results in a dialog
        alert('Geo API Results:\n' + data);
    })
    .catch(error => {
        console.error('Error testing geo API:', error);
    });
};
