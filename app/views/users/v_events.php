<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="events-container">
    <div class="events-header">
        <h1>Upcoming Events</h1>
        <p>Discover and book tickets for amazing music events</p>
    </div>

    <div class="events-grid">
        <?php if(isset($data['events']) && !empty($data['events'])): ?>
            <?php foreach($data['events'] as $event): ?>
                <div class="event-card">
                    <div class="event-image">
                        <img src="<?php echo URLROOT . '/public/img/events/' . $event->image; ?>" alt="<?php echo $event->title; ?>">
                    </div>
                    <div class="event-details">
                        <h3><?php echo $event->title; ?></h3>
                        <div class="event-info">
                            <span class="date">
                                <i class="fas fa-calendar"></i>
                                <?php echo date('M d, Y', strtotime($event->event_date)); ?>
                            </span>
                            <span class="time">
                                <i class="fas fa-clock"></i>
                                <?php echo date('h:i A', strtotime($event->event_time)); ?>
                            </span>
                            <div class="ticket-types">
                                <?php foreach($event->ticket_types as $ticket): ?>
                                    <span class="ticket-type">
                                        <i class="fas fa-ticket-alt"></i>
                                        <?php echo $ticket->name; ?>: Rs.<?php echo number_format($ticket->price, 2); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <p class="event-description"><?php echo substr($event->description, 0, 100) . '...'; ?></p>
                        <div class="event-actions">
                            <a href="<?php echo URLROOT; ?>/events/details/<?php echo $event->id; ?>" class="btn-view">View Details</a>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a href="<?php echo URLROOT; ?>/events/book/<?php echo $event->id; ?>" class="btn-book">Book Now</a>
                            <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/users/login" class="btn-book">Login to Book</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-events">
                <p>No upcoming events at the moment. Please check back later!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 