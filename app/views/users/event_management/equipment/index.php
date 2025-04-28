<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<div class="equipment-container">
    <div class="hero-container d-flex align-items-center mb-4">
        <h1 class="text-white mb-0">Event Equipment</h1>
        <a href="<?php echo URLROOT; ?>/eventequipmentcontroller/myrequests" class="btn btn-gradient ms-auto">
            <i class="fas fa-inbox me-2"></i>My Requests
        </a>
    </div>

    <?php flash('equipment_message'); ?>

    <div class="equipment-filters">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="equipmentSearch" placeholder="Search equipment...">
        </div>
        <div class="category-filter">
            <select id="categorySelect" class="category-select">
                <option value="all">All Categories</option>
                <option value="sound">Sound</option>
                <option value="lighting">Lighting</option>
                <option value="stage">Stage</option>
                <option value="dj">DJ</option>
            </select>
        </div>
    </div>

    <div class="equipment-grid" id="equipmentGrid">
        <?php foreach($data['equipment'] as $item): ?>
            <div class="equipment-card" data-name="<?php echo strtolower($item->name); ?>" data-category="<?php echo strtolower($item->category); ?>" data-description="<?php echo strtolower($item->description); ?>">
                <div class="equipment-image">
                    <img src="<?php echo $item->image_url; ?>" alt="<?php echo $item->name; ?>">
                    <div class="equipment-status <?php echo $item->status; ?>">
                        <?php echo ucfirst($item->status); ?>
                    </div>
                </div>
                <div class="equipment-content">
                    <h3><?php echo $item->name; ?></h3>
                    <div class="equipment-details">
                        <div class="detail">
                            <i class="fas fa-tag"></i>
                            <span><?php echo ucfirst($item->category); ?></span>
                        </div>
                        <div class="detail">
                            <i class="fas fa-dollar-sign"></i>
                            <span>$<?php echo number_format($item->price, 2); ?>/day</span>
                        </div>
                    </div>
                    <div class="equipment-meta">
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $item->rating ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                            <span>(<?php echo $item->reviews; ?> reviews)</span>
                        </div>
                    </div>
                    <p class="equipment-description"><?php echo $item->description; ?></p>
                    <div class="equipment-actions">
                        <a href="<?php echo URLROOT; ?>/eventequipmentcontroller/request/<?php echo $item->id; ?>" class="btn-action view <?php echo $item->status == 'booked' ? 'disabled' : ''; ?>">
                            <i class="fas fa-calendar-check"></i> <?php echo $item->status == 'booked' ? 'Booked' : 'Request'; ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.equipment-container {
    padding: 2rem;
    background: #1a1625;
    min-height: 100vh;
}

.equipment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.equipment-header h1 {
    color: #fff;
    font-size: 1.75rem;
    margin: 0;
}

.equipment-filters {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin-bottom: 2rem;
    gap: 1.5rem;
}

.hero-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: none;
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.5);
}

.category-filter {
    min-width: 180px;
}

.category-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    border: none;
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 1rem;
}

.equipment-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.equipment-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    overflow: hidden;
    transition: transform 0.3s;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.equipment-card:hover {
    transform: translateY(-5px);
}

.equipment-image {
    position: relative;
    height: 300px;
}

.equipment-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.equipment-status.available {
    background: rgba(76, 175, 80, 0.2);
    color: #4caf50;
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}
.equipment-status.booked {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.equipment-content {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    flex: 1;
    font-size: 0.95rem;
}

.equipment-content h3 {
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
}

.equipment-details {
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.7);
}

.equipment-meta {
    margin-bottom: 0.5rem;
}

.rating .fa-star {
    color: #ffc107;
}
.rating .fa-star:not(.active) {
    color: rgba(255, 255, 255, 0.2);
}
.rating span {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    margin-left: 0.5rem;
}

.equipment-description {
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
}

.equipment-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-action {
    background: #ff4d94;
    color: #fff;
    padding: 0.5rem 1.25rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
    font-weight: 500;
}

.btn-action.disabled {
    background: #aaa;
    pointer-events: none;
}

.btn-action:hover:not(.disabled) {
    background: #ff1a75;
}

@media (max-width: 992px) {
    .equipment-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 768px) {
    .equipment-container {
        padding: 1rem;
    }
    .equipment-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    .equipment-filters {
        flex-direction: column;
        gap: 1rem;
    }
    .search-box {
        max-width: 100%;
    }
    .equipment-grid {
        grid-template-columns: 1fr;
    }
}

.btn-gradient {
    background: linear-gradient(90deg, #ff4d94 60%, #ff6b6b 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 0.7rem;
    transition: background 0.3s, box-shadow 0.3s;
    box-shadow: 0 2px 8px rgba(255,77,148,0.10);
    padding: 0.6rem 1.5rem;
    font-size: 1.1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.btn-gradient:hover {
    background: linear-gradient(90deg, #ff6b6b 60%, #ff4d94 100%);
    color: #fff;
    box-shadow: 0 4px 16px rgba(255,77,148,0.18);
}
</style>

<script>
function filterEquipment() {
    const search = document.getElementById('equipmentSearch').value.toLowerCase();
    const category = document.getElementById('categorySelect').value;
    document.querySelectorAll('.equipment-card').forEach(function(card) {
        const name = card.getAttribute('data-name');
        const cardCategory = card.getAttribute('data-category');
        const description = card.getAttribute('data-description');
        const matchesCategory = (category === 'all' || cardCategory === category);
        const matchesSearch = (name.includes(search) || cardCategory.includes(search) || description.includes(search));
        if (matchesCategory && matchesSearch) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

document.getElementById('equipmentSearch').addEventListener('input', filterEquipment);
document.getElementById('categorySelect').addEventListener('change', filterEquipment);
</script>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?>
