<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="content">
  <h1 class="title">Equipment Requests</h1>
  
  <?php 
  // Display message if it exists in session
  if(isset($_SESSION['message_success'])) : 
  ?>
    <div class="alert alert-success">
      <?php 
      echo $_SESSION['message_success']; 
      // Clear the message after displaying
      unset($_SESSION['message_success']);
      ?>
    </div>
  <?php endif; ?>
  
  <?php 
  if(isset($_SESSION['message_error'])) : 
  ?>
    <div class="alert alert-danger">
      <?php 
      echo $_SESSION['message_error']; 
      // Clear the message after displaying
      unset($_SESSION['message_error']);
      ?>
    </div>
  <?php endif; ?>

  <!-- Message/Request Cards -->
  <div class="message-container">
    <?php if(!empty($data['messages'])) : ?>
      <?php foreach($data['messages'] as $message) : ?>
        <?php if($message->status == 'pending') : ?>
          <div class="message-card">
            <div class="message-header">
              <div class="user-info">
                <img src="<?php echo URLROOT; ?>/img/avatar.jpg" alt="User Avatar" class="avatar">
                <div>
                  <h3><?php echo $message->sender_name; ?></h3>
                  <p><?php echo $message->sender_role; ?></p>
                </div>
              </div>
              <div class="timestamp">
                <i class="fa fa-clock-o"></i> 
                <?php 
                  $requestDate = new DateTime($message->request_date);
                  $now = new DateTime();
                  $interval = $requestDate->diff($now);
                  
                  if($interval->days > 0) {
                    echo $interval->days . ' days ago';
                  } elseif($interval->h > 0) {
                    echo $interval->h . ' hours ago';
                  } else {
                    echo $interval->i . ' minutes ago';
                  }
                ?>
              </div>
            </div>
            
            <div class="message-content">
              <div class="equipment-info">
                <img src="<?php echo $message->equipment_image; ?>" alt="Equipment" class="equipment-img" onerror="this.src='<?php echo URLROOT; ?>/img/placeholder.jpg'">
                <div>
                  <h4><?php echo $message->equipment_name; ?></h4>
                  <p>Category: <?php echo ucfirst($message->equipment_category); ?></p>
                  <p>Price: $<?php echo number_format($message->equipment_price, 2); ?>/day</p>
                </div>
              </div>
              <p class="message-text"><?php echo $message->message; ?></p>
            </div>
            
            <div class="message-actions">
              <form action="<?php echo URLROOT; ?>/messages/respond/<?php echo $message->id; ?>" method="POST">
                <button type="submit" name="response" value="accept" class="btn accept-btn">
                  <i class="fa fa-check"></i> Accept
                </button>
                <button type="submit" name="response" value="reject" class="btn reject-btn">
                  <i class="fa fa-times"></i> Reject
                </button>
              </form>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Request History Table -->
  <h2 class="subtitle">Request History</h2>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Requester</th>
          <th>Equipment</th>
          <th>Request Date</th>
          <th>Response Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($data['messages'])) : ?>
          <?php foreach($data['messages'] as $message) : ?>
            <tr class="<?php echo $message->status; ?>">
              <td><?php echo $message->id; ?></td>
              <td><?php echo $message->sender_name; ?></td>
              <td><?php echo $message->equipment_name; ?></td>
              <td><?php echo date('M d, Y h:i A', strtotime($message->request_date)); ?></td>
              <td>
                <?php 
                  echo ($message->response_date) ? date('M d, Y h:i A', strtotime($message->response_date)) : 'Pending';
                ?>
              </td>
              <td>
                <span class="status-badge <?php echo $message->status; ?>">
                  <?php echo ucfirst($message->status); ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="6" class="text-center">No requests found</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
