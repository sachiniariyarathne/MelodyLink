<?php require APPROOT . '/views/inc/header.php'; ?>

<style>
  /* Equipment Add Form Styles */
.content {
    max-width: 600px;
    margin: 2.5rem auto;
    background: #fff;
    padding: 2.5rem 2rem;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(44, 62, 80, 0.12);
    color: #22223b;
    font-family: 'Inter', Arial, sans-serif;
  }
  
  .title {
    font-size: 2.1rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #5f3dc4;
    text-align: center;
    letter-spacing: 1px;
  }
  
  .back-btn {
    display: inline-block;
    background: #5f3dc4;
    color: #fff;
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 1.5rem;
    transition: background 0.2s;
    border: none;
  }
  
  .back-btn:hover {
    background: #3b2f63;
  }
  
  .form-container {
    background: #f8f9fa;
    padding: 2rem 1.2rem;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(44, 62, 80, 0.07);
  }
  
  .form-group {
    margin-bottom: 1.3rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #3b2f63;
    letter-spacing: 0.2px;
  }
  
  .form-group input[type="text"],
  .form-group input[type="number"],
  .form-group input[type="url"],
  .form-group textarea,
  .form-group select {
    width: 100%;
    padding: 0.7rem 0.9rem;
    border-radius: 6px;
    border: 1.5px solid #d0d0e1;
    background: #fff;
    color: #22223b;
    font-size: 1.02rem;
    font-family: inherit;
    transition: border-color 0.2s;
    box-sizing: border-box;
  }
  
  .form-group input[type="text"]:focus,
  .form-group input[type="number"]:focus,
  .form-group input[type="url"]:focus,
  .form-group textarea:focus,
  .form-group select:focus {
    outline: none;
    border-color: #5f3dc4;
    background: #f3f0ff;
  }
  
  .form-group textarea {
    resize: vertical;
    min-height: 90px;
  }
  
  .invalid-feedback {
    color: #ef4444;
    font-size: 0.92rem;
    margin-top: 0.18rem;
    display: block;
    min-height: 1.2em;
  }
  
  .submit-btn {
    width: 100%;
    background: #5f3dc4;
    color: white;
    border: none;
    padding: 0.85rem 1.5rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 0.7rem;
    letter-spacing: 0.5px;
  }
  
  .submit-btn:hover {
    background: #3b2f63;
  }
  
  @media (max-width: 700px) {
    .content {
      margin: 1rem;
      padding: 1.2rem;
    }
    .form-container {
      padding: 1.2rem 0.5rem;
    }
    .title {
      font-size: 1.5rem;
    }
    .submit-btn {
      font-size: 1rem;
    }
  }
</style>


<div class="content">
  <h1 class="title">Add New Equipment</h1>
  <a href="<?php echo URLROOT; ?>/equipment" class="btn back-btn">← Back to Equipment</a>
  
  <div class="form-container">
    <form action="<?php echo URLROOT; ?>/equipment/add" method="POST">
      <div class="form-group">
        <label for="name">Equipment Name</label>
        <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>" required>
        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="3" required><?php echo $data['description']; ?></textarea>
        <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="price">Price per day ($)</label>
        <input type="number" id="price" name="price" value="<?php echo $data['price']; ?>" required step="0.01">
        <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="category">Category</label>
        <select id="category" name="category" required>
          <option value="" <?php echo empty($data['category']) ? 'selected' : ''; ?>>Select a category</option>
          <option value="sound" <?php echo ($data['category'] == 'sound') ? 'selected' : ''; ?>>Sound System</option>
          <option value="lighting" <?php echo ($data['category'] == 'lighting') ? 'selected' : ''; ?>>Lighting</option>
          <option value="stage" <?php echo ($data['category'] == 'stage') ? 'selected' : ''; ?>>Stage Equipment</option>
          <option value="dj" <?php echo ($data['category'] == 'dj') ? 'selected' : ''; ?>>DJ Gear</option>
        </select>
        <span class="invalid-feedback"><?php echo $data['category_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="image_url">Image URL</label>
        <input type="url" id="image_url" name="image_url" value="<?php echo $data['image_url']; ?>">
        <span class="invalid-feedback"><?php echo $data['image_url_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="rating">Rating (1-5)</label>
        <input type="number" id="rating" name="rating" min="1" max="5" step="0.1" value="<?php echo $data['rating']; ?>" required>
        <span class="invalid-feedback"><?php echo $data['rating_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="reviews">Number of Reviews</label>
        <input type="number" id="reviews" name="reviews" min="0" value="<?php echo $data['reviews']; ?>" required>
        <span class="invalid-feedback"><?php echo $data['reviews_err']; ?></span>
      </div>
      
      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" required>
          <option value="available" <?php echo ($data['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
          <option value="booked" <?php echo ($data['status'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
        </select>
        <span class="invalid-feedback"><?php echo $data['status_err']; ?></span>
      </div>
      
      <div class="form-group">
        <button type="submit" class="submit-btn">Add Equipment</button>
      </div>
    </form>
  </div>
</div>
