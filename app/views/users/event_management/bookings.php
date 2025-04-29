<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<style>
    .bookings-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .bookings-container h1 {
        color: #fff;
        margin-bottom: 2rem;
        font-weight: 600;
    }

    .secret-code-search {
        background: rgba(255, 255, 255, 0.05);
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .search-form {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: #ff4d94;
        box-shadow: 0 0 0 0.2rem rgba(255, 77, 148, 0.25);
        background: rgba(255, 255, 255, 0.1);
        outline: none;
    }

    .search-button {
        background: #ff4d94;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-button:hover {
        background: #ff2d84;
        transform: translateY(-2px);
    }

    .search-result {
        margin-top: 1.5rem;
        padding: 1.5rem;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: none;
    }

    .search-result.show {
        display: block;
    }

    .result-success {
        border-left: 4px solid #4CAF50;
    }

    .result-error {
        border-left: 4px solid #f44336;
    }

    .result-title {
        color: #fff;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .result-details {
        color: rgba(255, 255, 255, 0.7);
    }

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    .bookings-table th,
    .bookings-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
    }

    .bookings-table th {
        background: rgba(255, 255, 255, 0.05);
        font-weight: 500;
        color: #fff;
    }

    .bookings-table tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .event-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .bookings-table {
            display: block;
            overflow-x: auto;
        }
        
        .search-form {
            flex-direction: column;
        }
        
        .search-button {
            width: 100%;
        }
    }
</style>

<div class="bookings-container">
    <h1>Bookings for <?php echo $data['event']->title; ?></h1>
    
    <div class="secret-code-search">
        <form class="search-form" id="secretCodeForm">
            <input type="text" class="search-input" id="secretCodeInput" placeholder="Enter secret code to search booking..." required>
            <button type="submit" class="search-button">Search</button>
        </form>
        <div class="search-result" id="searchResult"></div>
    </div>
    
    <?php if (empty($data['bookings'])): ?>
        <p>No bookings found for this event.</p>
    <?php else: ?>
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Ticket Types</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['bookings'] as $booking): ?>
                    <tr>
                        <td><?php echo $booking->booking_id; ?></td>
                        <td><?php echo $booking->customer_name; ?></td>
                        <td><?php echo $booking->email; ?></td>
                        <td>
                            <?php if (!empty($booking->tickets)): ?>
                                <ul>
                                    <?php foreach ($booking->tickets as $ticket): ?>
                                        <li><?php echo $ticket->ticket_type; ?>: <?php echo $ticket->quantity; ?> ticket(s)</li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                No tickets
                            <?php endif; ?>
                        </td>
                        <td>Rs.<?php echo number_format($booking->total_price); ?></td>
                        <td><?php echo ucfirst($booking->payment_status); ?></td>
                        <td><?php echo date('F j, Y, g:i A', strtotime($booking->created_at)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <div class="event-actions">
        <a href="<?php echo URLROOT; ?>/eventmanagement/viewEvent/<?php echo $data['event']->event_id; ?>" class="btn btn-secondary">Back to Event</a>
    </div>
</div>

<script>
document.getElementById('secretCodeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const secretCode = document.getElementById('secretCodeInput').value.trim();
    const resultDiv = document.getElementById('searchResult');
    
    // Find the booking with the matching secret code
    const booking = <?php echo json_encode($data['bookings']); ?>.find(b => b.secret_code === secretCode);
    
    if (booking) {
        resultDiv.className = 'search-result show result-success';
        resultDiv.innerHTML = `
            <h3 class="result-title">Booking Found!</h3>
            <div class="result-details">
                <p><strong>Customer:</strong> ${booking.customer_name}</p>
                <p><strong>Email:</strong> ${booking.email}</p>
                <p><strong>Total Price:</strong> Rs.${booking.total_price.toLocaleString()}</p>
                <p><strong>Status:</strong> ${booking.payment_status.charAt(0).toUpperCase() + booking.payment_status.slice(1)}</p>
                <p><strong>Date:</strong> ${new Date(booking.created_at).toLocaleString()}</p>
            </div>
        `;
    } else {
        resultDiv.className = 'search-result show result-error';
        resultDiv.innerHTML = `
            <h3 class="result-title">No Booking Found</h3>
            <p class="result-details">The secret code you entered is not valid. Please check and try again.</p>
        `;
    }
});
</script>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?>