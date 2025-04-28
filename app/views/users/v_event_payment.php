<?php require APPROOT . '/views/inc/event_header.php'; ?>

<div class="payment-container">
    <div class="payment-content">
        <div class="payment-header">
            <h1>Complete Your Booking</h1>
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
        </div>

        <form id="payment-form" action="<?php echo URLROOT; ?>/events/processPayment/<?php echo $data['event']->event_id; ?>" method="POST">
            <input type="hidden" name="ticket_type_id" value="<?php echo $data['ticket']->ticket_type_id; ?>">
            <input type="hidden" name="quantity" value="<?php echo $data['quantity']; ?>">
            <input type="hidden" name="total_price" value="<?php echo $data['total_price']; ?>">

            <div class="form-group">
                <label for="card-number">Card Number</label>
                <input type="text" id="card-number" class="form-control" placeholder="4242 4242 4242 4242" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="card-expiry">Expiry Date</label>
                    <input type="text" id="card-expiry" class="form-control" placeholder="MM/YY" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="card-cvc">CVC</label>
                    <input type="text" id="card-cvc" class="form-control" placeholder="123" required>
                </div>
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

.payment-content {
    background: rgba(255, 255, 255, 0.05);
    padding: 2rem;
    border-radius: 8px;
}

.payment-header {
    text-align: center;
    margin-bottom: 2rem;
}

.payment-header h1 {
    color: var(--text-primary);
    font-size: 2rem;
    margin-bottom: 1rem;
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
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    color: var(--text-primary);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
}

.form-row {
    display: flex;
    gap: 1rem;
}

.form-row .form-group {
    flex: 1;
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
</style>

<?php require APPROOT . '/views/inc/event_footer.php'; ?> 