<?php require APPROOT . '/views/inc/event_header.php'; ?>

<div class="confirmation-container">
    <div class="confirmation-content">
        <div class="confirmation-header">
            <i class="fas fa-check-circle"></i>
            <h1>Booking Confirmation</h1>
            <p>Thank you for booking with MelodyLink!</p>
        </div>

        <div class="confirmation-details">
            <div class="event-info">
                <h2>Event Details</h2>
                <p><strong>Event:</strong> <?php echo $data['event']->title; ?></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($data['event']->event_date)); ?></p>
                <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($data['event']->event_time)); ?></p>
                <p><strong>Venue:</strong> <?php echo $data['event']->venue; ?></p>
            </div>

            <div class="booking-info">
                <h2>Booking Details</h2>
                <p><strong>Ticket Type:</strong> <?php echo $data['ticket']->name; ?></p>
                <p><strong>Quantity:</strong> <?php echo $data['quantity']; ?></p>
                <p><strong>Total Amount:</strong> Rs.<?php echo number_format($data['total_price']); ?></p>
                <p><strong>Booking Reference:</strong> <?php echo $data['booking']->booking_id; ?></p>
            </div>

            <div class="entry-info">
                <h2>Entry Information</h2>
                <p><strong>Secret Code:</strong> <?php echo $data['booking']->secret_code; ?></p>
                <p class="note">Please present this code at the entrance.</p>
            </div>
        </div>

        <div class="confirmation-actions">
            <a href="<?php echo URLROOT; ?>/users/dashboard" class="btn btn-primary">Go to Dashboard</a>
            <a href="<?php echo URLROOT; ?>/events/details/<?php echo $data['event']->event_id; ?>" class="btn btn-secondary">View Event Details</a>
        </div>
    </div>
</div>

<style>
.confirmation-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: var(--bg-primary);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.confirmation-content {
    background: rgba(255, 255, 255, 0.05);
    padding: 2rem;
    border-radius: 8px;
}

.confirmation-header {
    text-align: center;
    margin-bottom: 2rem;
}

.confirmation-header i {
    font-size: 4rem;
    color: #4CAF50;
    margin-bottom: 1rem;
}

.confirmation-header h1 {
    color: var(--text-primary);
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.confirmation-details {
    margin-bottom: 2rem;
}

.event-info, .booking-info, .entry-info {
    background: rgba(0, 0, 0, 0.2);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.event-info h2, .booking-info h2, .entry-info h2 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.event-info p, .booking-info p, .entry-info p {
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.entry-info .note {
    font-style: italic;
    color: var(--text-secondary);
    margin-top: 1rem;
}

.confirmation-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--accent-primary);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-secondary);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
}

@media (max-width: 768px) {
    .confirmation-container {
        margin: 1rem;
        padding: 1rem;
    }

    .confirmation-content {
        padding: 1rem;
    }

    .confirmation-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php require APPROOT . '/views/inc/event_footer.php'; ?> 