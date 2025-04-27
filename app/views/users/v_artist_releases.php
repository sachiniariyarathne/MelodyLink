<?php require APPROOT . '/views/inc/header4.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_artist.php'; ?>

<head>
    <title>My Releases - Artist Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="md_dashboard-container">
        <!-- Include the sidebar -->
    <aside class="md_sidebar">
        <div class="md_profile-section">
            <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo $data['profile_pic']; ?>" alt="Profile" class="md_profile-avatar">
            <h2><?php echo $data['username']; ?></h2>
            <p>Verified Artist</p>
        </div>
        <nav class="md_sidebar-nav">
            <ul>
                <li><a href="<?php echo URLROOT; ?>/Artist_Dashboard/artist_dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="md_active"><a href="<?php echo URLROOT; ?>/Artist_Releases/artist_releases"><i class="fa fa-music"></i> My Releases</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist/ratings"><i class="fa fa-star"></i> Ratings & Reviews</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist/communities"><i class="fa fa-users"></i>Communities</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist/requests"><i class="fa fa-bell"></i> Requests</a></li>
            </ul>
        </nav>
    </aside>

        
        <!-- Main Content -->
        <main class="md_main-content">
            <!-- Top Bar -->
            <div class="md_topbar">
                <h1 class="md_topbar-title">My Releases</h1>
                <div class="md_topbar-actions">
                    <button class="md_add-release-btn" onclick="location.href='<?php echo URLROOT; ?>/Artist_Releases/add'">
                        <i class="fa fa-plus"></i> Add New Release
                    </button>
                </div>
            </div>
            
            <!-- Releases Grid -->
            <div class="md_releases-grid">
                <?php if(!empty($data['albums'])): ?>
                    <?php foreach($data['albums'] as $album): ?>
                        <div class="md_release-card">
                            <div class="md_release-cover">
                                <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo $album->album_cover; ?>" alt="<?php echo $album->album_name; ?>">
                                <div class="md_release-actions">
                                    <a href="<?php echo URLROOT; ?>/Artist_Releases/edit/<?php echo $album->album_id; ?>" class="md_edit-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="<?php echo URLROOT; ?>/Artist_Releases/delete/<?php echo $album->album_id; ?>" method="POST" style="display: inline;">
                                        <button type="submit" class="md_delete-btn" onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="md_release-info">
                                <h3 class="md_release-title"><?php echo $album->album_name; ?></h3>
                                <div class="md_release-details">
                                    <span class="md_release-date"><?php echo date('M d, Y', strtotime($album->release_date)); ?></span>
                                    <span class="md_release-genre"><?php echo $album->genre; ?></span>
                                </div>
                                <?php if(!empty($album->featured_artists)): ?>
                                    <div class="md_featured-artists">
                                        <span>feat. <?php echo $album->featured_artists; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="md_no-releases">
                        <i class="fa fa-music"></i>
                        <p>You haven't released any albums yet.</p>
                        <a href="<?php echo URLROOT; ?>/Artist_Releases/add" class="md_add-first-btn">Add Your First Release</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
<?php require APPROOT . '/views/inc/footer.php'; ?> 
