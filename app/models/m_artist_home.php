<?php
class m_artist_home {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    public function getArtistInfo($artist_id) {
        $this->db->query("SELECT * FROM artist WHERE Artist_id = :id");
        $this->db->bind(':id', $artist_id);
        return $this->db->single();
    }

    public function getStats($artist_id) {
        // Songs
        $this->db->query("SELECT COUNT(*) as total FROM songs WHERE artist_id = :id");
        $this->db->bind(':id', $artist_id);
        $songs = $this->db->single()->total ?? 0;

        // Communities
        $this->db->query("SELECT COUNT(*) as total FROM artist_communities WHERE artist_id = :id");
        $this->db->bind(':id', $artist_id);
        $communities = $this->db->single()->total ?? 0;

        // Reviews
        $this->db->query("SELECT COUNT(*) as total FROM reviews WHERE artist_id = :id");
        $this->db->bind(':id', $artist_id);
        $reviews = $this->db->single()->total ?? 0;

        return [
            'songs' => $songs,
            'communities' => $communities,
            'reviews' => $reviews
        ];
    }

    public function getLatestSongs($artist_id, $limit = 4) {
        $this->db->query("SELECT s.title, s.cover, s.released_at, a.album_name as album 
                          FROM songs s JOIN albums a ON s.album_id = a.album_id
                          WHERE s.artist_id = :id
                          ORDER BY s.released_at DESC LIMIT $limit");
        $this->db->bind(':id', $artist_id);
        return $this->db->resultSet();
    }

    public function getCommunities($artist_id, $limit = 3) {
        $this->db->query("SELECT c.name, c.description, c.members
                          FROM communities c
                          JOIN artist_communities ac ON c.community_id = ac.community_id
                          WHERE ac.artist_id = :id
                          LIMIT $limit");
        $this->db->bind(':id', $artist_id);
        return $this->db->resultSet();
    }
}
?>
