<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<div class="event-details-container">
    <div class="event-header">
        <h1><?php echo $data['event']->title; ?></h1>
        <div class="event-status <?php echo strtolower($data['event']->status); ?>">
            <?php echo ucfirst($data['event']->status); ?>
        </div>
    </div>

    <div class="event-info">
        <div class="info-section">
            <h2>Event Information</h2>
            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($data['event']->event_date)); ?></p>
            <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($data['event']->event_time)); ?></p>
            <p><strong>Venue:</strong> <?php echo $data['event']->venue; ?></p>
            <p><strong>Description:</strong> <?php echo $data['event']->description; ?></p>
        </div>

        <div class="stats-section">
            <h2>Event Statistics</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Total Bookings</span>
                    <span class="stat-value"><?php echo $data['event']->total_bookings; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Quantity Sold</span>
                    <span class="stat-value"><?php echo $data['event']->quantity_sold; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Income</span>
                    <span class="stat-value">Rs.<?php echo number_format($data['event']->total_income); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="ticket-types">
        <h2>Ticket Types</h2>
        <div class="ticket-grid">
            <?php foreach($data['ticket_types'] as $ticket): ?>
                <div class="ticket-card">
                    <h3><?php echo $ticket->ticket_type; ?></h3>
                    <div class="ticket-details">
                        <p><strong>Price:</strong> Rs.<?php echo number_format($ticket->price); ?></p>
                        <p><strong>Available:</strong> <?php echo $ticket->available_quantity; ?></p>
                        <p><strong>Sold:</strong> <?php echo $ticket->quantity_sold; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="event-actions">
    <a href="<?php echo URLROOT; ?>/eventmanagement/edit/<?php echo $data['event']->event_id; ?>" class="btn btn-primary">Edit Event</a>
    <a href="<?php echo URLROOT; ?>/eventmanagement/bookings/<?php echo $data['event']->event_id; ?>" class="btn btn-secondary">View Bookings</a>
    <!-- <button onclick="confirmDelete(<?php echo $data['event']->event_id; ?>)" class="btn btn-danger">Delete Event</button> -->
</div>
</div>

<style>
.event-details-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    background: var(--bg-primary);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.event-header h1 {
    color: var(--text-primary);
    font-size: 2rem;
    margin: 0;
}

.event-status {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.event-status.active {
    background: #4CAF50;
    color: white;
}

.event-status.ended {
    background: #f44336;
    color: white;
}

.event-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.info-section, .stats-section {
    background: rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    border-radius: 8px;
}

.info-section h2, .stats-section h2 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.info-section p {
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.stat-item {
    background: rgba(0, 0, 0, 0.2);
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
}

.stat-label {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    display: block;
    color: var(--text-primary);
    font-size: 1.25rem;
    font-weight: 500;
}

.ticket-types {
    margin-bottom: 2rem;
}

.ticket-types h2 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.ticket-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
}

.ticket-card {
    background: rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    border-radius: 8px;
}

.ticket-card h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.ticket-details p {
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.event-actions {
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
    .event-info {
        grid-template-columns: 1fr;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .event-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        text-align: center;
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