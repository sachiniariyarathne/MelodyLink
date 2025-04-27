<?php
class m_artist_releases {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    public function getArtistInfo($artist_id) {
        $this->db->query("SELECT Username, profile_pic FROM artist WHERE Artist_id = :id");
        $this->db->bind(':id', $artist_id);
        return $this->db->single();
    }
    
    // Get all albums by artist
    public function getAlbumsByArtist($artist_id) {
        $this->db->query("SELECT * FROM albums WHERE artist_id = :artist_id ORDER BY release_date DESC");
        $this->db->bind(':artist_id', $artist_id);
        return $this->db->resultSet();
    }
    
    // Add new album
    public function addAlbum($data) {
        $this->db->query("INSERT INTO albums (album_name, release_date, genre, featured_artists, artist_name, artist_id, album_cover, music_track) 
                          VALUES (:album_name, :release_date, :genre, :featured_artists, :artist_name, :artist_id, :album_cover, :music_track)");
        
        // Bind values
        $this->db->bind(':album_name', $data['album_name']);
        $this->db->bind(':release_date', $data['release_date']);
        $this->db->bind(':genre', $data['genre']);
        $this->db->bind(':featured_artists', $data['featured_artists']);
        $this->db->bind(':artist_name', $data['artist_name']);
        $this->db->bind(':artist_id', $data['artist_id']);
        $this->db->bind(':album_cover', $data['album_cover']);
        $this->db->bind(':music_track', $data['music_track']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Update album
    public function updateAlbum($data) {
        $this->db->query("UPDATE albums 
                          SET album_name = :album_name, release_date = :release_date, 
                              genre = :genre, featured_artists = :featured_artists, 
                              album_cover = :album_cover, music_track = :music_track 
                          WHERE album_id = :album_id AND artist_id = :artist_id");
        
        // Bind values
        $this->db->bind(':album_name', $data['album_name']);
        $this->db->bind(':release_date', $data['release_date']);
        $this->db->bind(':genre', $data['genre']);
        $this->db->bind(':featured_artists', $data['featured_artists']);
        $this->db->bind(':album_cover', $data['album_cover']);
        $this->db->bind(':music_track', $data['music_track']);
        $this->db->bind(':album_id', $data['album_id']);
        $this->db->bind(':artist_id', $data['artist_id']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Delete album
    public function deleteAlbum($album_id) {
        $this->db->query("DELETE FROM albums WHERE album_id = :album_id");
        $this->db->bind(':album_id', $album_id);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get album by ID
    public function getAlbumById($album_id) {
        $this->db->query("SELECT * FROM albums WHERE album_id = :album_id");
        $this->db->bind(':album_id', $album_id);
        return $this->db->single();
    }
    
    // Check if album name exists for this artist
    public function albumNameExists($album_name, $artist_id) {
        $this->db->query("SELECT COUNT(*) FROM albums WHERE album_name = :album_name AND artist_id = :artist_id");
        $this->db->bind(':album_name', $album_name);
        $this->db->bind(':artist_id', $artist_id);
        
        if ($this->db->single()->{'COUNT(*)'} > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
