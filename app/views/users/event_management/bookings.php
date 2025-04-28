<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<div class="bookings-container">
    <h1>Bookings for <?php echo $data['event']->title; ?></h1>
    
    <?php if (empty($data['bookings'])): ?>
        <p>No bookings found for this event.</p>
    <?php else: ?>
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Secret Code</th>
                    <th>Ticket Types</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['bookings'] as $booking): ?>
                    <tr>
                        <td><?php echo $booking->booking_id; ?></td>
                        <td><?php echo $booking->customer_name; ?></td>
                        <td><?php echo $booking->email; ?></td>
                        <td><?php echo $booking->secret_code; ?></td>
                        <td>
                            <?php if (!empty($booking->tickets)): ?>
                                <ul>
                                    <?php foreach ($booking->tickets as $ticket): ?>
                                        <li><?php echo $ticket->ticket_type; ?>: <?php echo $ticket->quantity; ?> ticket(s)</li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                No tickets
                            <?php endif; ?>
                        </td>
                        <td>Rs.<?php echo number_format($booking->total_price); ?></td>
                        <td><?php echo ucfirst($booking->payment_status); ?></td>
                        <td><?php echo date('F j, Y, g:i A', strtotime($booking->created_at)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <div class="event-actions">
        <a href="<?php echo URLROOT; ?>/eventmanagement/viewEvent/<?php echo $data['event']->event_id; ?>" class="btn btn-secondary">Back to Event</a>
    </div>
</div>

<style>
.bookings-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    background: var(--bg-primary);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.bookings-container h1 {
    color: var(--text-primary);
    margin-bottom: 2rem;
}

.bookings-table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.05);
}

.bookings-table th,
.bookings-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.bookings-table th {
    color: var(--text-primary);
    font-weight: 500;
}

.bookings-table td {
    color: var(--text-secondary);
}

.bookings-table ul {
    margin: 0;
    padding-left: 1.5rem;
}

.event-actions {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
}

@media (max-width: 768px) {
    .bookings-table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?>