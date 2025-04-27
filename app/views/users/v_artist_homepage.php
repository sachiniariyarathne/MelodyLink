<?php require APPROOT . '/views/inc/header4.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_artist.php'; ?>

<head>
    <meta charset="UTF-8">
    <title>Artist Home | MelodyLink</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/artist_homepage_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="artist-homepage-container">
        <!-- Hero Section -->
        <section class="artist-hero">
            <div class="hero-content">
                <h2>Welcome back, <?php echo htmlspecialchars($data['artist_info']['name']); ?>!</h2>
                <p>Share your music, connect with fans, and grow your artist brand on MelodyLink.</p>
                <a href="<?php echo URLROOT; ?>/artist_releases/new" class="release-btn">
                    <i class="fa fa-upload"></i> Release New Song
                </a>
            </div>
            <div class="artist-stats">
                <div>
                    <span><?php echo $data['stats']['songs']; ?></span>
                    <p>Songs Released</p>
                </div>
                <div>
                    <span><?php echo $data['stats']['communities']; ?></span>
                    <p>Communities Joined</p>
                </div>
                <div>
                    <span><?php echo $data['stats']['reviews']; ?></span>
                    <p>Reviews</p>
                </div>
            </div>
        </section>

        <!-- Latest Releases -->
        <section class="artist-section">
            <div class="section-header">
                <h3><i class="fa fa-music"></i> Latest Releases</h3>
                <a href="<?php echo URLROOT; ?>/artist_releases" class="see-more-btn">See All</a>
            </div>
            <div class="release-grid">
                <?php foreach($data['latest_songs'] as $song): ?>
                <div class="release-card">
                    <div class="release-cover">
                        <img src="<?php echo URLROOT; ?>/public/uploads/songs/<?php echo $song['cover']; ?>" alt="Song Cover">
                    </div>
                    <div class="release-info">
                        <h4><?php echo htmlspecialchars($song['title']); ?></h4>
                        <p><?php echo htmlspecialchars($song['album']); ?></p>
                        <span class="release-date"><?php echo date('M d, Y', strtotime($song['released_at'])); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty($data['latest_songs'])): ?>
                <div class="no-content">No songs released yet.</div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Community Highlights -->
        <section class="artist-section">
            <div class="section-header">
                <h3><i class="fa fa-users"></i> Community Highlights</h3>
                <a href="<?php echo URLROOT; ?>/artist_communities" class="see-more-btn">Join Communities</a>
            </div>
            <div class="community-grid">
                <?php foreach($data['communities'] as $community): ?>
                <div class="community-card">
                    <h4><?php echo htmlspecialchars($community['name']); ?></h4>
                    <p><?php echo htmlspecialchars($community['description']); ?></p>
                    <span class="members"><i class="fa fa-user-friends"></i> <?php echo $community['members']; ?> members</span>
                </div>
                <?php endforeach; ?>
                <?php if(empty($data['communities'])): ?>
                <div class="no-content">Not a member of any community yet.</div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</body>

<?php require APPROOT . '/views/inc/footer.php'; ?> 
