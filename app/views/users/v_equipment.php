<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventGear Pro - My Equipment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #12092b;
            color: #fff;
        }
        .navbar {
            background: #1b1239;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar a {
            margin: 0 1rem;
            color: #ccc;
            text-decoration: none;
        }
        .navbar a.active, .navbar a:hover {
            color: #a78bfa;
        }
        .content {
            padding: 2rem;
        }
        .title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .filter-btn {
            background: #2e1e5c;
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 0.5rem 1.5rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .filter-btn:hover {
            background: #3d2a72;
        }
        .filter-btn.active {
            background: #a78bfa;
        }
        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .card {
            background: #1e153a;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .status {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #10b981;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status.booked {
            background: #f59e0b;
        }
        .card-content {
            padding: 1rem;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
        }
        .card-desc {
            font-size: 0.875rem;
            color: #ccc;
            margin: 0.5rem 0;
            line-height: 1.5;
        }
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        .price {
            font-weight: 600;
            color: #a78bfa;
        }
        .rating {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
        }
        .rating span {
            margin-left: 0.3rem;
            color: #ccc;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            gap: 0.5rem;
        }
        .btn {
            flex: 1;
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .edit-btn {
            background: #a78bfa;
            color: white;
        }
        .delete-btn {
            background: #ef4444;
            color: white;
        }
        .add-btn {
            background: #a78bfa;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            float: right;
            margin-bottom: 1rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        .add-btn:hover {
            background: #8c6cf5;
        }
        .card.hidden {
            display: none;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
            padding: 2rem 0;
        }
        .modal-content {
            background: #1e153a;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            position: relative;
        }
        .modal-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #a78bfa;
            position: sticky;
            top: 0;
            background: #1e153a;
            padding-bottom: 1rem;
            z-index: 1;
        }
        .form-container {
            max-height: calc(80vh - 150px);
            overflow-y: auto;
            padding-right: 0.5rem;
        }
        .form-container::-webkit-scrollbar {
            width: 6px;
        }
        .form-container::-webkit-scrollbar-track {
            background: #1e153a;
        }
        .form-container::-webkit-scrollbar-thumb {
            background: #a78bfa;
            border-radius: 3px;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ddd;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #2e1e5c;
            background: #12092b;
            color: white;
            font-family: 'Inter', sans-serif;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #a78bfa;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
            position: sticky;
            bottom: 0;
            background: #1e153a;
            padding-top: 1rem;
            z-index: 1;
        }
        .form-actions button {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .form-actions button:hover {
            opacity: 0.9;
        }
        .cancel-btn {
            background: #2e1e5c;
            color: white;
            border: none;
        }
        .submit-btn {
            background: #a78bfa;
            color: white;
            border: none;
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                padding: 1.5rem;
            }
            .filters {
                gap: 0.5rem;
            }
            .filter-btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div><strong style="color:white">MelodyLink</strong></div>
        <div>
            <a href="#" class="active">Dashboard</a>
            <a href="#">Equipment</a>
            <a href="#">Bookings</a>
            <a href="#">Calendar</a>
        </div>
    </div>

    <div class="content">
        <h1 class="title">My Equipment</h1>
        <button class="add-btn" id="addEquipmentBtn">+ Add New Equipment</button>
        <div class="filters">
            <button class="filter-btn active" data-filter="all">All Equipment</button>
            <button class="filter-btn" data-filter="sound">Sound Systems</button>
            <button class="filter-btn" data-filter="lighting">Lighting</button>
            <button class="filter-btn" data-filter="stage">Stage Equipment</button>
            <button class="filter-btn" data-filter="dj">DJ Gear</button>
        </div>

        <div class="equipment-grid" id="equipmentGrid">
            <?php foreach ($equipment as $item): ?>
                <div class="card" data-category="<?= htmlspecialchars($item['category']) ?>" data-id="<?= $item['id'] ?>">
                    <div style="position:relative">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" onerror="this.src='https://via.placeholder.com/300x180?text=Equipment+Image'">
                        <div class="status <?= $item['status'] === 'booked' ? 'booked' : '' ?>"><?= $item['status'] === 'booked' ? 'Booked' : 'Available' ?></div>
                    </div>
                    <div class="card-content">
                        <p class="card-title"><?= htmlspecialchars($item['name']) ?></p>
                        <p class="card-desc"><?= htmlspecialchars($item['description']) ?></p>
                        <div class="card-footer">
                            <span class="price">$<?= number_format($item['price'], 2) ?>/day</span>
                            <span class="rating">⭐ <?= $item['rating'] ?> <span>(<?= $item['reviews'] ?>)</span></span>
                        </div>
                        <div class="actions">
                            <button class="btn edit-btn">Edit</button>
                            <a href="?delete=<?= $item['id'] ?>" class="btn delete-btn">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Equipment Modal -->
    <div class="modal" id="addEquipmentModal">
        <div class="modal-content">
            <h3 class="modal-title">Add New Equipment</h3>
            <div class="form-container">
                <form id="equipmentForm" method="POST">
                    <input type="hidden" name="add_equipment" value="1">
                    <div class="form-group">
                        <label for="equipmentName">Equipment Name</label>
                        <input type="text" id="equipmentName" name="name" required placeholder="Enter equipment name">
                    </div>
                    <div class="form-group">
                        <label for="equipmentDesc">Description</label>
                        <textarea id="equipmentDesc" name="description" rows="3" required placeholder="Describe the equipment features"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="equipmentPrice">Price per day ($)</label>
                        <input type="number" id="equipmentPrice" name="price" required placeholder="Enter daily rate" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="equipmentCategory">Category</label>
                        <select id="equipmentCategory" name="category" required>
                            <option value="">Select a category</option>
                            <option value="sound">Sound System</option>
                            <option value="lighting">Lighting</option>
                            <option value="stage">Stage Equipment</option>
                            <option value="dj">DJ Gear</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="equipmentImage">Image URL</label>
                        <input type="url" id="equipmentImage" name="image_url" required placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="form-group">
                        <label for="equipmentRating">Rating (1-5)</label>
                        <input type="number" id="equipmentRating" name="rating" min="1" max="5" step="0.1" value="4.5" required>
                    </div>
                    <div class="form-group">
                        <label for="equipmentReviews">Number of Reviews</label>
                        <input type="number" id="equipmentReviews" name="reviews" min="0" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="equipmentStatus">Status</label>
                        <select id="equipmentStatus" name="status" required>
                            <option value="available">Available</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="form-actions">
                <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                <button type="submit" class="submit-btn" form="equipmentForm">Add Equipment</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const addBtn = document.getElementById('addEquipmentBtn');
            const modal = document.getElementById('addEquipmentModal');
            const cancelBtn = document.getElementById('cancelBtn');

            // Open modal
            addBtn.addEventListener('click', () => {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });

            // Close modal
            cancelBtn.addEventListener('click', () => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });

            // Close modal when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });

            // Confirm before deleting
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this equipment?')) {
                        e.preventDefault();
                    }
                });
            });

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
        });
    </script>
</body>
</html>
