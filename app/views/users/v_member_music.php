<?php require APPROOT . '/views/inc/header6.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>

<div class="music-container">
    <!-- Search Section -->
    <div class="music-search-section">
        <form action="<?php echo URLROOT; ?>/music/search" method="GET">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="Search artists or albums..." class="search-input">
                <button type="submit" class="search-button">Search</button>
            </div>
        </form>
    </div>

    <!-- Popular Albums Section -->
    <section class="albums-section">
        <div class="section-header">
            <h2>Popular Albums</h2>
            <!-- <a href="<?php echo URLROOT; ?>/music/albums" class="view-all">View All</a> -->
        </div>
        
        <div class="albums-grid">
            <?php foreach($data['albums'] as $album): ?>
                <div class="album-card" data-album-id="<?php echo $album->album_id; ?>">
                    <div class="album-cover-container">
                        <img src="<?php echo URLROOT; ?>/public/uploads/img/<?php echo !empty($album->album_cover) ? $album->album_cover : 'default-album.jpg'; ?>" alt="<?php echo htmlspecialchars($album->album_name); ?>" class="album-cover">
                        
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

    <!-- Popular Artists Section -->
    <section class="artists-section">
        <div class="section-header">
            <h2>Popular Artists</h2>
            <!-- <a href="<?php echo URLROOT; ?>/music/artists" class="view-all">View All</a> -->
        </div>
        
        <div class="artists-grid">
            <?php foreach($data['artists'] as $artist): ?>
                <div class="artist-card">
                    <a href="<?php echo URLROOT; ?>/music/artist/<?php echo $artist->Artist_id; ?>" class="artist-link">
                        <div class="artist-image-container">
                            <img src="<?php echo URLROOT; ?>/public/uploads/img/<?php echo !empty($artist->profile_pic) ? $artist->profile_pic : 'default-artist.jpg'; ?>" alt="<?php echo htmlspecialchars($artist->Username); ?>" class="artist-image">
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

    <!-- Now Playing Bar (Fixed at bottom) -->
    <div class="now-playing-bar">
        <div class="now-playing-left">
            <img src="<?php echo URLROOT; ?>/public/uploads/img/" alt="Now Playing" id="now-playing-cover" class="now-playing-thumbnail">
            <div class="now-playing-info">
                <h4 id="now-playing-title">Not Playing</h4>
                <p id="now-playing-artist">Select a song to play</p>
            </div>
        </div>
        
        <div class="now-playing-center">
            <div class="player-controls">
                <button id="shuffle-btn" class="player-control-btn">
                    <i class="fas fa-random"></i>
                </button>
                <button id="prev-track-btn" class="player-control-btn">
                    <i class="fas fa-step-backward"></i>
                </button>
                <button id="play-pause-btn" class="player-control-btn play-btn">
                    <i class="fas fa-play"></i>
                </button>
                <button id="next-track-btn" class="player-control-btn">
                    <i class="fas fa-step-forward"></i>
                </button>
                <button id="repeat-btn" class="player-control-btn">
                    <i class="fas fa-redo-alt"></i>
                </button>
            </div>
            
            <div class="progress-container">
                <span id="current-time">0:00</span>
                <div class="progress-bar">
                    <div class="progress" id="song-progress"></div>
                </div>
                <span id="total-time">0:00</span>
            </div>
        </div>
        
        <div class="now-playing-right">
            <button id="volume-btn" class="player-control-btn">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="volume-slider">
                <div class="volume-bar">
                    <div class="volume" id="volume-level"></div>
                </div>
            </div>
            <button id="queue-btn" class="player-control-btn">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    <!-- Hidden Audio Element -->
    <audio id="audio-player"></audio>
</div>

<!-- JavaScript for Music Player -->
<script src="<?php echo URLROOT; ?>/public/js/music-player.js"></script>

<?php require APPROOT . '/views/inc/footer.php'; ?>

