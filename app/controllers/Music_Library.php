<?php
class Music_Library extends Controller {
    private $userModel;
    private $musicModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }
        $this->userModel = $this->model('m_users');
        $this->musicModel = $this->model('m_musiclibrary');
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $memberInfo = $this->userModel->getUserData($userId);

        // Fetch albums with artist info
        $albums = $this->musicModel->getAlbumsWithArtists();

        $data = [
            'member_info' => [
                'username' => $memberInfo->Username,
                'email' => $memberInfo->email,
                'profile_pic' => $memberInfo->profile_pic
            ],
            'albums' => $albums
        ];
        $this->view('users/v_member_music_library', $data);
    }

    public function musiclibrary() {
        // This can be removed as index() handles the functionality
        $this->index();
    }
}
?>



