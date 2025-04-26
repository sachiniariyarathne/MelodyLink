<?php
class m_Member {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Get all member emails for merchandise notifications
    public function getAllMemberEmails() {
        // Get all members with valid emails
        // Added improved email validation directly in the SQL query
        $this->db->query("SELECT email FROM member WHERE 
                         email IS NOT NULL AND 
                         email != '' AND 
                         email LIKE '%@%.%'");  // Basic validation to ensure email has @ and domain
        
        // Log the number of emails found
        $results = $this->db->resultSet();
        error_log('Found ' . count($results) . ' potentially valid member emails for notifications');
        
        return $results;
    }
    
    // Get member by ID
    public function getMemberById($id) {
        $this->db->query("SELECT * FROM member WHERE member_id = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Get member by email
    public function getMemberByEmail($email) {
        $this->db->query("SELECT * FROM member WHERE email = :email");
        $this->db->bind(':email', $email);
        
        return $this->db->single();
    }
    
    // Add new member
    public function register($data) {
        // Prepare query
        $this->db->query("INSERT INTO member (Username, Password, email, Phone_number, Address) VALUES (:username, :password, :email, :phone, :address)");
        
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        
        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Login member
    public function login($email, $password) {
        $this->db->query("SELECT * FROM member WHERE email = :email");
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if ($row) {
            $hashed_password = $row->Password;
            if (password_verify($password, $hashed_password)) {
                return $row;
            }
        }
        
        return false;
    }
    
    // Update member profile
    public function updateProfile($data) {
        // Prepare query
        $this->db->query("UPDATE member SET Username = :username, email = :email, Phone_number = :phone, Address = :address WHERE member_id = :id");
        
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        return $this->db->execute();
    }
    
    // Update member password
    public function updatePassword($id, $password) {
        // Prepare query
        $this->db->query("UPDATE member SET Password = :password WHERE member_id = :id");
        
        // Bind values
        $this->db->bind(':password', $password);
        $this->db->bind(':id', $id);
        
        // Execute
        return $this->db->execute();
    }
    
    // Check if email exists
    public function findMemberByEmail($email) {
        $this->db->query("SELECT * FROM member WHERE email = :email");
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        // Check if row is greater than 0
        return ($row) ? true : false;
    }
    
    // Store password reset token
    public function storeResetToken($email, $token, $expires) {
        $this->db->query("UPDATE member SET reset_token = :token, reset_token_expires = :expires WHERE email = :email");
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        $this->db->bind(':email', $email);
        
        return $this->db->execute();
    }
    
    // Verify reset token
    public function verifyResetToken($token) {
        $this->db->query("SELECT * FROM member WHERE reset_token = :token AND reset_token_expires > NOW()");
        $this->db->bind(':token', $token);
        
        return $this->db->single();
    }
    
    // Clear reset token after use
    public function clearResetToken($id) {
        $this->db->query("UPDATE member SET reset_token = NULL, reset_token_expires = NULL WHERE member_id = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Get all members (for admin)
    public function getAllMembers() {
        $this->db->query("SELECT * FROM member ORDER BY member_id DESC");
        
        return $this->db->resultSet();
    }
    
    // Delete member (for admin)
    public function deleteMember($id) {
        $this->db->query("DELETE FROM member WHERE member_id = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Update member subscription status
    public function updateSubscriptionStatus($id, $status) {
        $this->db->query("UPDATE member SET Subscription_status = :status WHERE member_id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Get subscription status
    public function getSubscriptionStatus($id) {
        $this->db->query("SELECT Subscription_status FROM member WHERE member_id = :id");
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        return $row ? $row->Subscription_status : null;
    }
}
?>