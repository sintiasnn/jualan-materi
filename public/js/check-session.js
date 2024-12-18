// resources/js/session-check.js
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for AJAX responses
    let originalFetch = window.fetch;
    window.fetch = function() {
        return originalFetch.apply(this, arguments)
            .then(async (response) => {
                if (response.status === 401) {
                    const data = await response.clone().json();
                    if (data.logout) {
                        window.location.href = '/login';
                    }
                }
                return response;
            });
    }
});