<?php require APPROOT . '/views/inc/header3.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>

<body>
  <div class="md_dashboard-container">
    <!-- Sidebar -->
    <aside class="md_sidebar">
      <div class="md_profile-section">
        <img src="/images/default-avatar.png" alt="Profile" class="md_profile-avatar">
        <h2><?php echo $data['member_info']['username']; ?></h2>
        <p><?php echo $data['member_info']['email']; ?></p>
      </div>
      <nav class="md_sidebar-nav">
      <ul>
          <li class="md_active"><i class="fa fa-home"></i> Dashboard</li>
          <li><i class="fa fa-ticket-alt"></i><a href="<?php echo URLROOT; ?>/my_tickets/mytickets">My Tickets</a></li>
          <li><i class="fa fa-shopping-cart"></i> My Purchases</li>
          <li><i class="fa fa-music"></i><a href="<?php echo URLROOT; ?>/music_library/musiclibrary">Music Library</a></li>
        </ul>
      </nav>
    </aside>


    <!-- Main Content -->
    <main class="md_main-content">
      <!-- Top Bar -->
      <header class="md_topbar">
        <h2 class="md_topbar-title">Welcome Back!</h2>
        <div class="md_topbar-actions">
          <div class="md_search-box">
            <input type="text" placeholder="Search events, artists, or songs...">
            <i class="fa fa-search"></i>
          </div>
        </div>
      </header>

      <!-- Member Info and Stats -->
      <section class="md_profile-card">
        <div class="md_profile-left">
          <img src="/images/default-avatar.png" alt="<?php echo $data['member_info']['username']; ?>">
          <div>
            <div class="md_profile-name"><?php echo $data['member_info']['username']; ?></div>
            <div class="md_profile-role">Premium Member</div>
          </div>
        </div>
        <!-- <button class="md_edit-profile-btn"><i class="fa fa-edit"></i> Edit Profile</button> -->
        <div class="md_profile-stats">
          <div>
            <div class="md_stat-number"><?php echo count($data['playlists']); ?></div>
            <div class="md_stat-label">Playlists</div>
          </div>
          <div>
            <div class="md_stat-number"><?php echo count($data['recent_activities']); ?></div>
            <div class="md_stat-label">Recent Activity</div>
          </div>
          <div>
            <div class="md_stat-number">7</div>
            <div class="md_stat-label">Wishlist</div>
          </div>
        </div>
      </section>

    <!-- Recent Activity -->
    <section class="md_section">
    <div class="md_section-header">
        <h3 class="md_section-title">Recent Activity</h3>
    </div>
    <div class="md_activity-list">
        <?php foreach($data['recent_activities'] as $activity): ?>
        <div class="md_activity-item">
            <span class="md_activity-icon">
            <?php
                $type = strtolower(trim($activity['type']));
                switch($type) {
                case 'ticket':
                    echo '<i class="fa fa-ticket-alt"></i>';
                    break;
                case 'purchase':
                    echo '<i class="fa fa-shopping-cart"></i>';
                    break;
                case 'like':
                case 'liked':
                    echo '<i class="fa fa-heart"></i>';
                    break;
                case 'created':
                    echo '<i class="fa fa-plus-circle"></i>';
                    break;
                case 'attended':
                    echo '<i class="fa fa-calendar-check"></i>';
                    break;
                default:
                    echo '<i class="fa fa-info-circle"></i>';
                }
            ?>
            </span>
            <div class="md_activity-details">
            <p><?php echo $activity['details']; ?></p>
            <small><?php echo $activity['date']; ?></small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    </section>

     <!-- Saved Events -->
     <section class="md_section">
        <div class="md_section-header">
          <h3 class="md_section-title">Saved Events</h3>
          <a href="#" class="md_section-link">View All</a>
        </div>
        <div class="md_cards-row">
          <div class="md_event-card">
            <div class="md_event-date">Mar 15</div>
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80" alt="Summer Music Festival">
            <div class="md_event-info">
              <div class="md_event-title">Summer Music Festival</div>
              <div class="md_event-meta"><i class="fa fa-map-marker-alt"></i> Central Park, New York</div>
              <div class="md_event-meta"><i class="fa fa-clock"></i> 7:00 PM EST</div>
              <button class="md_details-btn">View Details</button>
            </div>
          </div>
          <div class="md_event-card">
            <div class="md_event-date">Mar 22</div>
            <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" alt="Electronic Night">
            <div class="md_event-info">
              <div class="md_event-title">Electronic Night</div>
              <div class="md_event-meta"><i class="fa fa-map-marker-alt"></i> Club Matrix</div>
              <div class="md_event-meta"><i class="fa fa-clock"></i> 8:00 PM EST</div>
              <button class="md_details-btn">View Details</button>
            </div>
          </div>
          <div class="md_event-card">
            <div class="md_event-date">Mar 28</div>
            <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=400&q=80" alt="Jazz Evening">
            <div class="md_event-info">
              <div class="md_event-title">Jazz Evening</div>
              <div class="md_event-meta"><i class="fa fa-map-marker-alt"></i> Blue Note Jazz Club</div>
              <div class="md_event-meta"><i class="fa fa-clock"></i> 8:00 PM EST</div>
              <button class="md_details-btn">View Details</button>
            </div>
          </div>
        </div>
      </section>

      <!-- My Playlists -->
      <section class="md_section">
        <div class="md_section-header">
          <h3 class="md_section-title">My Playlists</h3>
          <a href="#" class="md_section-link"><i class="fa fa-plus"></i> Add New</a>
        </div>
        <div class="md_playlist-library">
        <?php foreach($data['playlists'] as $playlist): ?>
            <div class="md_playlist-row">
            <div class="md_playlist-play">
                <i class="fa fa-play"></i>
            </div>
            <div class="md_playlist-info">
                <div class="md_playlist-title"><?php echo $playlist['name']; ?></div>
                <div class="md_playlist-meta">
                <?php echo $playlist['songs_count']; ?> Songs
                </div>
            </div>
            <div class="md_playlist-created">
                <?php echo $playlist['created_at']; ?>
            </div>
            <div class="md_playlist-actions">
                <i class="fa fa-edit" title="Edit"></i>
                <i class="fa fa-trash" title="Delete"></i>
                <i class="fa fa-heart"></i>
            </div>
            </div>
        <?php endforeach; ?>
        </div>
      </section>

      <!-- Reccomended Merchandise -->
      <section class="md_section">
        <div class="md_section-header">
          <h3 class="md_section-title">Reccomend for you</h3>
          <a href="#" class="md_section-link">View All</a>
        </div>
        <div class="md_cards-row">
          <div class="md_merch-card">
            <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=200&q=80" alt="Vintage Band Tee">
            <div class="md_merch-title">Vintage Band Tee</div>
            <div class="md_merch-price">$29.99</div>
            <button class="md_add-btn">Add to Cart</button>
          </div>
          <div class="md_merch-card">
            <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=200&q=80" alt="Wireless Headphones">
            <div class="md_merch-title">Wireless Headphones</div>
            <div class="md_merch-price">$199.99</div>
            <button class="md_add-btn">Add to Cart</button>
          </div>
          <div class="md_merch-card">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80" alt="Limited Edition Vinyl">
            <div class="md_merch-title">Limited Edition Vinyl</div>
            <div class="md_merch-price">$49.99</div>
            <button class="md_add-btn">Add to Cart</button>
          </div>
          <div class="md_merch-card">
            <img src="https://images.unsplash.com/photo-1526178613658-3f1622045544?auto=format&fit=crop&w=200&q=80" alt="Festival Poster">
            <div class="md_merch-title">Festival Poster</div>
            <div class="md_merch-price">$24.99</div>
            <button class="md_add-btn">Add to Cart</button>
          </div>
        </div>
      </section>

    </main>
  </div>
</body>
<?php require APPROOT . '/views/inc/footer.php'; ?> 