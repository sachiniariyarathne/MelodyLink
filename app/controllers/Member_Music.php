<?php
class Member_Music extends Controller {
    private $musicModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'member') {
            redirect('users/login');
        }
        
        $this->musicModel = $this->model('m_music');
    }

    public function index() {
        $popularAlbums = $this->musicModel->getPopularAlbums();
        $popularArtists = $this->musicModel->getPopularArtists();

        $data = [
            'albums' => $popularAlbums,
            'artists' => $popularArtists
        ];

        $this->view('users/v_member_music', $data);
    }

    public function search() {
        $searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($searchTerm)) {
            redirect('music');
        }
        
        $albums = $this->musicModel->searchAlbums($searchTerm);
        $artists = $this->musicModel->searchArtists($searchTerm);
        
        $data = [
            'searchTerm' => $searchTerm,
            'albums' => $albums,
            'artists' => $artists
        ];
        
        $this->view('users/v_music_search', $data);
    }

    public function album($id) {
        $album = $this->musicModel->getAlbumById($id);
        $tracks = $this->musicModel->getTracksForAlbum($id);
        
        if (!$album) {
            redirect('music');
        }
        
        $data = [
            'album' => $album,
            'tracks' => $tracks
        ];
        
        $this->view('music/v_album_detail', $data);
    }

    public function artist($id) {
        $artist = $this->musicModel->getArtistById($id);
        
        if (!$artist) {
            redirect('music');
        }
        
        $data = [
            'artist' => $artist
        ];
        
        $this->view('music/v_artist_detail', $data);
    }
}
?>
