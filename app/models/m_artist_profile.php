<?php
class m_artist_profile {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    public function getArtistProfile($artist_id) {
        $this->db->query("SELECT * FROM artist WHERE Artist_id = :id");
        $this->db->bind(':id', $artist_id);
        return $this->db->single();
    }

    public function updateVerificationDocument($artist_id, $filename) {
        $this->db->query("UPDATE artist SET verification_document = :doc, Verification_status = 'pending' WHERE Artist_id = :id");
        $this->db->bind(':doc', $filename);
        $this->db->bind(':id', $artist_id);
        return $this->db->execute();
    }

    public function updateArtistProfile($artist_id, $data) {
        $this->db->query("UPDATE artist SET 
            Username = :username, 
            Email = :email, 
            Phone_number = :phone, 
            Address = :address, 
            Genre = :genre
            WHERE Artist_id = :id");
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone_number']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':genre', $data['genre']);
        $this->db->bind(':id', $artist_id);
        return $this->db->execute();
    }

    public function updateArtistProfilePic($artist_id, $profilePic) {
        $this->db->query("UPDATE artist SET profile_pic = :profilePic WHERE Artist_id = :id");
        $this->db->bind(':profilePic', $profilePic);
        $this->db->bind(':id', $artist_id);
        return $this->db->execute();
    }

    public function updateArtistPassword($artist_id, $hashedPassword) {
        $this->db->query("UPDATE artist SET Password = :password WHERE Artist_id = :id");
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $artist_id);
        return $this->db->execute();
    }
    
    
}
?>
