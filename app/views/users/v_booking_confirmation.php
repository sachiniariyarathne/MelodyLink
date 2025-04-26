<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="confirmation-container">
    <div class="confirmation-card">
        <div class="confirmation-header">
            <i class="fas fa-check-circle"></i>
            <h1>Booking Confirmed!</h1>
            <p>Thank you for your purchase. Your tickets have been booked successfully.</p>
        </div>

        <div class="confirmation-details">
            <div class="detail-section">
                <h2>Event Details</h2>
                <div class="detail-item">
                    <span class="label">Event:</span>
                    <span class="value"><?php echo $data['event']->title; ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Date:</span>
                    <span class="value"><?php echo date('F j, Y', strtotime($data['event']->event_date)); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Time:</span>
                    <span class="value"><?php echo date('h:i A', strtotime($data['event']->event_time)); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Venue:</span>
                    <span class="value"><?php echo $data['event']->venue; ?></span>
                </div>
            </div>

            <div class="detail-section">
                <h2>Booking Details</h2>
                <div class="detail-item">
                    <span class="label">Ticket Type:</span>
                    <span class="value"><?php echo $data['ticket']->name; ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Quantity:</span>
                    <span class="value"><?php echo $data['quantity']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Total Amount:</span>
                    <span class="value">Rs.<?php echo number_format($data['total_price'], 2); ?></span>
                </div>
                <div class="detail-item">
                    <span class="label">Booking Reference:</span>
                    <span class="value"><?php echo $data['booking']->booking_id; ?></span>
                </div>
            </div>

            <div class="detail-section">
                <h2>Entry Information</h2>
                <div class="secret-code">
                    <span class="label">Your Secret Code:</span>
                    <span class="code"><?php echo $data['booking']->secret_code; ?></span>
                </div>
                <p class="note">Please present this code at the event entrance along with your ID.</p>
            </div>
        </div>

        <div class="confirmation-actions">
            <a href="<?php echo URLROOT; ?>/users/dashboard" class="btn-dashboard">Go to Dashboard</a>
            <a href="<?php echo URLROOT; ?>/events/details/<?php echo $data['event']->id; ?>" class="btn-event">View Event</a>
        </div>
    </div>
</div>

<style>
.confirmation-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
}

.confirmation-card {
    background: var(--bg-primary);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.confirmation-header {
    text-align: center;
    margin-bottom: 2rem;
}

.confirmation-header i {
    font-size: 4rem;
    color: #48bb78;
    margin-bottom: 1rem;
}

.confirmation-header h1 {
    color: var(--text-primary);
    font-size: 2rem;
    margin-bottom: 1rem;
}

.confirmation-header p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.confirmation-details {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.detail-section {
    margin-bottom: 2rem;
}

.detail-section:last-child {
    margin-bottom: 0;
}

.detail-section h2 {
    color: var(--text-primary);
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
}

.detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    color: var(--text-secondary);
}

.detail-item .label {
    font-weight: 500;
}

.detail-item .value {
    color: var(--text-primary);
}

.secret-code {
    text-align: center;
    margin: 2rem 0;
    padding: 1.5rem;
    background: rgba(72, 187, 120, 0.1);
    border-radius: 8px;
}

.secret-code .label {
    display: block;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.secret-code .code {
    font-size: 2rem;
    font-weight: bold;
    color: #48bb78;
    letter-spacing: 0.2em;
}

.note {
    text-align: center;
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-top: 1rem;
}

.confirmation-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn-dashboard, .btn-event {
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-dashboard {
    background: var(--accent-primary);
    color: white;
}

.btn-event {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

.btn-dashboard:hover {
    background: var(--accent-secondary);
}

.btn-event:hover {
    background: rgba(255, 255, 255, 0.2);
}

@media (max-width: 768px) {
    .confirmation-container {
        padding: 1rem;
    }
    
    .confirmation-card {
        padding: 1.5rem;
    }
    
    .confirmation-actions {
        flex-direction: column;
    }
    
    .btn-dashboard, .btn-event {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?> 