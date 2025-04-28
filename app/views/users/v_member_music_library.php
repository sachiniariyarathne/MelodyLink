<?php require APPROOT . '/views/inc/header3.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>


<head>
    <meta charset="UTF-8">
    <title>My Music Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar (reuse your dashboard sidebar code here) -->
    <aside class="md_sidebar">
        <div class="md_profile-section">
        <?php $profilePic = !empty($member_info['profile_pic']) ? $member_info['profile_pic'] : 'default-avatar.png';?>
                <img 
                src="<?php echo URLROOT; ?>/public/uploads/img/<?php echo $profilePic; ?>" 
                alt="Profile Photo" 
                class="profile-avatar"
                style="width:90px;height:90px;border-radius:50%;object-fit:cover;margin-bottom:16px;">

            <h2><?php echo $data['member_info']['username']; ?></h2>
            <p><?php echo $data['member_info']['email']; ?></p>
        </div>
        <nav class="md_sidebar-nav">
            <ul>
                <li><a href="<?php echo URLROOT; ?>/users/dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/my_tickets/mytickets"><i class="fa fa-ticket-alt"></i> My Tickets</a></li>
                <li><a href="<?php echo URLROOT; ?>/Member_Purchases"><i class="fa fa-shopping-cart"></i> My Purchases</a></li>
                <li class="md_active"><a href="<?php echo URLROOT; ?>/music_library"><i class="fa fa-music"></i> Music Library</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="library-container">
        <header class="library-header">
            <h1>My Music Library</h1>
        </header>
        <section class="library-section">
            <div class="section-header">
                <h2>Recently Played</h2>
            </div>
            <div class="album-grid">
                <?php foreach($data['albums'] as $album): ?>
                    <div class="album-card">
                        <div class="album-cover">
                            <img src="<?php echo URLROOT; ?>/public/uploads/img/<?php echo $album->album_cover ?? 'default-album.jpg'; ?>" alt="<?php echo htmlspecialchars($album->album_name); ?>">
                        </div>
                        <div class="album-info">
                            <h3><?php echo htmlspecialchars($album->album_name); ?></h3>
                            <p>By <?php echo htmlspecialchars($album->artist_username); ?></p>
                            <p><?php echo htmlspecialchars($album->genre); ?> • <?php echo date('Y', strtotime($album->release_date)); ?></p>
                            <?php if (!empty($album->featured_artists)): ?>
                                <p class="featured">Feat. <?php echo htmlspecialchars($album->featured_artists); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>
</body>
<?php require APPROOT . '/views/inc/footer.php'; ?>
