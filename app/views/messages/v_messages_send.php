<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="content">
  <h1 class="title">Request Equipment</h1>
  <a href="<?php echo URLROOT; ?>/equipment" class="btn back-btn">← Back to Equipment</a>
  
  <div class="form-container">
    <form action="<?php echo URLROOT; ?>/messages/send" method="POST">
      <div class="form-group">
        <label for="equipment_id">Select Equipment</label>
        <select id="equipment_id" name="equipment_id" required>
          <option value="" <?php echo empty($data['equipment_id']) ? 'selected' : ''; ?>>Select equipment</option>
          <?php foreach($data['equipment'] as $equipment) : ?>
            <option value="<?php echo $equipment->id; ?>" <?php echo ($data['equipment_id'] == $equipment->id) ? 'selected' : ''; ?>>
              <?php echo $equipment->name; ?> - $<?php echo number_format($equipment->price, 2); ?>/day
            </option>
          <?php endforeach; ?>
        </select>
        <span class="invalid-feedback"><?php echo $data['equipment_id_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="message">Request Details</label>
        <textarea id="message" name="message" rows="5" required placeholder="Explain why you need this equipment, for what event, and for how long..."><?php echo $data['message']; ?></textarea>
        <span class="invalid-feedback"><?php echo $data['message_err']; ?></span>
      </div>
      
      <div class="form-group">
        <button type="submit" class="submit-btn">Send Request</button>
      </div>
    </form>
  </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
