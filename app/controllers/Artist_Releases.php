<?php

require_once APPROOT . '/helpers/flash_helper.php';

class Artist_Releases extends Controller {
    private $releaseModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'artist') {
            redirect('users/login');
        }
        $this->releaseModel = $this->model('m_artist_releases');
    }

    public function index() {
        $artist_id = $_SESSION['user_id'];
        $albums = $this->releaseModel->getAlbumsByArtist($artist_id);
    
        // Fetch artist info (assuming you have a model for this)
        $artist = $this->releaseModel->getArtistInfo($artist_id); // or use a separate model
    
        $data = [
            'albums' => $albums,
            'username' => $artist->Username ?? 'Artist',
            'profile_pic' => $artist->profile_pic ?? 'default-avatar.png'
        ];
    
        $this->view('users/v_artist_releases', $data);
    }
    
    public function artist_releases() {
        // This can be removed as index() handles the functionality
        $this->index();
    }



    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'album_name' => trim($_POST['album_name']),
                'release_date' => $_POST['release_date'],
                'genre' => trim($_POST['genre']),
                'featured_artists' => trim($_POST['featured_artists']),
                'artist_id' => $_SESSION['user_id'],
                'artist_name' => $_SESSION['username'],
                'album_cover' => '',
                'music_track' => '',
                'album_name_err' => '',
                'release_date_err' => '',
                'genre_err' => '',
                'album_cover_err' => '',
                'music_track_err' => ''
            ];
            
            // Validate album name
            if (empty($data['album_name'])) {
                $data['album_name_err'] = 'Please enter album name';
            } elseif ($this->releaseModel->albumNameExists($data['album_name'], $data['artist_id'])) {
                $data['album_name_err'] = 'Album with this name already exists';
            }
            
            // Validate release date
            if (empty($data['release_date'])) {
                $data['release_date_err'] = 'Please select release date';
            }
            
            // Validate genre
            if (empty($data['genre'])) {
                $data['genre_err'] = 'Please enter genre';
            }
            
            // Handle album cover upload
            if (!empty($_FILES['album_cover']['name'])) {
                $upload_dir = APPROOT . '/../public/uploads/';
                $filename = uniqid() . '_' . $_FILES['album_cover']['name'];
                $target_file = $upload_dir . $filename;
                
                // Check file type
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($_FILES['album_cover']['type'], $allowed_types)) {
                    $data['album_cover_err'] = 'Only JPG, JPEG, PNG files are allowed';
                } 
                // Check file size (2MB max)
                elseif ($_FILES['album_cover']['size'] > 2000000) {
                    $data['album_cover_err'] = 'File size must be less than 2MB';
                } 
                // Upload file
                elseif (move_uploaded_file($_FILES['album_cover']['tmp_name'], $target_file)) {
                    $data['album_cover'] = $filename;
                } else {
                    $data['album_cover_err'] = 'Error uploading file';
                }
            } else {
                $data['album_cover_err'] = 'Please upload album cover';
            }
            
            // Handle music track upload
            if (!empty($_FILES['music_track']['name'])) {
                $upload_dir = APPROOT . '/../public/uploads/tracks/';
                
                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $filename = uniqid() . '_' . $_FILES['music_track']['name'];
                $target_file = $upload_dir . $filename;
                
                // Check file type
                $allowed_types = ['audio/mpeg', 'audio/mp3', 'audio/wav'];
                if (!in_array($_FILES['music_track']['type'], $allowed_types)) {
                    $data['music_track_err'] = 'Only MP3, WAV files are allowed';
                } 
                // Check file size (10MB max)
                elseif ($_FILES['music_track']['size'] > 10000000) {
                    $data['music_track_err'] = 'File size must be less than 10MB';
                } 
                // Upload file
                elseif (move_uploaded_file($_FILES['music_track']['tmp_name'], $target_file)) {
                    $data['music_track'] = 'tracks/' . $filename;
                } else {
                    $data['music_track_err'] = 'Error uploading file';
                }
            } else {
                $data['music_track_err'] = 'Please upload a music track';
            }
            
            // Make sure no errors
            if (empty($data['album_name_err']) && empty($data['release_date_err']) && 
                empty($data['genre_err']) && empty($data['album_cover_err']) && 
                empty($data['music_track_err'])) {
                
                // Add album
                if ($this->releaseModel->addAlbum($data)) {
                    flash('album_message', 'Album added successfully');
                    redirect('Artist_Releases');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/v_artist_add_release', $data);
            }
            
        } else {
            $data = [
                'album_name' => '',
                'release_date' => '',
                'genre' => '',
                'featured_artists' => '',
                'album_cover' => '',
                'music_track' => '',
                'album_name_err' => '',
                'release_date_err' => '',
                'genre_err' => '',
                'album_cover_err' => '',
                'music_track_err' => ''
            ];
            
            $this->view('users/v_artist_add_release', $data);
        }
    }
    
    public function edit($album_id) {
        $artist_id = $_SESSION['user_id'];
        
        // Verify album belongs to artist
        $album = $this->releaseModel->getAlbumById($album_id);
        if ($album->artist_id != $artist_id) {
            redirect('Artist_Releases');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'album_id' => $album_id,
                'album_name' => trim($_POST['album_name']),
                'release_date' => $_POST['release_date'],
                'genre' => trim($_POST['genre']),
                'featured_artists' => trim($_POST['featured_artists']),
                'artist_id' => $_SESSION['user_id'],
                'artist_name' => $_SESSION['username'],
                'album_cover' => $album->album_cover,
                'music_track' => $album->music_track,
                'album_name_err' => '',
                'release_date_err' => '',
                'genre_err' => '',
                'album_cover_err' => '',
                'music_track_err' => ''
            ];
            
            // Validate album name
            if (empty($data['album_name'])) {
                $data['album_name_err'] = 'Please enter album name';
            }

            if ($data['album_name'] !== $album->album_name) {
                if ($this->releaseModel->albumNameExists($data['album_name'], $data['artist_id'])) {
                    $data['album_name_err'] = 'Album name already exists';
                }
            }
            
            // Validate release date
            if (empty($data['release_date'])) {
                $data['release_date_err'] = 'Please select release date';
            }
            
            // Validate genre
            if (empty($data['genre'])) {
                $data['genre_err'] = 'Please enter genre';
            }
            
            // Handle album cover upload (if new file uploaded)
            if (!empty($_FILES['album_cover']['name']) && !empty($album->album_cover)) {
                $old_cover = APPROOT . '/../public/uploads/' . $album->album_cover;
                if (file_exists($old_cover)) {
                    unlink($old_cover);
                }
                
                // Check file type
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($_FILES['album_cover']['type'], $allowed_types)) {
                    $data['album_cover_err'] = 'Only JPG, JPEG, PNG files are allowed';
                } 
                // Check file size (2MB max)
                elseif ($_FILES['album_cover']['size'] > 2000000) {
                    $data['album_cover_err'] = 'File size must be less than 2MB';
                } 
                // Upload file
                elseif (move_uploaded_file($_FILES['album_cover']['tmp_name'], $target_file)) {
                    $data['album_cover'] = $filename;
                } else {
                    $data['album_cover_err'] = 'Error uploading file';
                }
            }
            
            // Handle music track upload (if new file uploaded)
            if (!empty($_FILES['music_track']['name']) && !empty($album->music_track)) {
                $old_track = APPROOT . '/../public/uploads/' . $album->music_track;
                if (file_exists($old_track)) {
                    unlink($old_track);
                }
                
                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $filename = uniqid() . '_' . $_FILES['music_track']['name'];
                $target_file = $upload_dir . $filename;
                
                // Check file type
                $allowed_types = ['audio/mpeg', 'audio/mp3', 'audio/wav'];
                if (!in_array($_FILES['music_track']['type'], $allowed_types)) {
                    $data['music_track_err'] = 'Only MP3, WAV files are allowed';
                } 
                // Check file size (10MB max)
                elseif ($_FILES['music_track']['size'] > 10000000) {
                    $data['music_track_err'] = 'File size must be less than 10MB';
                } 
                // Upload file
                elseif (move_uploaded_file($_FILES['music_track']['tmp_name'], $target_file)) {
                    $data['music_track'] = 'tracks/' . $filename;
                } else {
                    $data['music_track_err'] = 'Error uploading file';
                }
            }
            
            // Make sure no errors
            if (empty($data['album_name_err']) && empty($data['release_date_err']) && 
                empty($data['genre_err']) && empty($data['album_cover_err']) && 
                empty($data['music_track_err'])) {
                
                // Update album
                if ($this->releaseModel->updateAlbum($data)) {
                    flash('album_message', 'Album updated successfully');
                    redirect('Artist_Releases');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/v_artist_edit_release', $data);
            }
            
        } else {
            // Get album details
            $album = $this->releaseModel->getAlbumById($album_id);
            
            $data = [
                'album_id' => $album->album_id,
                'album_name' => $album->album_name,
                'release_date' => $album->release_date,
                'genre' => $album->genre,
                'featured_artists' => $album->featured_artists,
                'album_cover' => $album->album_cover,
                'music_track' => $album->music_track,
                'album_name_err' => '',
                'release_date_err' => '',
                'genre_err' => '',
                'album_cover_err' => '',
                'music_track_err' => ''
            ];
            
            $this->view('artist/v_artist_edit_release', $data);
        }
    }
    
    public function delete($album_id) {
        $artist_id = $_SESSION['user_id'];
        $album = $this->releaseModel->getAlbumById($album_id);
    
        // Verify ownership
        if ($album->artist_id != $artist_id) {
            redirect('Artist_Releases');
        }
    
        // Delete even if not POST (but keep confirmation in view)
        if ($this->releaseModel->deleteAlbum($album_id)) {
            // Delete files
            if (!empty($album->album_cover)) {
                $cover_path = APPROOT . '/../public/uploads/' . $album->album_cover;
                if (file_exists($cover_path)) unlink($cover_path);
            }
            if (!empty($album->music_track)) {
                $track_path = APPROOT . '/../public/uploads/' . $album->music_track;
                if (file_exists($track_path)) unlink($track_path);
            }
            flash('album_message', 'Album deleted successfully', 'success');
        } else {
            flash('album_message', 'Deletion failed', 'error');
        }
        redirect('Artist_Releases');
    }
    
}
?>
