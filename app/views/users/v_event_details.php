<?php require APPROOT . '/views/inc/event_header.php'; ?>

<div class="event-details-page">
    <div class="event-details-container">
        <div class="event-details-content">
            <div class="event-image">
                <img src="<?php echo URLROOT . '/public/' . $data['event']->image; ?>" alt="<?php echo $data['event']->title; ?>">
            </div>
            
            <div class="event-info">
                <div class="event-header">
                    <h1><?php echo $data['event']->title; ?></h1>
                    <div class="event-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo date('F j, Y', strtotime($data['event']->event_date)); ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span><?php echo date('h:i A', strtotime($data['event']->event_time)); ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $data['event']->venue; ?></span>
                        </div>
                    </div>
                </div>

                <div class="event-description">
                    <h2>About the Event</h2>
                    <p><?php echo $data['event']->description; ?></p>
                </div>

                <div class="ticket-types-section">
                    <h2>Available Tickets</h2>
                    <div class="ticket-types-grid">
                        <?php if(isset($data['ticket_types']) && !empty($data['ticket_types'])): ?>
                            <?php foreach($data['ticket_types'] as $ticket): ?>
                                <div class="ticket-type-card">
                                    <h3><?php echo $ticket->name; ?></h3>
                                    <div class="ticket-price">Rs.<?php echo number_format($ticket->price, 2); ?></div>
                                    <div class="ticket-availability">
                                        <?php if($ticket->available_quantity > 0): ?>
                                            <span class="available"><?php echo $ticket->available_quantity; ?> tickets available</span>
                                        <?php else: ?>
                                            <span class="sold-out">Sold Out</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-tickets">
                                <p>No tickets available at the moment.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="event-actions">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo URLROOT; ?>/events/book/<?php echo $data['event']->event_id; ?>" class="btn-book">Book Tickets</a>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/users/login" class="btn-book">Login to Book Tickets</a>
                    <?php endif; ?>
                    <a href="<?php echo URLROOT; ?>/events" class="btn-back">Back to Events</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/event_footer.php'; ?>
