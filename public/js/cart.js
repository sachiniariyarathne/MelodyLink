// cart.js
document.addEventListener('DOMContentLoaded', function() {
    // Get initial cart count
    const cartCountElement = document.getElementById('cartCount');
    let currentCount = parseInt(cartCountElement?.textContent) || 0;

    // Function to update cart count
    function updateCartCount(newCount) {
        if (cartCountElement) {
            cartCountElement.textContent = newCount;
            cartCountElement.classList.add('pulse');
            setTimeout(() => {
                cartCountElement.classList.remove('pulse');
            }, 200);
        }
    }

    // Function to add item to cart
    window.addToCart = async function(merchId) {
        if (!merchId) {
            showNotification('Invalid product selected.', 'error');
            return;
        }

        const button = document.querySelector(`button[data-merch-id="${merchId}"]`);
        if (button) {
            button.disabled = true;
        }

        try {
            // Create the request payload
            const payload = JSON.stringify({
                merchId: merchId
            });

            // Log the payload for debugging
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

            // Log the raw response for debugging
            const rawResponse = await response.text();
            console.log('Raw response:', rawResponse);

            // Try to parse the response as JSON
            let result;
            try {
                result = JSON.parse(rawResponse);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                throw new Error('Invalid JSON response from server');
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

    // Function to show notification
    function showNotification(message, type) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => {
            notification.remove();
        });

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;

        // Style the notification
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

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Add CSS animations if they don't exist
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

// Function to toggle cart visibility
function toggleCart() {
    window.location.href = `${URLROOT}/cart`;
}