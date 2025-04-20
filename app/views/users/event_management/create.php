<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2>Create New Event</h2>
            <form action="<?php echo URLROOT; ?>/eventmanagement/create" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Event Title</label>
                    <input type="text" name="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title']; ?>">
                    <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="4"><?php echo $data['description']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="event_date">Event Date</label>
                        <input type="date" name="event_date" class="form-control <?php echo (!empty($data['event_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['event_date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['event_date_err']; ?></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="event_time">Event Time</label>
                        <input type="time" name="event_time" class="form-control <?php echo (!empty($data['event_time_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['event_time']; ?>">
                        <span class="invalid-feedback"><?php echo $data['event_time_err']; ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="venue">Venue</label>
                    <input type="text" name="venue" class="form-control <?php echo (!empty($data['venue_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['venue']; ?>">
                    <span class="invalid-feedback"><?php echo $data['venue_err']; ?></span>
                </div>

                <div class="form-group">
                    <label for="image">Event Image</label>
                    <input type="file" name="image" class="form-control-file <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
                </div>

                <div class="form-group">
                    <label>Ticket Types</label>
                    <div id="ticket-types-container">
                        <div class="ticket-type-row mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="ticket_name[]" class="form-control" placeholder="Ticket Name">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="ticket_price[]" class="form-control" placeholder="Price" step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="ticket_quantity[]" class="form-control" placeholder="Quantity">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-ticket-type">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-ticket-type">Add Ticket Type</button>
                    <span class="invalid-feedback d-block"><?php echo $data['ticket_types_err']; ?></span>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create Event</button>
                    <a href="<?php echo URLROOT; ?>/eventmanagement" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('ticket-types-container');
    const addButton = document.getElementById('add-ticket-type');

    addButton.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'ticket-type-row mb-3';
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="ticket_name[]" class="form-control" placeholder="Ticket Name">
                </div>
                <div class="col-md-3">
                    <input type="number" name="ticket_price[]" class="form-control" placeholder="Price" step="0.01">
                </div>
                <div class="col-md-3">
                    <input type="number" name="ticket_quantity[]" class="form-control" placeholder="Quantity">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-ticket-type">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ticket-type')) {
            e.target.closest('.ticket-type-row').remove();
        }
    });
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?> 