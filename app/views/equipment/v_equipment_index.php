<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/equipment.css">
<div class="content">
  <h1 class="title">My Equipment</h1>
  
  <?php 
  // Display message if it exists in session
  if(isset($_SESSION['equipment_message'])) : 
  ?>
    <div class="alert alert-success">
      <?php 
      echo $_SESSION['equipment_message']; 
      // Clear the message after displaying
      unset($_SESSION['equipment_message']);
      ?>
    </div>
  <?php endif; ?>
  
  <a href="<?php echo URLROOT; ?>/equipment/add" class="add-btn">+ Add New Equipment</a>
  
  <div class="filters">
    <button class="filter-btn active" data-filter="all">All Equipment</button>
    <button class="filter-btn" data-filter="sound">Sound Systems</button>
    <button class="filter-btn" data-filter="lighting">Lighting</button>
    <button class="filter-btn" data-filter="stage">Stage Equipment</button>
    <button class="filter-btn" data-filter="dj">DJ Gear</button>
  </div>

  <div class="equipment-grid" id="equipmentGrid">
    <?php if(!empty($data['equipment'])) : ?>
      <?php foreach ($data['equipment'] as $item): ?>
        <div class="card" data-category="<?= htmlspecialchars($item->category) ?>" data-id="<?= $item->id ?>">
          <div style="position:relative">
            <img src="<?= htmlspecialchars($item->image_url) ?>" alt="<?= htmlspecialchars($item->name) ?>" onerror="this.src='<?php echo URLROOT; ?>/img/placeholder.jpg'">
            <div class="status <?= $item->status === 'booked' ? 'booked' : '' ?>"><?= $item->status === 'booked' ? 'Booked' : 'Available' ?></div>
          </div>
          <div class="card-content">
            <p class="card-title"><?= htmlspecialchars($item->name) ?></p>
            <p class="card-desc"><?= htmlspecialchars($item->description) ?></p>
            <div class="card-footer">
              <span class="price">$<?= number_format($item->price, 2) ?>/day</span>
              
            </div>
            <div class="actions">
              <a href="<?php echo URLROOT; ?>/equipment/edit/<?= $item->id ?>" class="btn edit-btn">Edit</a>
              <form action="<?php echo URLROOT; ?>/equipment/delete/<?= $item->id ?>" method="post" class="delete-form">
                <button type="submit" class="btn delete-btn">Delete</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p>No equipment found. Add some equipment to get started!</p>
    <?php endif; ?>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.card');
    
    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        filterButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.getAttribute('data-filter');
        
        cards.forEach(card => {
          if (filter === 'all') {
            card.classList.remove('hidden');
          } else {
            if (card.getAttribute('data-category') === filter) {
              card.classList.remove('hidden');
            } else {
              card.classList.add('hidden');
            }
          }
        });
      });
    });

    // Confirm before deleting
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to delete this equipment?')) {
          e.preventDefault();
        }
      });
    });
  });
</script>

