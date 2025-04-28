<?php
class M_Password_Reset {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Find user by email across all user tables
    public function findUserByEmail($email) {
        // Check member table
        $this->db->query("SELECT 'member' AS user_type, member_id, email FROM member WHERE email = :email");
        $this->db->bind(':email', $email);
        $member = $this->db->single();

        // Check artist table
        if (!$member) {
            $this->db->query("SELECT 'artist' AS user_type, user_id, email FROM artist WHERE email = :email");
            $this->db->bind(':email', $email);
            $member = $this->db->single();
        }

        // Check supplier table
        if (!$member) {
            $this->db->query("SELECT 'supplier' AS user_type, user_id, email FROM supplier WHERE email = :email");
            $this->db->bind(':email', $email);
            $member = $this->db->single();
        }

        // Check Merchandise vendor table

        if (!$member) {
            $this->db->query("SELECT 'merchandise_vendor' AS user_type, user_id, email FROM merchandise_vendor WHERE email = :email");
            $this->db->bind(':email', $email);
            $member = $this->db->single();
        }

        // Check event organizer table
        if (!$member) {
            $this->db->query("SELECT 'event_organiser' AS user_type, user_id, email FROM event_organiser WHERE email = :email");
            $this->db->bind(':email', $email);
            $member = $this->db->single();
        }

        return $member;
    }

    // Store password reset token
    public function storeResetToken($userId, $userType, $token) {
        // Mapping of user types to tables and ID columns
        $tableMap = [
            'member' => ['table' => 'member', 'id_column' => 'member_id'],
            'artist' => ['table' => 'artist', 'id_column' => 'user_id'],
            'supplier' => ['table' => 'supplier', 'id_column' => 'user_id'],
            'event_organizer' => ['table' => 'event_organiser', 'id_column' => 'user_id']
            'merchandise_vendor' => ['table' => 'merchandise_vendor', 'id_column' => 'user_id']
        ];

        // Validate user type
        if (!isset($tableMap[$userType])) {
            return false;
        }

        $tableInfo = $tableMap[$userType];

        // Prepare the query
        // In m_password_reset.php
$this->db->query("UPDATE {$tableInfo['table']} SET reset_token = :token, reset_token_expires = :expires WHERE {$tableInfo['id_column']} = :id");

// Set timezone to Sri Lanka
date_default_timezone_set('Asia/Colombo');

// Create token expiration 1 hour from now in Sri Lankan time
$this->db->bind(':expires', date('Y-m-d H:i:s', strtotime('+1 hour')));
        
        // Bind values
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', date('Y-m-d H:i:s', strtotime('+1 hour'))); // Token expires in 1 hour
        $this->db->bind(':id', $userId);

        // Execute the query
        return $this->db->execute();
    }

    // Validate reset token
    public function validateResetToken($token) {
        $tables = [
            ['table' => 'member', 'id_column' => 'member_id'],
            ['table' => 'artist', 'id_column' => 'user_id'],
            ['table' => 'supplier', 'id_column' => 'user_id'],
            ['table' => 'event_organiser', 'id_column' => 'user_id']
            ['table' => 'merchandise_vendor', 'id_column' => 'user_id']
        ];
        
        foreach ($tables as $tableInfo) {
            $this->db->query("SELECT {$tableInfo['id_column']} AS id, email, reset_token, reset_token_expires, 
                              :table_name AS user_type 
                              FROM {$tableInfo['table']} 
                              WHERE reset_token = :token 
                              AND reset_token_expires > NOW()");
            $this->db->bind(':token', $token);
            $this->db->bind(':table_name', $tableInfo['table']);
            $user = $this->db->single();
    
            if ($user) {
                // Normalize user type
                $user->user_type = $this->normalizeUserType($tableInfo['table']);
                return $user;
            }
        }
    
        return false;
    }
    
    // Helper method to normalize user type
    private function normalizeUserType($table) {
        $typeMap = [
            'member' => 'member',
            'artist' => 'artist',
            'supplier' => 'supplier',
            'event_organisers' => 'event_organiser'
            'merchandise_vendor' => 'merchandise_vendor'
        ];
        return $typeMap[$table] ?? $table;
    }

    // Reset password
    public function resetPassword($userId, $userType, $newPassword) {
        // Mapping of user types to tables and ID columns
        $tableMap = [
            'member' => ['table' => 'member', 'id_column' => 'member_id'],
            'artist' => ['table' => 'artist', 'id_column' => 'user_id'],
            'supplier' => ['table' => 'suppliers', 'id_column' => 'user_id'],
            'event_organizer' => ['table' => 'event_organiser', 'id_column' => 'user_id']
            'merchandise_vendor' => ['table' => 'merchandise_vendor', 'id_column' => 'user_id']
        ];

        // Validate user type
        if (!isset($tableMap[$userType])) {
            return false;
        }

        $tableInfo = $tableMap[$userType];

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Prepare the query
        $this->db->query("UPDATE {$tableInfo['table']} SET password = :password, reset_token = NULL, reset_token_expires = NULL WHERE {$tableInfo['id_column']} = :id");
        
        // Bind values
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $userId);

        // Execute the query and clear the reset token
        return $this->db->execute();
    }
}
?>