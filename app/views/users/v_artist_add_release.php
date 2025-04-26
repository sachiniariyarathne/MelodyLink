<?php require APPROOT . '/views/inc/header4.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_artist.php'; ?>
<head>
    <meta charset="UTF-8">
    <title>Add New Release - Artist Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/artist_add_release_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="artist-add-release-container">
    <h1 class="artist-add-title"><i class="fa fa-plus"></i> Add New Release</h1>
    <form class="artist-add-form" action="<?php echo URLROOT; ?>/Artist_Releases/add" method="POST" enctype="multipart/form-data">
        <div class="artist-add-fields">
            <div class="artist-add-field">
                <label for="album_name">Album Name<span>*</span></label>
                <input type="text" name="album_name" id="album_name" value="<?php echo $data['album_name'] ?? ''; ?>" required>
                <div class="artist-add-error"><?php echo $data['album_name_err'] ?? ''; ?></div>
            </div>
            <div class="artist-add-field">
                <label for="release_date">Release Date<span>*</span></label>
                <input type="date" name="release_date" id="release_date" value="<?php echo $data['release_date'] ?? ''; ?>" required>
                <div class="artist-add-error"><?php echo $data['release_date_err'] ?? ''; ?></div>
            </div>
            <div class="artist-add-field">
                <label for="genre">Genre<span>*</span></label>
                <input type="text" name="genre" id="genre" value="<?php echo $data['genre'] ?? ''; ?>" required>
                <div class="artist-add-error"><?php echo $data['genre_err'] ?? ''; ?></div>
            </div>
            <div class="artist-add-field">
                <label for="featured_artists">Featured Artists</label>
                <input type="text" name="featured_artists" id="featured_artists" value="<?php echo $data['featured_artists'] ?? ''; ?>">
            </div>
            <div class="artist-add-field">
                <label for="album_cover">Album Cover<span>*</span></label>
                <input type="file" name="album_cover" id="album_cover" accept="image/*" required>
                <div class="artist-add-hint">JPG/PNG, max 2MB</div>
                <div class="artist-add-error"><?php echo $data['album_cover_err'] ?? ''; ?></div>
            </div>
            <div class="artist-add-field">
                <label for="music_track">Music Track<span>*</span></label>
                <input type="file" name="music_track" id="music_track" accept="audio/*" required>
                <div class="artist-add-hint">MP3/WAV, max 10MB</div>
                <div class="artist-add-error"><?php echo $data['music_track_err'] ?? ''; ?></div>
            </div>
        </div>
        <div class="artist-add-actions">
            <a href="<?php echo URLROOT; ?>/Artist_Releases" class="artist-add-cancel">Cancel</a>
            <button type="submit" class="artist-add-submit"><i class="fa fa-check"></i> Save Release</button>
        </div>
    </form>
</div>
</body>
</html>

<?php require APPROOT . '/views/inc/footer.php'; ?> 