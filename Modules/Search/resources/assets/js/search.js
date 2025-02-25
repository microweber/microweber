/**
 * Search Module JavaScript
 */

// Initialize search functionality
document.addEventListener('DOMContentLoaded', function() {
    // This is a placeholder for any additional JavaScript functionality
    // Most of the search functionality is now handled by Livewire
    
    // Support for URL hash parameters for backward compatibility
    const urlParams = new URLSearchParams(window.location.search);
    const keyword = urlParams.get('keyword');
    
    if (keyword) {
        // If there's a keyword in the URL, we can dispatch an event that Livewire can listen for
        document.dispatchEvent(new CustomEvent('search:keyword', {
            detail: { keyword: keyword }
        }));
    }
});
