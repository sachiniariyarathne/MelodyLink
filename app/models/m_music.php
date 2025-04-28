<?php
class m_music {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getPopularAlbums($limit = 6) {
        $this->db->query('SELECT a.*, art.Username as artist_name, art.profile_pic as artist_image 
                          FROM albums a 
                          JOIN artist art ON a.artist_id = art.Artist_id 
                          ORDER BY a.album_id DESC 
                          LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getPopularArtists($limit = 6) {
        $this->db->query('SELECT * FROM artist ORDER BY Artist_id DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function searchAlbums($searchTerm) {
        $this->db->query('SELECT a.*, art.Username as artist_name FROM albums a 
                          JOIN artist art ON a.artist_id = art.Artist_id 
                          WHERE a.album_name LIKE :search OR art.Username LIKE :search');
        $this->db->bind(':search', '%' . $searchTerm . '%');
        return $this->db->resultSet();
    }

    public function searchArtists($searchTerm) {
        $this->db->query('SELECT * FROM artist WHERE Username LIKE :search');
        $this->db->bind(':search', '%' . $searchTerm . '%');
        return $this->db->resultSet();
    }

    public function getAlbumById($albumId) {
        $this->db->query('SELECT a.*, art.Username as artist_name FROM albums a 
                          JOIN artist art ON a.artist_id = art.Artist_id 
                          WHERE a.album_id = :id');
        $this->db->bind(':id', $albumId);
        return $this->db->single();
    }

    public function getArtistById($artistId) {
        $this->db->query('SELECT * FROM artist WHERE Artist_id = :id');
        $this->db->bind(':id', $artistId);
        return $this->db->single();
    }

    public function getTracksForAlbum($albumId) {
        $this->db->query('SELECT * FROM albums WHERE album_id = :album_id');
        $this->db->bind(':album_id', $albumId);
        $album = $this->db->single();
        
        if ($album && !empty($album->music_track)) {
            // This assumes music_track contains one track - modify if it's multiple tracks
            return [$album->music_track];
        }
        
        return [];
    }
}
?>
