<?php require APPROOT . '/views/inc/event_header.php'; ?>

<div class="events-page">
    <div class="events-header">
        <h1>Upcoming Events</h1>
        <p>Discover and book your next music experience</p>
    </div>

    <div class="events-filters">
        <div class="search-bar">
            <input type="text" placeholder="Search events..." class="search-input">
            <button class="filters-btn">
                <i class="fas fa-filter"></i>
                Filters
            </button>
        </div>
        <div class="filter-options">
            <select class="filter-select">
                <option value="">All Categories</option>
                <option value="music">Music</option>
                <option value="concert">Concert</option>
                <option value="festival">Festival</option>
            </select>
            <select class="filter-select">
                <option value="">All Locations</option>
                <option value="newyork">New York</option>
                <option value="chicago">Chicago</option>
                <option value="la">Los Angeles</option>
            </select>
            <input type="date" class="date-picker" placeholder="Select Date">
        </div>
    </div>

    <div class="events-grid">
        <?php if(isset($data['events']) && !empty($data['events'])): ?>
            <?php foreach($data['events'] as $event): ?>
                <div class="event-card">
                    <div class="event-date-badge">
                        <?php echo date('M d', strtotime($event->event_date)); ?>
                    </div>
                    <div class="event-image">
                        <img src="<?php echo URLROOT . '/public/' . $event->image; ?>" alt="<?php echo $event->title; ?>">
                    </div>
                    <div class="event-content">
                        <h3 class="event-title"><?php echo $event->title; ?></h3>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo $event->venue; ?>
                        </div>
                        <div class="event-actions">
                            <a href="<?php echo URLROOT; ?>/events/details/<?php echo $event->event_id; ?>" class="btn-view">View Details</a>
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

    <div class="events-pagination">
        <button class="pagination-btn"><i class="fas fa-chevron-left"></i></button>
        <button class="pagination-btn active">1</button>
        <button class="pagination-btn">2</button>
        <button class="pagination-btn">3</button>
        <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
    </div>

    <div class="cta-section">
        <h2>Ready to Join the Music Revolution?</h2>
        <p>Get started today and be part of the largest music event community</p>
        <a href="<?php echo URLROOT; ?>/users/register" class="btn-signup">Sign Up Now</a>
    </div>
</div>

<?php require APPROOT . '/views/inc/event_footer.php'; ?>