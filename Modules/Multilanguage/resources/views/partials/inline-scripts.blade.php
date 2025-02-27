<script>
// Function to test geolocation API
window.testGeoApi = function() {
    const clientDetails = {};

    // Get CSRF token
    let csrfToken = '';
    const tokenElement = document.querySelector('meta[name="csrf-token"]');
    if (tokenElement) {
        csrfToken = tokenElement.getAttribute('content');
    } else {
        // Try to find it in a form if meta tag is not available
        const tokenInput = document.querySelector('input[name="_token"]');
        if (tokenInput) {
            csrfToken = tokenInput.value;
        }
    }

    fetch('/api/multilanguage/geolocaiton_test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(clientDetails)
    })
    .then(response => response.json())
    .then(data => {
        // Format the JSON data for better readability
        const formattedData = JSON.stringify(data, null, 2);

        // Create a modal to display the results
        const modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
        modal.style.zIndex = '10000';
        modal.style.display = 'flex';
        modal.style.justifyContent = 'center';
        modal.style.alignItems = 'center';

        const content = document.createElement('div');
        content.style.backgroundColor = 'white';
        content.style.padding = '20px';
        content.style.borderRadius = '5px';
        content.style.maxWidth = '80%';
        content.style.maxHeight = '80%';
        content.style.overflow = 'auto';

        const title = document.createElement('h3');
        title.textContent = 'Geo API Test Results';
        title.style.marginTop = '0';

        const pre = document.createElement('pre');
        pre.textContent = formattedData;
        pre.style.whiteSpace = 'pre-wrap';
        pre.style.wordBreak = 'break-all';

        const closeButton = document.createElement('button');
        closeButton.textContent = 'Close';
        closeButton.style.marginTop = '15px';
        closeButton.style.padding = '5px 15px';
        closeButton.onclick = function() {
            document.body.removeChild(modal);
        };

        content.appendChild(title);
        content.appendChild(pre);
        content.appendChild(closeButton);
        modal.appendChild(content);

        document.body.appendChild(modal);
    })
    .catch(error => {
        console.error('Error testing geo API:', error);
        alert('Error testing Geo API: ' + error.message);
    });
};
</script>
