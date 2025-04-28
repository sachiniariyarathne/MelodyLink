<?php
class m_Contact {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function saveContact($data) {
        $this->db->query('INSERT INTO contacts (name, email, subject, message) 
                         VALUES (:name, :email, :subject, :message)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message', $data['message']);

        return $this->db->execute();
    }

    public function getAllContacts() {
        $this->db->query('SELECT * FROM contacts ORDER BY created_at DESC');
        return $this->db->resultSet();
    }
}
?>
