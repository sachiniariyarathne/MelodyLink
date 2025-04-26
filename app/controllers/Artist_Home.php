<?php
class Artist_Home extends Controller {
    private $artistHomeModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'artist') {
            redirect('users/login');
        }
        $this->artistHomeModel = $this->model('m_artist_home');
    }

    public function index() {
        $artist_id = $_SESSION['user_id'];
        $artist = $this->artistHomeModel->getArtistInfo($artist_id);

        $data = [
            'artist_info' => [
                'name' => $artist->Username,
                'profile_pic' => $artist->profile_pic ?? 'default-artist.png'
            ],
            'stats' => $this->artistHomeModel->getStats($artist_id),
            'latest_songs' => $this->artistHomeModel->getLatestSongs($artist_id, 4),
            'communities' => $this->artistHomeModel->getCommunities($artist_id, 3)
        ];
        $this->view('users/v_artist_homepage', $data);
    }

    
    public function artist_home() {
        // This can be removed as index() handles the functionality
        $this->index();
    }

}
?>
