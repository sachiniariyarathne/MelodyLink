<?php require APPROOT . '/views/inc/header7.php'; ?> 
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
            <form action="" method="GET" class="filter-form" id="filterForm">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search events..." class="search-input" id="searchInput">
                </div>
                
                <div class="filter-container">
                    <div class="filter-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="venue" class="filter-select" id="venueFilter">
                            <option value="">All Locations</option>
                            <?php 
                            // Get unique venues from events
                            $venues = array_unique(array_column($data['events'], 'venue'));
                            foreach($venues as $venue): 
                                if(!empty($venue)):
                            ?>
                                <option value="<?php echo htmlspecialchars($venue); ?>"><?php echo htmlspecialchars($venue); ?></option>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" name="start_date" class="date-picker" id="startDateFilter" placeholder="Start Date">
                    </div>
                    <div class="filter-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" name="end_date" class="date-picker" id="endDateFilter" placeholder="End Date">
                    </div>
                    <div class="filter-group">
                        <i class="fas fa-sort"></i>
                        <select name="sort" class="filter-select" id="sortFilter">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                        </select>
                    </div>
                    <button type="button" class="filter-button" id="filterButton">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button type="button" class="reset-button" id="resetButton">
                        <i class="fas fa-times"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Events Grid -->
        <div class="events-grid" id="eventsGrid">
            <?php foreach($data['events'] as $event): ?>
                <div class="event-card" 
                     data-title="<?php echo htmlspecialchars($event->title); ?>"
                     data-venue="<?php echo htmlspecialchars($event->venue); ?>"
                     data-date="<?php echo strtotime($event->event_date); ?>">
                    <div class="card-image" style="background-image: url('<?php echo URLROOT; ?>/public/uploads/img/<?php echo isset($event->image) ? $event->image : 'default-event.jpg'; ?>')">
                        <span class="event-badge">
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo isset($event->event_date) ? date('M d', strtotime($event->event_date)) : 'TBD'; ?>
                        </span>
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

        <!-- Empty State -->
        <div class="events-empty" id="noResults" style="display: none;">
            <i class="fas fa-calendar-times"></i>
            <h2>No Events Found</h2>
            <p>Try adjusting your filters or check back later!</p>
        </div>

        <!-- CTA Section -->
        <div class="events-cta">
            <h2><i class="fas fa-headphones-alt"></i> Ready for Your Next Music Experience?</h2>
            <p>Get started today and be part of the largest music event community</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?php echo URLROOT; ?>/users/register" class="btn-signup">Sign Up</a>
            <?php endif; ?>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const venueFilter = document.getElementById('venueFilter');
        const startDateFilter = document.getElementById('startDateFilter');
        const endDateFilter = document.getElementById('endDateFilter');
        const sortFilter = document.getElementById('sortFilter');
        const filterButton = document.getElementById('filterButton');
        const resetButton = document.getElementById('resetButton');
        const eventsGrid = document.getElementById('eventsGrid');
        const noResults = document.getElementById('noResults');
        const eventCards = Array.from(eventsGrid.getElementsByClassName('event-card'));

        function filterAndSortEvents() {
            const searchTerm = searchInput.value.toLowerCase();
            const venueValue = venueFilter.value;
            const startDate = startDateFilter.value ? new Date(startDateFilter.value) : null;
            const endDate = endDateFilter.value ? new Date(endDateFilter.value) : null;
            const sortValue = sortFilter.value;
            let visibleCount = 0;

            // Validate date range
            if (startDate && endDate && startDate > endDate) {
                alert('End date must be after start date');
                return;
            }

            eventCards.forEach(card => {
                const title = card.dataset.title.toLowerCase();
                const venue = card.dataset.venue;
                const eventDate = new Date(parseInt(card.dataset.date) * 1000);

                const matchesSearch = title.includes(searchTerm);
                const matchesVenue = !venueValue || venue === venueValue;
                const matchesDateRange = (!startDate || eventDate >= startDate) && 
                                       (!endDate || eventDate <= endDate);

                if (matchesSearch && matchesVenue && matchesDateRange) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Sort the visible cards
            const visibleCards = eventCards.filter(card => card.style.display !== 'none');
            visibleCards.sort((a, b) => {
                const dateA = parseInt(a.dataset.date);
                const dateB = parseInt(b.dataset.date);
                return sortValue === 'newest' ? dateB - dateA : dateA - dateB;
            });

            // Reorder the cards in the grid
            visibleCards.forEach(card => {
                eventsGrid.appendChild(card);
            });

            // Show/hide no results message
            noResults.style.display = visibleCount === 0 ? '' : 'none';
        }

        // Add event listeners
        filterButton.addEventListener('click', filterAndSortEvents);
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            venueFilter.value = '';
            startDateFilter.value = '';
            endDateFilter.value = '';
            sortFilter.value = 'newest';
            filterAndSortEvents();
        });

        // Initial filter and sort
        filterAndSortEvents();
    });
    </script>
</body>

<?php require APPROOT . '/views/inc/footer.php'; ?> 