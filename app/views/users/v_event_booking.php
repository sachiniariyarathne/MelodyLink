<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="booking-container">
    <div class="booking-content">
        <h1>Book Tickets for <?php echo $data['event']->title; ?></h1>
        
        <div class="booking-details">
            <div class="event-info">
                <div class="info-item">
                    <span class="label">Date:</span>
                    <span class="value"><?php echo date('F j, Y', strtotime($data['event']->event_date)); ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Time:</span>
                    <span class="value"><?php echo date('h:i A', strtotime($data['event']->event_time)); ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Venue:</span>
                    <span class="value"><?php echo $data['event']->venue; ?></span>
                </div>
            </div>

            <form action="<?php echo URLROOT; ?>/events/book/<?php echo $data['event']->id; ?>" method="POST" class="booking-form">
                <div class="ticket-selection">
                    <h3>Select Ticket Type</h3>
                    <?php foreach($data['event']->ticket_types as $ticket): ?>
                        <?php if($ticket->quantity_available > 0): ?>
                            <div class="ticket-option">
                                <div class="ticket-info">
                                    <input type="radio" name="ticket_type_id" value="<?php echo $ticket->id; ?>" 
                                           id="ticket_<?php echo $ticket->id; ?>" required>
                                    <label for="ticket_<?php echo $ticket->id; ?>">
                                        <span class="ticket-name"><?php echo $ticket->name; ?></span>
                                        <span class="ticket-price">Rs.<?php echo number_format($ticket->price, 2); ?></span>
                                        <span class="ticket-available">(<?php echo $ticket->quantity_available; ?> available)</span>
                                    </label>
                                </div>
                                <div class="ticket-description"><?php echo $ticket->description; ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="form-group">
                    <label for="quantity">Number of Tickets</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="10" value="1" required>
                </div>

                <div class="price-summary">
                    <div class="summary-item">
                        <span>Ticket Price:</span>
                        <span>Rs.<span id="ticketPrice">0.00</span></span>
                    </div>
                    <div class="summary-item">
                        <span>Quantity:</span>
                        <span id="quantityDisplay">1</span>
                    </div>
                    <div class="summary-item total">
                        <span>Total:</span>
                        <span>Rs.<span id="totalPrice">0.00</span></span>
                    </div>
                </div>

                <button type="submit" class="btn-confirm">Confirm Booking</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketRadios = document.querySelectorAll('input[name="ticket_type_id"]');
    const quantityInput = document.getElementById('quantity');
    const ticketPriceSpan = document.getElementById('ticketPrice');
    const quantityDisplay = document.getElementById('quantityDisplay');
    const totalPriceSpan = document.getElementById('totalPrice');

    function updatePrice() {
        const selectedTicket = document.querySelector('input[name="ticket_type_id"]:checked');
        if (selectedTicket) {
            const ticketOption = selectedTicket.closest('.ticket-option');
            const price = parseFloat(ticketOption.querySelector('.ticket-price').textContent.replace('Rs.', ''));
            const quantity = parseInt(quantityInput.value);
            const total = price * quantity;
            
            ticketPriceSpan.textContent = price.toFixed(2);
            quantityDisplay.textContent = quantity;
            totalPriceSpan.textContent = total.toFixed(2);
        }
    }

    ticketRadios.forEach(radio => {
        radio.addEventListener('change', updatePrice);
    });

    quantityInput.addEventListener('change', updatePrice);
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 