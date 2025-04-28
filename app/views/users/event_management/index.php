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
            <div class="header-actions">
                <button class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
                <button class="btn-export"><i class="fas fa-download"></i> Export</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Tickets</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['recent_bookings'] as $booking): ?>
                    <tr>
                        <td>
                            <div class="customer-info">
                                <img src="<?php echo URLROOT; ?>/public/img/avatars/<?php echo $booking->user_avatar; ?>" alt="Avatar" class="avatar">
                                <span><?php echo $booking->customer_name; ?></span>
                            </div>
                        </td>
                        <td><?php echo $booking->event_title; ?></td>
                        <td><?php echo $booking->booking_date ?? 'N/A'; ?></td>
                        <td><?php echo $booking->ticket_count ?? 0; ?></td>
                        <td><span class="status-badge <?php echo strtolower($booking->status); ?>"><?php echo $booking->status; ?></span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon" title="More options"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
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

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-filter, .btn-export {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-filter:hover, .btn-export:hover {
    background: rgba(255, 255, 255, 0.2);
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

.customer-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    object-fit: cover;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
}

.status-badge.confirmed {
    background: rgba(76, 175, 80, 0.2);
    color: #4caf50;
}

.status-badge.pending {
    background: rgba(255, 152, 0, 0.2);
    color: #ff9800;
}

.btn-icon {
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
    padding: 0.25rem;
}

.btn-icon:hover {
    color: #ff4d94;
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