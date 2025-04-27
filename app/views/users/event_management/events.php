<?php require APPROOT . '/views/users/event_management/header.php'; ?>


<div class="events-container">
    <div class="events-header">
        <h1>Events Management</h1>
        <a href="<?php echo URLROOT; ?>/eventmanagement/create" class="btn-create">
            <i class="fas fa-plus"></i> Create New Event
        </a>
    </div>

    <?php flash('event_message'); ?>

    <div class="events-filters">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search events...">
        </div>
        <div class="filter-actions">
            <button class="btn-filter">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn-sort">
                <i class="fas fa-sort"></i> Sort
            </button>
        </div>
    </div>

    <div class="events-grid">
        <?php foreach($data['events'] as $event): ?>
            <div class="event-card">
                <div class="event-image">
                    <img src="<?php echo URLROOT; ?>/public/<?php echo $event->image; ?>" alt="<?php echo $event->title; ?>">
                    <div class="event-status <?php echo $event->status; ?>">
                        <?php echo ucfirst($event->status); ?>
                    </div>
                </div>
                <div class="event-content">
                    <h3><?php echo $event->title; ?></h3>
                    <div class="event-details">
                        <div class="detail">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo date('M d, Y', strtotime($event->event_date)); ?></span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-clock"></i>
                            <span><?php echo date('h:i A', strtotime($event->event_time)); ?></span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $event->venue; ?></span>
                        </div>
                    </div>
                    <div class="event-stats">
                        <div class="stat">
                            <i class="fas fa-ticket-alt"></i>
                            <span><?php echo $event->total_bookings; ?> Bookings</span>
                        </div>
                        <div class="stat">
                            <i class="fas fa-rupee-sign"></i>
                            <span><?php echo number_format($event->total_income, 2); ?></span>
                        </div>
                    </div>
                    <div class="event-actions">
                        <a href="<?php echo URLROOT; ?>/eventmanagement/viewEvent/<?php echo $event->event_id; ?>" class="btn-action view">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?php echo URLROOT; ?>/eventmanagement/edit/<?php echo $event->event_id; ?>" class="btn-action edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn-action delete" onclick="confirmDelete(<?php echo $event->event_id; ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.events-container {
    padding: 2rem;
    background: #1a1625;
    min-height: 100vh;
}

.events-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.events-header h1 {
    color: #fff;
    font-size: 1.75rem;
    margin: 0;
}

.btn-create {
    background: #ff4d94;
    color: #fff;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.3s;
}

.btn-create:hover {
    background: #ff1a75;
}

.events-filters {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: none;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.5);
}

.filter-actions {
    display: flex;
    gap: 1rem;
}

.btn-filter, .btn-sort {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #fff;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.event-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    overflow: hidden;
    transition: transform 0.3s;
}

.event-card:hover {
    transform: translateY(-5px);
}

.event-image {
    position: relative;
    height: 200px;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.event-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
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
    padding: 1.5rem;
}

.event-content h3 {
    color: #fff;
    margin: 0 0 1rem 0;
    font-size: 1.25rem;
}

.event-details {
    display: grid;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.7);
}

.event-stats {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 1rem;
}

.stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
}

.event-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-action {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-action.view {
    background: rgba(255, 255, 255, 0.1);
}

.btn-action.edit {
    background: #2196f3;
}

.btn-action.delete {
    background: #f44336;
}

.btn-action:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .events-container {
        padding: 1rem;
    }
    
    .events-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .events-filters {
        flex-direction: column;
        gap: 1rem;
    }
    
    .search-box {
        max-width: 100%;
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