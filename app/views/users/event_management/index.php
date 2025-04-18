<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="event-management-container">
    <div class="dashboard-header">
        <h1>Event Management Dashboard</h1>
        <a href="<?php echo URLROOT; ?>/eventmanagement/create" class="btn-create">Create New Event</a>
    </div>

    <?php flash('event_message'); ?>

    <div class="events-grid">
        <?php foreach($data['events'] as $event): ?>
            <div class="event-card">
                <div class="event-image">
                    <img src="<?php echo URLROOT . '/public/img/events/' . $event->image; ?>" alt="<?php echo $event->title; ?>">
                </div>
                <div class="event-info">
                    <h2><?php echo $event->title; ?></h2>
                    <div class="event-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo date('F j, Y', strtotime($event->event_date)); ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span><?php echo date('h:i A', strtotime($event->event_time)); ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $event->venue; ?></span>
                        </div>
                    </div>
                    <div class="event-stats">
                        <div class="stat-item">
                            <i class="fas fa-ticket-alt"></i>
                            <span><?php echo $event->total_bookings; ?> Bookings</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Rs.<?php echo number_format($event->total_income, 2); ?></span>
                        </div>
                    </div>
                    <div class="event-actions">
                        <a href="<?php echo URLROOT; ?>/eventmanagement/bookings/<?php echo $event->id; ?>" class="btn-action">
                            <i class="fas fa-list"></i> View Bookings
                        </a>
                        <a href="<?php echo URLROOT; ?>/eventmanagement/income/<?php echo $event->id; ?>" class="btn-action">
                            <i class="fas fa-chart-line"></i> View Income
                        </a>
                        <a href="<?php echo URLROOT; ?>/eventmanagement/edit/<?php echo $event->id; ?>" class="btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="<?php echo URLROOT; ?>/eventmanagement/delete/<?php echo $event->id; ?>" method="POST" class="delete-form">
                            <button type="submit" class="btn-action delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 