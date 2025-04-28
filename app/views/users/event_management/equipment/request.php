<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<div class="create-event-container">
    <div class="form-container">
        <h2>Request Equipment</h2>
        <form action="<?php echo URLROOT; ?>/eventequipmentcontroller/request/<?php echo $data['equipment']->id; ?>" method="post">
            <div class="form-group">
                <label>Equipment</label>
                <input type="text" class="form-control" value="<?php echo $data['equipment']->name; ?>" readonly>
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" class="form-control" value="<?php echo ucfirst($data['equipment']->category); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Price (per day)</label>
                <input type="text" class="form-control" value="$<?php echo number_format($data['equipment']->price, 2); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="message">Request Message <span class="text-danger">*</span></label>
                <textarea name="message" class="form-control <?php echo (!empty($data['message_err'])) ? 'is-invalid' : ''; ?>" rows="4" placeholder="Describe your event, date, venue, and any special requirements..." required><?php echo $data['message']; ?></textarea>
                <span class="invalid-feedback"><?php echo $data['message_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="request_date">Request Date <span class="text-danger">*</span></label>
                <input type="datetime-local" name="request_date" class="form-control <?php echo (!empty($data['request_date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['request_date']; ?>" required>
                <span class="invalid-feedback"><?php echo $data['request_date_err']; ?></span>
            </div>
            <div class="form-group">
                <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 1rem;">Pending</span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Send Request</button>
                <a href="<?php echo URLROOT; ?>/eventequipmentcontroller" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.create-event-container {
    padding: 2rem;
    background: #1a1625;
    min-height: 100vh;
}

.form-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 2rem;
    backdrop-filter: blur(10px);
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h2 {
    color: #fff;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
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
    margin: 0 auto;
    font-size: 1.1rem;
}

.form-control:focus {
    background: rgba(255, 255, 255, 0.1);
    border-color: #ff4d94;
    box-shadow: none;
    outline: none;
}

.btn-primary {
    background: #ff4d94;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    color: #fff;
}

.btn-primary:hover {
    background: #ff1a75;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    color: #fff;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
}

.invalid-feedback {
    color: #ff4d4d;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

textarea.form-control {
    min-height: 120px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const requestDateInput = document.querySelector('input[name="request_date"]');
    if (requestDateInput) {
        const now = new Date();
        const localISOTime = now.toISOString().slice(0,16);
        requestDateInput.min = localISOTime;
    }
});
</script>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?>
