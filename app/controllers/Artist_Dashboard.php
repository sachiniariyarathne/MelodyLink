<?php
class Artist_Dashboard extends Controller {
    private $dashboardModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'artist') {
            redirect('users/login');
        }
        $this->dashboardModel = $this->model('m_artist_dashboard');
    }

    public function index() {
        $artist_id = $_SESSION['user_id'];
        // Fetch artist profile info
        $profile = $this->dashboardModel->getArtistProfile($artist_id);

        // Fetch stats and dashboard data
        $stats = $this->dashboardModel->getArtistStats($artist_id);
        $recent_releases = $this->dashboardModel->getRecentReleases($artist_id, 4);
        $communities = $this->dashboardModel->getArtistCommunities($artist_id, 4);

        $data = [
            'profile_pic' => $profile->profile_pic ?? 'default-avatar.png',
            'username' => $profile->Username,
            'stats' => $stats,
            'recent_releases' => $recent_releases,
            'communities' => $communities
        ];

        $this->view('users/v_artist_dashboard', $data);
    }

    public function artist_dashboard() {
        // This can be removed as index() handles the functionality
        $this->index();
    }

}
?>
