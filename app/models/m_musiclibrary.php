<?php
class m_musiclibrary {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    // Get all albums with artist info
    public function getAlbumsWithArtists() {
        $this->db->query(
            "SELECT a.album_id, a.album_name, a.release_date, a.genre, a.album_cover, a.featured_artists,
                    ar.Artist_id, ar.Username AS artist_username
             FROM albums a
             JOIN artist ar ON a.artist_id = ar.Artist_id
             ORDER BY a.release_date DESC"
        );
        return $this->db->resultSet();
    }
}
?>
