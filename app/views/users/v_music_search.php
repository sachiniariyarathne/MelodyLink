<?php require APPROOT . '/views/inc/header6.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>

<div class="music-container">
    <!-- Search Section -->
    <div class="music-search-section">
        <form action="<?php echo URLROOT; ?>/music/search" method="GET">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="Search artists or albums..." class="search-input" value="<?php echo htmlspecialchars($data['searchTerm']); ?>">
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    <div class="search-results">
        <h2>Search Results for "<?php echo htmlspecialchars($data['searchTerm']); ?>"</h2>

        <?php if(empty($data['albums']) && empty($data['artists'])): ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <p>No results found for "<?php echo htmlspecialchars($data['searchTerm']); ?>"</p>
                <p>Try a different search term or browse our popular albums and artists.</p>
            </div>
        <?php else: ?>
            <!-- Album Results -->
            <?php if(!empty($data['albums'])): ?>
                <section class="results-section">
                    <h3>Albums</h3>
                    <div class="albums-grid">
                        <?php foreach($data['albums'] as $album): ?>
                            <div class="album-card" data-album-id="<?php echo $album->album_id; ?>">
                                <div class="album-cover-container">
                                    <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo !empty($album->album_cover) ? $album->album_cover : 'default-album.jpg'; ?>" alt="<?php echo htmlspecialchars($album->album_name); ?>" class="album-cover">
                                    
                                    <div class="album-controls">
                                        <button class="album-control-btn prev-btn" title="Previous">
                                            <i class="fas fa-step-backward"></i>
                                        </button>
                                        <button class="album-control-btn play-btn" title="Play">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <button class="album-control-btn next-btn" title="Next">
                                            <i class="fas fa-step-forward"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="album-info">
                                    <h3 class="album-title"><?php echo htmlspecialchars($album->album_name); ?></h3>
                                    <p class="album-artist"><?php echo htmlspecialchars($album->artist_name); ?></p>
                                    
                                    <div class="album-actions">
                                        <button class="action-btn add-playlist" title="Add to Playlist">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div class="rating">
                                            <i class="far fa-star" data-rating="1"></i>
                                            <i class="far fa-star" data-rating="2"></i>
                                            <i class="far fa-star" data-rating="3"></i>
                                            <i class="far fa-star" data-rating="4"></i>
                                            <i class="far fa-star" data-rating="5"></i>
                                        </div>
                                        <button class="action-btn favorite" title="Add to Favorites">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Artist Results -->
            <?php if(!empty($data['artists'])): ?>
                <section class="results-section">
                    <h3>Artists</h3>
                    <div class="artists-grid">
                        <?php foreach($data['artists'] as $artist): ?>
                            <div class="artist-card">
                                <a href="<?php echo URLROOT; ?>/music/artist/<?php echo $artist->Artist_id; ?>" class="artist-link">
                                    <div class="artist-image-container">
                                        <img src="<?php echo URLROOT; ?>/public/uploads/<?php echo !empty($artist->profile_pic) ? $artist->profile_pic : 'default-artist.jpg'; ?>" alt="<?php echo htmlspecialchars($artist->Username); ?>" class="artist-image">
                                    </div>
                                    <h3 class="artist-name"><?php echo htmlspecialchars($artist->Username); ?></h3>
                                    <?php if(!empty($artist->Genre)): ?>
                                        <p class="artist-genre"><?php echo htmlspecialchars($artist->Genre); ?></p>
                                    <?php endif; ?>
                                </a>
                                <button class="follow-btn">
                                    <i class="fas fa-user-plus"></i> Follow
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Now Playing Bar (Same as in v_music.php) -->
    <div class="now-playing-bar">
        <!-- Same content as in v_music.php -->
    </div>

    <!-- Hidden Audio Element -->
    <audio id="audio-player"></audio>
</div>

<!-- JavaScript for Music Player -->
<script src="<?php echo URLROOT; ?>/public/js/music-player.js"></script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
