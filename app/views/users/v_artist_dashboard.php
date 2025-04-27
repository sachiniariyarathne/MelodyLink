<?php require APPROOT . '/views/inc/header4.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_artist.php'; ?>


<head>
    <title>My Releases - Artist Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<div class="md_dashboard-container">
    <aside class="md_sidebar">
        <div class="md_profile-section">
            <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo $data['profile_pic']; ?>" alt="Profile" class="md_profile-avatar">
            <h2><?php echo $data['username']; ?></h2>
            <p>Verified Artist</p>
        </div>
        <nav class="md_sidebar-nav">
            <ul>
                <li class="md_active"><a href="<?php echo URLROOT; ?>/artist_dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/Artist_Releases/artist_releases"><i class="fa fa-music"></i> My Releases</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist/ratings"><i class="fa fa-star"></i> Ratings & Reviews</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist/communities"><i class="fa fa-users"></i>Communities</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist/requests"><i class="fa fa-bell"></i> Requests</a></li>
            </ul>
        </nav>
    </aside>


    <main class="md_main-content">
        <!-- Top Bar -->
        <div class="md_topbar">
            <h1 class="md_topbar-title">Welcome <?php echo $data['username']; ?>!</h1>
            <div class="md_topbar-actions">
                <div class="md_search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Search releases...">
                </div>
            
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="md_stats-grid">
            <div class="md_stat-card">
                <i class="fa fa-eye"></i>
                <div>
                    <span class="md_stat-number"><?php echo number_format($data['stats']['views']); ?></span>
                    <span class="md_stat-label">Total Views</span>
                </div>
            </div>
            <div class="md_stat-card">
                <i class="fa fa-download"></i>
                <div>
                    <span class="md_stat-number"><?php echo number_format($data['stats']['downloads']); ?></span>
                    <span class="md_stat-label">Downloads</span>
                </div>
            </div>
            <div class="md_stat-card">
                <i class="fa fa-music"></i>
                <div>
                    <span class="md_stat-number"><?php echo number_format($data['stats']['songs']); ?></span>
                    <span class="md_stat-label">Songs Released</span>
                </div>
            </div>
            <div class="md_stat-card">
                <i class="fa fa-users"></i>
                <div>
                    <span class="md_stat-number"><?php echo number_format($data['stats']['communities']); ?></span>
                    <span class="md_stat-label">Communities</span>
                </div>
            </div>
        </div>

        <!-- Recent Releases Section -->
        <section class="md_section">
            <div class="md_section-header">
                <h2 class="md_section-title">Recent Releases</h2>
                <a href="<?php echo URLROOT; ?>/artist/releases" class="md_section-link">View All</a>
            </div>
            <div class="md_cards-row">
                <?php foreach($data['recent_releases'] as $release): ?>
                <div class="md_release-card">
                    <div class="md_release-cover">
                        <img src="<?php echo URLROOT; ?>/public/uploads/releases/<?php echo $release['cover']; ?>" alt="<?php echo $release['title']; ?>">
                        <div class="md_release-overlay">
                            <button class="md_play-btn"><i class="fa fa-play"></i></button>
                        </div>
                    </div>
                    <div class="md_release-info">
                        <h3 class="md_release-title"><?php echo $release['title']; ?></h3>
                        <div class="md_release-meta">
                            <span><?php echo date('M Y', strtotime($release['release_date'])); ?></span>
                            <span><?php echo number_format($release['streams']); ?> Streams</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Top Communities Section -->
        <section class="md_section">
            <div class="md_section-header">
                <h2 class="md_section-title">Reccomended Communities</h2>
                <a href="<?php echo URLROOT; ?>/artist/communities" class="md_section-link">Manage</a>
            </div>
            <div class="md_communities-grid">
                <?php foreach($data['communities'] as $community): ?>
                <div class="md_community-card">
                    <img src="<?php echo URLROOT; ?>/public/uploads/communities/<?php echo $community['logo']; ?>" alt="<?php echo $community['name']; ?>">
                    <h4><?php echo $community['name']; ?></h4>
                    <p><?php echo number_format($community['members']); ?> Members</p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 