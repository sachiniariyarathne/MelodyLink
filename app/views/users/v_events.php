<?php require APPROOT . '/views/inc/header6.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>

<head>
    <meta charset="UTF-8">
    <title>Events | MelodyLink</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/events_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <section class="events-section">
        <!-- Search and Filters -->
        <div class="events-controls">
            <form action="" method="GET" class="filter-form">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search events..." class="search-input" 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                
                <div class="filter-container">
                    <div class="filter-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="venue" class="filter-select">
                            <option value="">All Locations</option>
                            <option value="Colombo" <?php echo (isset($_GET['venue']) && $_GET['venue'] == 'Colombo') ? 'selected' : ''; ?>>Colombo</option>
                            <option value="Kandy" <?php echo (isset($_GET['venue']) && $_GET['venue'] == 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
                            <option value="Galle" <?php echo (isset($_GET['venue']) && $_GET['venue'] == 'Galle') ? 'selected' : ''; ?>>Galle</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" name="event_date" class="date-picker" 
                               value="<?php echo isset($_GET['event_date']) ? htmlspecialchars($_GET['event_date']) : ''; ?>">
                    </div>
                    <div class="filter-group">
                        <i class="fas fa-building"></i>
                        <select name="event_type" class="filter-select">
                            <option value="">All Event Types</option>
                            <option value="indoor" <?php echo (isset($_GET['event_type']) && $_GET['event_type'] == 'indoor') ? 'selected' : ''; ?>>Indoor</option>
                            <option value="outdoor" <?php echo (isset($_GET['event_type']) && $_GET['event_type'] == 'outdoor') ? 'selected' : ''; ?>>Outdoor</option>
                        </select>
                    </div>
                    <button type="submit" class="filter-button">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <?php if(!empty($_GET)): ?>
                        <a href="<?php echo URLROOT; ?>/events" class="reset-button">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Events Grid -->
        <?php if (empty($data['events'])): ?>
            <div class="events-empty">
                <i class="fas fa-calendar-times"></i>
                <h2>No Events Found</h2>
                <p>Try adjusting your filters or check back later!</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach($data['events'] as $event): ?>
                    <div class="event-card">
                        <div class="card-image" style="background-image: url('<?php echo URLROOT; ?>/public/uploads/<?php echo isset($event->image) ? $event->image : 'default-event.jpg'; ?>')">
                            <span class="event-badge">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo isset($event->event_date) ? date('M d', strtotime($event->event_date)) : 'TBD'; ?>
                            </span>
                            <!-- <?php if(isset($event->event_type)): ?>
                                <span class="event-type-badge <?php echo $event->event_type; ?>">
                                    <i class="fas fa-<?php echo $event->event_type == 'indoor' ? 'building' : 'tree'; ?>"></i>
                                    <?php echo ucfirst($event->event_type); ?>
                                </span>
                            <?php endif; ?> -->
                        </div>
                        
                        <div class="card-content">
                            <h3 class="event-title">
                                <i class="fas fa-microphone-alt"></i>
                                <?php echo htmlspecialchars($event->title); ?>
                            </h3>
                            
                            <div class="event-meta">
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($event->venue ?? 'Location TBD'); ?>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <?php echo isset($event->event_time) ? date('g:i A', strtotime($event->event_time)) : 'Time TBD'; ?>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <a href="<?php echo URLROOT; ?>/events/details/<?php echo $event->event_id; ?>" class="details-btn">
                                    View Details <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="events-pagination">
                <button class="pagination-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        <?php endif; ?>

        <!-- CTA Section -->
        <div class="events-cta">
            <h2><i class="fas fa-headphones-alt"></i> Ready for Your Next Music Experience?</h2>
            <p>Get started today and be part of the largest music event community</p>
                <a href="<?php echo URLROOT; ?>/users/register" class="cta-button">Sign Up Now<i class="fas fa-plus-circle"></i></a>
        </div>
    </section>
</body>

<?php require APPROOT . '/views/inc/footer.php'; ?> 