<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<div class="event-view-container">
    <div class="event-header">
        <div class="header-content">
            <h1><?php echo $data['event']->title; ?></h1>
            <div class="event-actions">
                <a href="<?php echo URLROOT; ?>/eventmanagement/edit/<?php echo $data['event']->event_id; ?>" class="btn btn-edit">
                    <i class="fas fa-edit"></i> Edit Event
                </a>
                <button class="btn btn-delete" onclick="confirmDelete(<?php echo $data['event']->event_id; ?>)">
                    <i class="fas fa-trash"></i> Delete Event
                </button>
            </div>
        </div>
        <div class="event-status <?php echo $data['event']->status; ?>">
            <?php echo ucfirst($data['event']->status); ?>
        </div>
    </div>

    <div class="event-content">
        <div class="event-main">
            <div class="event-image">
                <img src="<?php echo URLROOT; ?>/<?php echo $data['event']->image; ?>" alt="<?php echo $data['event']->title; ?>">
            </div>
            
            <div class="event-details">
                <div class="detail-group">
                    <h3>Event Details</h3>
                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span>Date: <?php echo date('F d, Y', strtotime($data['event']->event_date)); ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock"></i>
                        <span>Time: <?php echo date('h:i A', strtotime($data['event']->event_time)); ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Venue: <?php echo $data['event']->venue; ?></span>
                    </div>
                </div>

                <div class="description">
                    <h3>Description</h3>
                    <p><?php echo $data['event']->description; ?></p>
                </div>
            </div>

            <div class="ticket-types">
                <h3>Ticket Types</h3>
                <div class="ticket-grid">
                    <?php foreach($data['ticket_types'] as $ticket): ?>
                        <div class="ticket-card">
                            <div class="ticket-header">
                                <h4><?php echo $ticket->name; ?></h4>
                                <div class="price">Rs. <?php echo number_format($ticket->price, 2); ?></div>
                            </div>
                            <div class="ticket-info">
                                <div class="quantity">
                                    <span>Available:</span>
                                    <strong><?php echo $ticket->quantity_available; ?></strong>
                                </div>
                                <div class="sold">
                                    <span>Sold:</span>
                                    <strong><?php echo $ticket->quantity_sold; ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!empty($data['bookings'])): ?>
            <div class="bookings-section">
                <h3>Recent Bookings</h3>
                <div class="table-responsive">
                    <table class="bookings-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Ticket Type</th>
                                <th>Quantity</th>
                                <th>Total Amount</th>
                                <th>Booking Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['bookings'] as $booking): ?>
                                <tr>
                                    <td><?php echo $booking->customer_name; ?></td>
                                    <td><?php echo $booking->ticket_type; ?></td>
                                    <td><?php echo $booking->quantity; ?></td>
                                    <td>Rs. <?php echo number_format($booking->total_amount, 2); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($booking->booking_date)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.event-view-container {
    padding: 2rem;
    background: #1a1625;
    min-height: 100vh;
}

.event-header {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.event-header h1 {
    color: #fff;
    margin: 0;
    font-size: 2rem;
}

.event-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-edit {
    background: #2196f3;
    color: #fff;
    text-decoration: none;
}

.btn-edit:hover {
    background: #1976d2;
}

.btn-delete {
    background: #f44336;
    color: #fff;
}

.btn-delete:hover {
    background: #d32f2f;
}

.event-status {
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-weight: 500;
}

.event-status.active {
    background: rgba(76, 175, 80, 0.2);
    color: #4caf50;
}

.event-status.ended {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
}

.event-content {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 2rem;
    backdrop-filter: blur(10px);
}

.event-image {
    width: 100%;
    height: 400px;
    border-radius: 0.5rem;
    overflow: hidden;
    margin-bottom: 2rem;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.event-details {
    color: #fff;
}

.detail-group {
    margin-bottom: 2rem;
}

.detail-group h3 {
    margin-bottom: 1rem;
    color: #fff;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
}

.description {
    margin-bottom: 2rem;
}

.description p {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
}

.ticket-types {
    margin-bottom: 2rem;
}

.ticket-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.ticket-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.5rem;
    padding: 1.5rem;
}

.ticket-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.ticket-header h4 {
    color: #fff;
    margin: 0;
}

.price {
    color: #4caf50;
    font-weight: 600;
}

.ticket-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.quantity, .sold {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.quantity span, .sold span {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.875rem;
}

.quantity strong, .sold strong {
    color: #fff;
}

.bookings-section {
    margin-top: 2rem;
}

.table-responsive {
    overflow-x: auto;
}

.bookings-table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
}

.bookings-table th,
.bookings-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.bookings-table th {
    background: rgba(255, 255, 255, 0.05);
    font-weight: 500;
}

@media (max-width: 768px) {
    .event-header {
        flex-direction: column;
        gap: 1rem;
    }

    .header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .event-actions {
        justify-content: center;
    }

    .event-image {
        height: 300px;
    }
}
</style>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this event?')) {
        // Create a form dynamically
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo URLROOT; ?>/eventmanagement/delete/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?> 