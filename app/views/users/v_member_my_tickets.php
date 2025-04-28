<?php require APPROOT . '/views/inc/header3.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="md_dashboard-container">
    <!-- Sidebar -->
    <aside class="md_sidebar">
        <div class="md_profile-section">
        <?php $profilePic = !empty($member_info['profile_pic']) ? $member_info['profile_pic'] : 'default-avatar.png';
            ?>
            <img 
            src="<?php echo URLROOT; ?>/public/uploads/img/<?php echo $profilePic; ?>" 
            alt="Profile Photo" 
            class="profile-avatar"
            style="width:90px;height:90px;border-radius:50%;object-fit:cover;margin-bottom:16px;">

        
            <h2><?php echo $data['member_info']['username']; ?></h2>
            <p><?php echo $data['member_info']['email']; ?></p>
        </div>
        <nav class="md_sidebar-nav">
            <ul>
                <li><a href="<?php echo URLROOT; ?>/users/dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="md_active"><a href="<?php echo URLROOT; ?>/my_tickets/mytickets"><i class="fa fa-ticket-alt"></i> My Tickets</a></li>
                <li><a href="<?php echo URLROOT; ?>/Member_Purchases"><i class="fa fa-shopping-cart"></i> My Purchases</a></li>
                <li><a href="<?php echo URLROOT; ?>/music_library/musiclibrary"><i class="fa fa-music"></i> Music Library</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="tickets-container">
        <header class="tickets-header">
            <h1>My Tickets</h1>
            <p>View and manage your booked and saved events</p>
        </header>

        <!-- Upcoming Events Section -->
        <section class="tickets-section">
            <div class="section-title">
                <h2>Upcoming Events</h2>
                <span class="badge"><?php echo count($data['upcoming_bookings']); ?></span>
            </div>
            <?php if(empty($data['upcoming_bookings'])): ?>
                <div class="empty-state">
                    <i class="fa fa-ticket-alt"></i>
                    <p>No upcoming events found</p>
                </div>
            <?php else: ?>
                <div class="tickets-list">
                    <?php foreach($data['upcoming_bookings'] as $booking): ?>
                        <div class="ticket-card">
                            <div class="ticket-img">
                                <img src="<?php echo URLROOT; ?>/public/uploads/events/<?php echo $booking->image; ?>" 
                                     alt="<?php echo htmlspecialchars($booking->title); ?>">
                            </div>
                            <div class="ticket-info">
                                <h3><?php echo htmlspecialchars($booking->title); ?></h3>
                                <div class="ticket-meta">
                                    <span><i class="fa fa-calendar"></i> <?php echo date('M d, Y', strtotime($booking->event_date)); ?></span>
                                    <span><i class="fa fa-clock"></i> <?php echo date('h:i A', strtotime($booking->event_time)); ?></span>
                                    <span><i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($booking->venue); ?></span>
                                </div>
                                <div class="ticket-details">
                                    <span class="badge <?php echo strtolower($booking->status); ?>">
                                        <?php echo ucfirst($booking->status); ?>
                                    </span>
                                    <span class="ticket-count">
                                        <?php 
                                            $tickets = json_decode($booking->tickets);
                                            $qty = isset($tickets->quantity) ? $tickets->quantity : 0;
echo $qty . ' Ticket' . ($qty > 1 ? 's' : '');
                                        ?>
                                    </span>
                                    <span class="ticket-price">Rs. <?php echo number_format($booking->total_price, 2); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Past Bookings Section -->
        <section class="tickets-section">
            <div class="section-title">
                <h2>Past Bookings</h2>
                <span class="badge"><?php echo count($data['past_bookings']); ?></span>
            </div>
            <?php if(empty($data['past_bookings'])): ?>
                <div class="empty-state">
                    <i class="fa fa-history"></i>
                    <p>No past bookings found</p>
                </div>
            <?php else: ?>
                <div class="tickets-list">
                    <?php foreach($data['past_bookings'] as $booking): ?>
                        <div class="ticket-card past">
                            <div class="ticket-img">
                                <img src="<?php echo URLROOT; ?>/public/uploads/events/<?php echo $booking->image; ?>" 
                                    alt="<?php echo htmlspecialchars($booking->title); ?>">
                            </div>
                            <div class="ticket-info">
                                <h3><?php echo htmlspecialchars($booking->title); ?></h3>
                                <div class="ticket-meta">
                                    <span><i class="fa fa-calendar"></i> <?php echo date('M d, Y', strtotime($booking->event_date)); ?></span>
                                    <span><i class="fa fa-clock"></i> <?php echo date('h:i A', strtotime($booking->event_time)); ?></span>
                                    <span><i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($booking->venue); ?></span>
                                </div>
                                <div class="ticket-details">
                                    <span class="badge completed">Completed</span>
                                    <span class="ticket-count">
                                    <?php 
                                            $tickets = json_decode($booking->tickets);
                                            $qty = isset($tickets->quantity) ? $tickets->quantity : 0; echo $qty . ' Ticket' . ($qty > 1 ? 's' : '');
                                        ?>
                                    </span>
                                    <span class="ticket-price">Rs. <?php echo number_format($booking->total_price, 2); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>


        <!-- Saved Events Section -->
        <section class="tickets-section">
            <div class="section-title">
                <h2>Saved Events</h2>
                <span class="badge"><?php echo count($data['saved_events']); ?></span>
            </div>
            <?php if(empty($data['saved_events'])): ?>
                <div class="empty-state">
                    <i class="fa fa-heart"></i>
                    <p>No saved events found</p>
                </div>
            <?php else: ?>
                <div class="tickets-list">
                    <?php foreach($data['saved_events'] as $event): ?>
                        <div class="ticket-card saved">
                            <div class="ticket-img">
                                <img src="<?php echo URLROOT; ?>/public/uploads/events/<?php echo $event->image; ?>" 
                                     alt="<?php echo htmlspecialchars($event->title); ?>">
                            </div>
                            <div class="ticket-info">
                                <h3><?php echo htmlspecialchars($event->title); ?></h3>
                                <div class="ticket-meta">
                                    <span><i class="fa fa-calendar"></i> <?php echo date('M d, Y', strtotime($event->event_date)); ?></span>
                                    <span><i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($event->venue); ?></span>
                                </div>
                            </div>
                            <div class="ticket-actions">
                                <button class="unsave-btn" data-event-id="<?php echo $event->event_id; ?>">
                                    <i class="fa fa-heart"></i> Unsave
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

</body>
<script>
document.querySelectorAll('.unsave-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const eventId = this.dataset.eventId;
        if(confirm('Are you sure you want to unsave this event?')) {
            fetch(`<?php echo URLROOT; ?>/events/unsave/${eventId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    this.closest('.ticket-card').remove();
                }
            });
        }
    });
});
</script>

</html>


<?php require APPROOT . '/views/inc/footer.php'; ?>

