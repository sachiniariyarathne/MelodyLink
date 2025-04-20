document.addEventListener('DOMContentLoaded', function() {
    const cartCountElement = document.getElementById('cartCount');
    let currentCount = parseInt(cartCountElement?.textContent) || 0;

    function updateCartCount(newCount) {
        if (cartCountElement) {
            cartCountElement.textContent = newCount;
            cartCountElement.classList.add('pulse');
            setTimeout(() => {
                cartCountElement.classList.remove('pulse');
            }, 200);
        }
    }

    // Test functions for debugging
    window.testEndpoint = async function() {
        try {
            const response = await fetch(`${URLROOT}/merchandise/debug`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            const rawResponse = await response.text();
            console.log('Debug raw response:', rawResponse);
            
            if (rawResponse.trim().startsWith('{')) {
                const result = JSON.parse(rawResponse);
                console.log('Debug parsed response:', result);
                alert('Debug endpoint working: ' + result.message);
            } else {
                console.error('Debug endpoint returned non-JSON:', rawResponse);
                alert('Debug endpoint error: Non-JSON response');
            }
        } catch (error) {
            console.error('Debug error:', error);
            alert('Debug error: ' + error.message);
        }
    };

    // Test the add to cart with minimal code
    window.testAddToCart = async function(merchId) {
        try {
            const payload = JSON.stringify({
                merch_id: merchId,
                quantity: 1
            });
            
            console.log('Test payload:', payload);
            
            const response = await fetch(`${URLROOT}/merchandise/testAdd`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: payload
            });
            
            const rawResponse = await response.text();
            console.log('Test raw response:', rawResponse);
            
            if (rawResponse.trim().startsWith('{')) {
                const result = JSON.parse(rawResponse);
                console.log('Test parsed response:', result);
                alert('Test successful: ' + JSON.stringify(result));
            } else {
                console.error('Test endpoint returned non-JSON:', rawResponse);
                alert('Test error: Non-JSON response');
            }
        } catch (error) {
            console.error('Test error:', error);
            alert('Test error: ' + error.message);
        }
    };

    // Fixed addToCart function
    window.addToCart = async function(merchId) {
        console.log('addToCart called with merchId:', merchId);
        
        if (!merchId) {
            showNotification('Invalid product selected.', 'error');
            return;
        }

        const button = document.querySelector(`button[data-merch-id="${merchId}"]`);
        if (button) {
            button.disabled = true;
        }

        try {
            // Convert to number if needed
            const merchIdValue = Number(merchId) || merchId;
            
            const payload = JSON.stringify({
                merch_id: merchIdValue, // This is the key for the PHP controller
                quantity: 1
            });

            console.log('Sending payload:', payload);

            const response = await fetch(`${URLROOT}/merchandise/addToCart`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: payload
            });

            const rawResponse = await response.text();
            console.log('Raw response:', rawResponse);

            let result;
            // Check if the response is actually JSON before trying to parse it
            if (rawResponse.trim().startsWith('{') || rawResponse.trim().startsWith('[')) {
                result = JSON.parse(rawResponse);
            } else {
                console.error('Server returned non-JSON response:', rawResponse);
                throw new Error('Server returned invalid response format');
            }
            
            if (response.ok && result.success) {
                updateCartCount(result.cartCount);
                showNotification('Item added to cart successfully!', 'success');
            } else {
                throw new Error(result.message || 'Failed to add item to cart.');
            }
        } catch (error) {
            console.error('Error details:', error);
            showNotification(error.message || 'Error occurred while adding to cart.', 'error');
        } finally {
            if (button) {
                button.disabled = false;
            }
        }
    };

    function showNotification(message, type) {
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;

        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '10px 20px',
            borderRadius: '4px',
            zIndex: '1000',
            animation: 'slideIn 0.3s ease-out',
            backgroundColor: type === 'success' ? '#4CAF50' : '#f44336',
            color: 'white',
            boxShadow: '0 2px 5px rgba(0,0,0,0.2)'
        });

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    if (!document.querySelector('#cart-animations')) {
        const style = document.createElement('style');
        style.id = 'cart-animations';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }

            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }

            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }

            .pulse {
                animation: pulse 0.2s ease-in-out;
            }

            .notification {
                transition: all 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    }
});

function toggleCart() {
    window.location.href = `${URLROOT}/cart`;
}