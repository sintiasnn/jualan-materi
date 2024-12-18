document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form');
    
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const response = await fetch('/login', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (response.status === 429) {
                // Maximum devices reached
                if (confirm(data.message + '\nClick OK to logout from oldest device and continue.')) {
                    // Force logout oldest session
                    await fetch('/sessions/force-logout', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    // Retry login
                    loginForm.submit();
                }
            } else if (!response.ok) {
                // Handle other errors
                throw new Error(data.message || 'Login failed');
            } else {
                // Successful login
                window.location.href = '/dashboard';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});