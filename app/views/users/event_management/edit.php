<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<div class="edit-event-container">
    <div class="form-container">
        <h2>Edit Event</h2>
        <form action="<?php echo URLROOT; ?>/eventmanagement/edit/<?php echo $data['event']->event_id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" name="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['event']->title; ?>">
                <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="4"><?php echo $data['event']->description; ?></textarea>
                <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="event_date">Event Date</label>
                    <input type="date" name="event_date" class="form-control <?php echo (!empty($data['event_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['event']->event_date; ?>">
                    <span class="invalid-feedback"><?php echo $data['event_date_err']; ?></span>
                </div>

                <div class="form-group col-md-6">
                    <label for="event_time">Event Time</label>
                    <input type="time" name="event_time" class="form-control <?php echo (!empty($data['event_time_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['event']->event_time; ?>">
                    <span class="invalid-feedback"><?php echo $data['event_time_err']; ?></span>
                </div>
            </div>

            <div class="form-group">
                <label for="venue">Venue</label>
                <input type="text" name="venue" class="form-control <?php echo (!empty($data['venue_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['event']->venue; ?>">
                <span class="invalid-feedback"><?php echo $data['venue_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="image">Event Image</label>
                <div class="current-image">
                    <img src="<?php echo URLROOT; ?>/<?php echo $data['event']->image; ?>" alt="Current Event Image">
                </div>
                <input type="file" name="image" class="form-control-file">
                <small class="form-text text-muted">Leave empty to keep current image</small>
                <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
            </div>

            <div class="form-group">
                <h3>Ticket Types</h3>
                <div id="ticket-types-container">
                    <?php foreach($data['ticket_types'] as $ticket): ?>
                        <div class="ticket-type-row">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Ticket Name</label>
                                    <input type="text" name="ticket_name[]" class="form-control" value="<?php echo $ticket->name; ?>" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Price (Rs.)</label>
                                    <input type="number" name="ticket_price[]" class="form-control" min="0" step="0.01" value="<?php echo $ticket->price; ?>" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Quantity Available</label>
                                    <input type="number" name="ticket_quantity[]" class="form-control" min="1" value="<?php echo $ticket->quantity_available; ?>" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-block remove-ticket">Remove</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-secondary mt-2" id="add-ticket-type">Add Another Ticket Type</button>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Event</button>
                <a href="<?php echo URLROOT; ?>/eventmanagement/edit/<?php echo $data['event']->event_id; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.edit-event-container {
    padding: 2rem;
    background: #1a1625;
    min-height: 100vh;
}

.form-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 2rem;
    backdrop-filter: blur(10px);
    max-width: 1000px;
    margin: 0 auto;
}

h2, h3 {
    color: #fff;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.col-md-6 {
    flex: 1;
}

label {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    border-radius: 0.5rem;
    padding: 0.75rem;
    width: 100%;
}

.form-control:focus {
    background: rgba(255, 255, 255, 0.1);
    border-color: #ff4d94;
    box-shadow: none;
    outline: none;
}

.form-control-file {
    color: rgba(255, 255, 255, 0.8);
}

.current-image {
    margin: 1rem 0;
    max-width: 300px;
    border-radius: 0.5rem;
    overflow: hidden;
}

.current-image img {
    width: 100%;
    height: auto;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #ff4d94;
    color: #fff;
}

.btn-primary:hover {
    background: #ff1a75;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    text-decoration: none;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
}

.btn-danger {
    background: #dc3545;
    color: #fff;
}

.btn-danger:hover {
    background: #c82333;
}

.invalid-feedback {
    color: #ff4d4d;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.ticket-type-row {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.text-muted {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .form-container {
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('ticket-types-container');
    const addButton = document.getElementById('add-ticket-type');
    
    addButton.addEventListener('click', function() {
        const template = `
            <div class="ticket-type-row">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Ticket Name</label>
                        <input type="text" name="ticket_name[]" class="form-control" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Price (Rs.)</label>
                        <input type="number" name="ticket_price[]" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Quantity Available</label>
                        <input type="number" name="ticket_quantity[]" class="form-control" min="1" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block remove-ticket">Remove</button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
    });
    
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ticket')) {
            if (container.children.length > 1) {
                e.target.closest('.ticket-type-row').remove();
            }
        }
    });
});
</script>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?> 