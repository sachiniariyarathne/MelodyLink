<?php
class m_artist_dashboard {
    private $db;
    public function __construct() {
        $this->db = new Database;
    }

    public function getArtistProfile($artist_id) {
        $this->db->query("SELECT * FROM artist WHERE Artist_id = :id");
        $this->db->bind(':id', $artist_id);
        return $this->db->single();
    }

    public function getArtistStats($artist_id) {
        return [
            'views' => $this->getTotalViews($artist_id),
            'downloads' => $this->getTotalDownloads($artist_id),
            'songs' => $this->getSongCount($artist_id),
            'communities' => $this->getCommunityCount($artist_id)
        ];
    }

    private function getTotalViews($artist_id) {
        $this->db->query("SELECT SUM(views) AS total FROM songs WHERE artist_id = :id");
        $this->db->bind(':id', $artist_id);
        return $this->db->single()->total ?? 0;
    }

    private function getTotalDownloads($artist_id) {
        $this->db->query("SELECT SUM(downloads) AS total FROM songs WHERE artist_id = :id");
        $this->db->bind(':id', $artist_id);
        return $this->db->single()->total ?? 0;
    }

    private function getSongCount($artist_id) {
        $this->db->query("SELECT COUNT(*) AS total FROM songs WHERE artist_id = :id");
        $this->db->bind(':id', $artist_id);
        return $this->db->single()->total ?? 0;
    }

    private function getCommunityCount($artist_id) {
        $this->db->query("SELECT COUNT(*) AS total FROM artist_communities WHERE artist_id = :id");
        $this->db->bind(':id', $artist_id);
        return $this->db->single()->total ?? 0;
    }

    public function getRecentReleases($artist_id, $limit = 4) {
        $this->db->query("SELECT * FROM songs WHERE artist_id = :id ORDER BY release_date DESC LIMIT $limit");
        $this->db->bind(':id', $artist_id);
        return $this->db->resultSet();
    }

    public function getArtistCommunities($artist_id, $limit = 4) {
        $this->db->query("SELECT c.* FROM communities c 
                        JOIN artist_communities ac ON c.community_id = ac.community_id 
                        WHERE ac.artist_id = :id 
                        ORDER BY c.members DESC 
                        LIMIT $limit");
        $this->db->bind(':id', $artist_id);
        return $this->db->resultSet();
    }
}
?>
