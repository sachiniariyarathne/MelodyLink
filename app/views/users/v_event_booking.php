<?php require APPROOT . '/views/inc/event_header.php'; ?>

<div class="booking-container">
    <div class="booking-content">
        <div class="booking-header">
            <h1>Book Tickets for <?php echo $data['event']->title; ?></h1>
            <div class="event-info">
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <span><?php echo date('F j, Y', strtotime($data['event']->event_date)); ?></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span><?php echo date('h:i A', strtotime($data['event']->event_time)); ?></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo $data['event']->venue; ?></span>
                </div>
            </div>
        </div>

        <form action="<?php echo URLROOT; ?>/events/book/<?php echo $data['event']->event_id; ?>" method="POST" class="booking-form">
            <div class="ticket-selection">
                <h3>Select Ticket Type</h3>
                <div class="ticket-options">
                    <?php if(isset($data['ticket_types']) && !empty($data['ticket_types'])): ?>
                        <?php foreach($data['ticket_types'] as $ticket): ?>
                            <?php if($ticket->available_quantity > 0): ?>
                                <div class="ticket-option">
                                    <input type="radio" name="ticket_type_id" value="<?php echo $ticket->ticket_type_id; ?>" 
                                           id="ticket_<?php echo $ticket->ticket_type_id; ?>" required>
                                    <label for="ticket_<?php echo $ticket->ticket_type_id; ?>">
                                        <div class="ticket-info">
                                            <span class="ticket-name"><?php echo $ticket->name; ?></span>
                                            <span class="ticket-price">Rs.<?php echo number_format($ticket->price, 2); ?></span>
                                            <span class="ticket-available"><?php echo $ticket->available_quantity; ?> available</span>
                                        </div>
                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-tickets">
                            <p>No tickets available for this event.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="quantity-selection">
                <label for="quantity">Number of Tickets</label>
                <div class="quantity-input">
                    <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" name="quantity" min="1" max="10" value="1" required>
                    <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
                </div>
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

            <button type="submit" class="btn-confirm">Proceed to Payment</button>
        </form>
    </div>
</div>

<style>
.booking-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: var(--bg-primary);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.booking-content {
    background: rgba(255, 255, 255, 0.05);
    padding: 2rem;
    border-radius: 8px;
}

.booking-header {
    text-align: center;
    margin-bottom: 2rem;
}

.booking-header h1 {
    color: var(--text-primary);
    font-size: 2rem;
    margin-bottom: 1rem;
}

.event-info {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
}

.info-item i {
    color: var(--accent-primary);
}

.ticket-selection {
    margin-bottom: 2rem;
}

.ticket-selection h3 {
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.ticket-options {
    display: grid;
    gap: 1rem;
}

.ticket-option {
    position: relative;
}

.ticket-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.ticket-option label {
    display: block;
    padding: 1.5rem;
    background: rgba(0, 0, 0, 0.2);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.ticket-option input[type="radio"]:checked + label {
    border-color: var(--accent-primary);
    background: rgba(var(--accent-primary-rgb), 0.1);
}

.ticket-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ticket-name {
    font-weight: 500;
    color: var(--text-primary);
}

.ticket-price {
    color: var(--accent-primary);
    font-weight: 600;
}

.ticket-available {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.quantity-selection {
    margin-bottom: 2rem;
}

.quantity-selection label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.quantity-input {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.quantity-input input {
    width: 80px;
    text-align: center;
    padding: 0.5rem;
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    color: var(--text-primary);
}

.quantity-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-primary);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.quantity-btn:hover {
    background: var(--accent-secondary);
}

.price-summary {
    background: rgba(0, 0, 0, 0.2);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
}

.summary-item.total {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    font-weight: bold;
    color: var(--text-primary);
}

.btn-confirm {
    width: 100%;
    padding: 1rem;
    background: var(--accent-primary);
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-confirm:hover {
    background: var(--accent-secondary);
}

.no-tickets {
    text-align: center;
    padding: 2rem;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    color: var(--text-secondary);
}

@media (max-width: 768px) {
    .booking-container {
        padding: 1rem;
    }
    
    .booking-content {
        padding: 1.5rem;
    }
    
    .event-info {
        flex-direction: column;
        gap: 1rem;
    }
    
    .ticket-info {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}
</style>

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
            const priceText = ticketOption.querySelector('.ticket-price').textContent;
            const price = parseFloat(priceText.replace('Rs.', '').replace(',', ''));
            const quantity = parseInt(quantityInput.value);
            const total = price * quantity;
            
            ticketPriceSpan.textContent = price.toFixed(2);
            quantityDisplay.textContent = quantity;
            totalPriceSpan.textContent = total.toFixed(2);
        }
    }

    // Initialize price when a ticket is selected
    ticketRadios.forEach(radio => {
        radio.addEventListener('change', updatePrice);
        
        // If a ticket is pre-selected, update the price
        if (radio.checked) {
            updatePrice();
        }
    });

    // Update price when quantity changes
    quantityInput.addEventListener('input', updatePrice);
    quantityInput.addEventListener('change', updatePrice);
});

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        // Trigger the change event to update the price
        input.dispatchEvent(new Event('change'));
    }
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue < 10) {
        input.value = currentValue + 1;
        // Trigger the change event to update the price
        input.dispatchEvent(new Event('change'));
    }
}
</script>

<?php require APPROOT . '/views/inc/event_footer.php'; ?>