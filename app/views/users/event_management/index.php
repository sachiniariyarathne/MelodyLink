<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<div class="dashboard-container">
    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <h3>Total Bookings</h3>
                <i class="fas fa-ticket-alt"></i>
    </div>
            <div class="stat-value"><?php echo number_format($data['total_bookings']); ?></div>
            <div class="stat-trend positive">
                +12.5% from last month
                </div>
                        </div>

        <div class="stat-card">
            <div class="stat-header">
                <h3>Revenue</h3>
                <i class="fas fa-dollar-sign"></i>
                        </div>
            <div class="stat-value">Rs.<?php echo number_format($data['total_revenue']); ?></div>
            <div class="stat-trend positive">
                +8.3% from last month
                        </div>
                    </div>

        <div class="stat-card">
            <div class="stat-header">
                <h3>Active Events</h3>
                <i class="fas fa-calendar-alt"></i>
                        </div>
            <div class="stat-value"><?php echo $data['active_events']; ?></div>
            <div class="stat-info">
                <?php echo $data['ending_soon']; ?> ending soon
                        </div>
                    </div>

        <div class="stat-card">
            <div class="stat-header">
                <h3>Total Customers</h3>
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value"><?php echo number_format($data['total_customers']); ?></div>
            <div class="stat-trend positive">
                +15.2% from last month
                    </div>
                </div>
            </div>

    <!-- Recent Bookings -->
    <div class="recent-bookings-section">
        <div class="section-header">
            <h2>Recent Bookings</h2>
        </div>
        <div class="table-responsive">
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Event</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0; foreach($data['recent_bookings'] as $booking): if ($count++ >= 5) break; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking->customer_name); ?></td>
                        <td><?php echo htmlspecialchars($booking->event_title); ?></td>
                        <td>
                            <?php 
                            if (isset($booking->booking_date)) {
                                echo date('M d, Y', strtotime($booking->booking_date));
                            } elseif (isset($booking->created_at)) {
                                echo date('M d, Y', strtotime($booking->created_at));
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                    </tr>
        <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Dashboard Styles */
.dashboard-container {
    padding: 2rem;
    background: #1a1625;
    min-height: 100vh;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    border-radius: 1rem;
    backdrop-filter: blur(10px);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.stat-header h3 {
    color: #fff;
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
}

.stat-header i {
    color: #ff4d94;
    font-size: 1.2rem;
}

.stat-value {
    color: #fff;
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.stat-trend {
    font-size: 0.875rem;
}

.stat-trend.positive {
    color: #4caf50;
}

.stat-info {
    color: #ffd700;
    font-size: 0.875rem;
}

/* Recent Bookings Section */
.recent-bookings-section {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h2 {
    color: #fff;
    font-size: 1.25rem;
    margin: 0;
}

/* Table Styles */
.table-responsive {
    overflow-x: auto;
}

.bookings-table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
}

.bookings-table th {
    text-align: left;
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    font-weight: 500;
}

.bookings-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-container {
        padding: 1rem;
    }
}
</style>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?> 