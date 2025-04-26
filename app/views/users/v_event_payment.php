<?php require APPROOT . '/views/inc/event_header.php'; ?>

<div class="payment-container">
    <div class="payment-form">
        <h2>Payment Details</h2>
        <div class="booking-summary">
            <h3>Booking Summary</h3>
            <div class="summary-item">
                <span>Event:</span>
                <span><?php echo $data['event']->title; ?></span>
            </div>
            <div class="summary-item">
                <span>Ticket Type:</span>
                <span><?php echo $data['ticket']->name; ?></span>
            </div>
            <div class="summary-item">
                <span>Quantity:</span>
                <span><?php echo $data['quantity']; ?></span>
            </div>
            <div class="summary-item total">
                <span>Total Amount:</span>
                <span>Rs.<?php echo number_format($data['total_price'], 2); ?></span>
            </div>
        </div>

        <form id="payment-form">
            <input type="hidden" name="ticket_type_id" value="<?php echo $data['ticket']->id; ?>">
            <input type="hidden" name="quantity" value="<?php echo $data['quantity']; ?>">
            <input type="hidden" name="total_price" value="<?php echo $data['total_price']; ?>">
            <input type="hidden" name="payment_intent_id" id="payment-intent-id">

            <div class="form-group">
                <label for="card-element">Card Details</label>
                <div id="card-element" class="form-control">
                    <!-- Stripe Card Element will be inserted here -->
                </div>
                <div id="card-errors" class="invalid-feedback" role="alert"></div>
            </div>

            <div class="form-group">
                <label for="card-holder-name">Card Holder Name</label>
                <input type="text" id="card-holder-name" class="form-control" required>
            </div>

            <button type="submit" class="btn-pay" id="submit-button">
                <span id="button-text">Pay Now</span>
                <div id="spinner" class="spinner hidden"></div>
            </button>
        </form>
    </div>
</div>

<style>
.payment-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: var(--bg-primary);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.payment-form {
    background: rgba(255, 255, 255, 0.05);
    padding: 2rem;
    border-radius: 8px;
}

.payment-form h2 {
    color: var(--text-primary);
    margin-bottom: 2rem;
    text-align: center;
}

.booking-summary {
    background: rgba(0, 0, 0, 0.2);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.booking-summary h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.summary-item.total {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    font-weight: bold;
    color: var(--text-primary);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    color: var(--text-primary);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
}

.btn-pay {
    width: 100%;
    padding: 1rem;
    background: var(--accent-primary);
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.3s ease;
    position: relative;
}

.btn-pay:hover {
    background: var(--accent-secondary);
}

.btn-pay:disabled {
    background: var(--accent-secondary);
    cursor: not-allowed;
}

.spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
}

.hidden {
    display: none;
}

@keyframes spin {
    to { transform: translateY(-50%) rotate(360deg); }
}

#card-element {
    padding: 10px;
    background: #ffffff;
    border-radius: 4px;
    border: 1px solid #ccc;
    min-height: 40px;
    display: block;
}

.StripeElement {
    background-color: white;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid #ccc;
    box-shadow: 0 1px 3px 0 #e6ebf1;
    transition: box-shadow 150ms ease;
    width: 100%;
    display: block;
}

.StripeElement--focus {
    box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
    border-color: #fa755a;
}

.StripeElement--webkit-autofill {
    background-color: #fefde5 !important;
}

#card-errors {
    color: #fa755a;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    display: block;
}
</style>

<script src="https://js.stripe.com/v3/"></script>
<script>
// Initialize Stripe
const stripe = Stripe('pk_test_51Qe7HeA97EM7XCbcUIwrluRAjLN0fcIo74r7nIyv2b8hu6cN6AS6gOrE2P5OL5ZERvw4mrSQRgnfsI5xTXDdmxnC00wTQJE4bu');
const elements = stripe.elements();

// Create and mount the card element
const card = elements.create('card', {
    style: {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    }
});

// Mount the card element
card.mount('#card-element');

// Handle real-time validation errors
card.addEventListener('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Create payment intent when page loads
let paymentIntentId = null;
fetch('<?php echo URLROOT; ?>/events/createPaymentIntent', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        amount: <?php echo $data['total_price']; ?>,
        ticket_type_id: <?php echo $data['ticket']->ticket_type_id; ?>,
        quantity: <?php echo $data['quantity']; ?>
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        paymentIntentId = data.paymentIntentId;
        document.getElementById('payment-intent-id').value = paymentIntentId;
    } else {
        throw new Error(data.error);
    }
})
.catch(error => {
    console.error('Error:', error);
    showError('Failed to initialize payment. Please try again.');
});

// Handle form submission
const form = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-button');
const buttonText = document.getElementById('button-text');
const spinner = document.getElementById('spinner');

form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    if (!paymentIntentId) {
        showError('Payment initialization failed. Please refresh the page.');
        return;
    }
    
    submitButton.disabled = true;
    buttonText.textContent = 'Processing...';
    spinner.classList.remove('hidden');
    
    stripe.confirmCardPayment(paymentIntentId, {
        payment_method: {
            card: card,
            billing_details: {
                name: document.getElementById('card-holder-name').value
            }
        }
    })
    .then(function(result) {
        if (result.error) {
            showError(result.error.message);
            submitButton.disabled = false;
            buttonText.textContent = 'Pay Now';
            spinner.classList.add('hidden');
        } else {
            // Payment successful
            form.submit();
        }
    });
});

function showError(message) {
    const errorElement = document.getElementById('card-errors');
    errorElement.textContent = message;
}
</script>

<?php require APPROOT . '/views/inc/event_footer.php'; ?> 