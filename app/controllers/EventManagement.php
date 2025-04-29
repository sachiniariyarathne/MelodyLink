<?php
class EventManagement extends Controller {
    private $eventModel;
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once '../app/helpers/session_helper.php';

        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }
        
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'organizer') {
            redirect('events');
        }

        $this->eventModel = $this->model('Event');
        $this->userModel = $this->model('m_users');
    }

    public function index() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $organizerId = $_SESSION['user_id'];

        $data = [
            'total_bookings' => $this->eventModel->getTotalBookings($organizerId),
            'total_revenue' => $this->eventModel->getTotalRevenue($organizerId),
            'active_events' => $this->eventModel->getActiveEventsCount($organizerId),
            'ending_soon' => $this->eventModel->getEndingSoonCount($organizerId),
            'total_customers' => $this->eventModel->getTotalCustomers($organizerId),
            'recent_bookings' => $this->eventModel->getRecentBookings($organizerId)
        ];

        $this->view('users/event_management/index', $data);
    }

    public function events() {
        $events = $this->eventModel->getOrganizerEvents($_SESSION['user_id']);
        
        $data = [
            'events' => $events
        ];

        $this->view('users/event_management/events', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'organiser_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'eventType' => trim($_POST['eventType']),
                'description' => trim($_POST['description']),
                'event_date' => trim($_POST['event_date']),
                'event_time' => trim($_POST['event_time']),
                'venue' => trim($_POST['venue']),
                'image' => '',
                'ticket_types' => [],
                'title_err' => '',
                'eventType_err' => '',
                'description_err' => '',
                'event_date_err' => '',
                'event_time_err' => '',
                'venue_err' => '',
                'image_err' => '',
                'ticket_types_err' => ''
            ];

            if (isset($_POST['ticket_name']) && is_array($_POST['ticket_name'])) {
                foreach ($_POST['ticket_name'] as $index => $name) {
                    if (empty($name) || empty($_POST['ticket_price'][$index]) || empty($_POST['ticket_quantity'][$index])) {
                        $data['ticket_types_err'] = 'Please fill in all ticket type fields';
                        break;
                    }
                    $data['ticket_types'][] = [
                        'name' => $name,
                        'price' => $_POST['ticket_price'][$index],
                        'quantity' => $_POST['ticket_quantity'][$index]
                    ];
                }
            } else {
                $data['ticket_types_err'] = 'At least one ticket type is required';
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 5 * 1024 * 1024; // 5MB
                $filename = $_FILES['image']['name'];
                $filesize = $_FILES['image']['size'];
                $filetype = $_FILES['image']['type'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if ($filesize > $max_size) {
                    $data['image_err'] = 'File size must be less than 5MB';
                    error_log('File size too large: ' . $filesize . ' bytes');
                    $this->view('users/event_management/create', $data);
                    return;
                }

                if (!in_array($ext, $allowed) || !in_array($filetype, $allowed_mime)) {
                    $data['image_err'] = 'Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed';
                    error_log('Invalid file type: ' . $filetype);
                    $this->view('users/event_management/create', $data);
                    return;
                }

                $newFilename = bin2hex(random_bytes(6)) . '.' . $ext;
                $uploadDir = dirname(APPROOT) . '/public/uploads/events/';
                
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0775, true)) {
                        $error = error_get_last();
                        $data['image_err'] = 'Failed to create upload directory';
                        error_log('Failed to create directory: ' . $uploadDir . ' - Error: ' . ($error ? $error['message'] : 'Unknown error'));
                        $this->view('users/event_management/create', $data);
                        return;
                    }
                    chown($uploadDir, 'daemon');
                    chgrp($uploadDir, 'daemon');
                }

                $uploadPath = $uploadDir . $newFilename;

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $_FILES['image']['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mime_type, $allowed_mime)) {
                    $data['image_err'] = 'Invalid file type detected';
                    error_log('Invalid MIME type detected: ' . $mime_type);
                    $this->view('users/event_management/create', $data);
                    return;
                }

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    chmod($uploadPath, 0664);
                    
                    if (extension_loaded('gd')) {
                        $image = null;
                        if ($ext === 'jpg' || $ext === 'jpeg') {
                            $image = imagecreatefromjpeg($uploadPath);
                            imagejpeg($image, $uploadPath, 85); 
                        } elseif ($ext === 'png') {
                            $image = imagecreatefrompng($uploadPath);
                            imagepng($image, $uploadPath, 8); 
                        }
                        if ($image) {
                            imagedestroy($image);
                        }
                    }

                    $data['image'] = 'uploads/events/' . $newFilename;
                    
                    error_log('Successfully uploaded file: ' . $newFilename . ' (size: ' . $filesize . ' bytes, type: ' . $mime_type . ')');
                } else {
                    $error = error_get_last();
                    $data['image_err'] = 'Failed to upload image';
                    error_log('Upload error: ' . ($error ? $error['message'] : 'Unknown error'));
                    error_log('Upload path: ' . $uploadPath);
                    error_log('Temp file: ' . $_FILES['image']['tmp_name']);
                    $this->view('users/event_management/create', $data);
                    return;
                }
            } else {
                $upload_error = isset($_FILES['image']) ? $_FILES['image']['error'] : 'No file uploaded';
                $data['image_err'] = 'Please select a valid image file';
                error_log('File upload error: ' . $upload_error);
            }

            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter event title';
            }

            if (empty($data['eventType'])) {
                $data['eventType_err'] = 'Please enter event Type';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter event description';
            }
            if (empty($data['event_date'])) {
                $data['event_date_err'] = 'Please enter event date';
            }
            if (empty($data['event_time'])) {
                $data['event_time_err'] = 'Please enter event time';
            }
            if (empty($data['venue'])) {
                $data['venue_err'] = 'Please enter venue';
            }

            if (empty($data['title_err']) && empty($data['eventType_err']) && empty($data['description_err']) && 
                empty($data['event_date_err']) && empty($data['event_time_err']) && 
                empty($data['venue_err']) && empty($data['image_err']) && 
                empty($data['ticket_types_err'])) {
                
                if ($this->eventModel->createEvent($data)) {
                    flash('event_message', 'Event created successfully');
                    redirect('eventmanagement/events');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/event_management/create', $data);
            }
        } else {
            $data = [
                'title' => '',
                'eventType' => '',
                'description' => '',
                'event_date' => '',
                'event_time' => '',
                'venue' => '',
                'image' => '',
                'ticket_types' => [],
                'title_err' => '',
                'eventType_err' => '',
                'description_err' => '',
                'event_date_err' => '',
                'event_time_err' => '',
                'venue_err' => '',
                'image_err' => '',
                'ticket_types_err' => ''
            ];

            $this->view('users/event_management/create', $data);
        }
    }

    public function viewEvent($id) {
        $event = $this->eventModel->getEventById($id);
        
        if (!$event || $event->organiser_id != $_SESSION['user_id']) {
            redirect('eventmanagement/events');
        }

        $data = [
            'event' => $event,
            'ticket_types' => $this->eventModel->getEventTicketTypes($id),
            'bookings' => $this->eventModel->getEventBookings($id)
        ];

        $this->view('users/event_management/view', $data);
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $ticket_types = [];
            if (isset($_POST['ticket_name']) && is_array($_POST['ticket_name'])) {
                foreach ($_POST['ticket_name'] as $index => $name) {
                    if (!empty($name) && isset($_POST['ticket_price'][$index]) && isset($_POST['ticket_quantity'][$index])) {
                        $ticket_types[] = [
                            'name' => $name,
                            'price' => $_POST['ticket_price'][$index],
                            'quantity' => $_POST['ticket_quantity'][$index]
                        ];
                    }
                }
            }

            $data = [
                'event_id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_date' => trim($_POST['event_date']),
                'event_time' => trim($_POST['event_time']),
                'venue' => trim($_POST['venue']),
                'image' => '',
                'ticket_types' => $ticket_types,
                'title_err' => '',
                'description_err' => '',
                'event_date_err' => '',
                'event_time_err' => '',
                'venue_err' => '',
                'image_err' => '',
                'ticket_types_err' => ''
            ];

            if(empty($data['title'])) {
                $data['title_err'] = 'Please enter event title';
            }

            if(empty($data['description'])) {
                $data['description_err'] = 'Please enter event description';
            }

            if(empty($data['event_date'])) {
                $data['event_date_err'] = 'Please enter event date';
            }

            if(empty($data['event_time'])) {
                $data['event_time_err'] = 'Please enter event time';
            }

            if(empty($data['venue'])) {
                $data['venue_err'] = 'Please enter event venue';
            }

            if(empty($data['ticket_types'])) {
                $data['ticket_types_err'] = 'Please add at least one ticket type';
            }

            if(empty($data['title_err']) && empty($data['description_err']) && 
               empty($data['event_date_err']) && empty($data['event_time_err']) && 
               empty($data['venue_err']) && empty($data['ticket_types_err'])) {
                
                if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    $max_size = 5 * 1024 * 1024; // 5MB
                    $filename = $_FILES['image']['name'];
                    $filesize = $_FILES['image']['size'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if($filesize > $max_size) {
                        $data['image_err'] = 'File size must be less than 5MB';
                        $this->view('users/event_management/edit', $data);
                        return;
                    }

                    if(!in_array($ext, $allowed)) {
                        $data['image_err'] = 'Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed';
                        $this->view('users/event_management/edit', $data);
                        return;
                    }

                    $imageName = time() . '_' . $filename;
                    $targetDir = "public/uploads/events/";
                    $targetFile = $targetDir . $imageName;
                    
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $data['image'] = $imageName;
                    } else {
                        $data['image_err'] = 'Failed to upload image';
                        $this->view('users/event_management/edit', $data);
                        return;
                    }
                } else {
                    $event = $this->eventModel->getEventById($id);
                    $data['image'] = $event->image;
                }
                
                if($this->eventModel->updateEvent($data)) {
                    flash('event_message', 'Event updated successfully');
                    redirect('eventmanagement/events');
                } else {
                    flash('event_message', 'Something went wrong while updating the event', 'alert alert-danger');
                    $this->view('users/event_management/edit', $data);
                }
            } else {
                $event = $this->eventModel->getEventById($id);
                $data['event'] = $event;
                $data['ticket_types'] = $this->eventModel->getEventTicketTypes($id);
                $this->view('users/event_management/edit', $data);
            }
        } else {
            $event = $this->eventModel->getEventById($id);
            
            if (!$event || $event->organiser_id != $_SESSION['user_id']) {
                flash('event_message', 'You are not authorized to edit this event', 'alert alert-danger');
                redirect('eventmanagement/events');
            }

            $data = [
                'event' => $event,
                'ticket_types' => $this->eventModel->getEventTicketTypes($id)
            ];

            $this->view('users/event_management/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $event = $this->eventModel->getEventById($id);
            
            if (!$event || $event->organiser_id != $_SESSION['user_id']) {
                flash('event_message', 'You are not authorized to delete this event', 'alert alert-danger');
                redirect('eventmanagement/events');
                return;
            }

            if ($this->eventModel->deleteEvent($id)) {
                flash('event_message', 'Event deleted successfully');
                redirect('eventmanagement/events');
            } else {
                flash('event_message', 'Something went wrong while deleting the event', 'alert alert-danger');
                redirect('eventmanagement/events');
            }
        } else {
            redirect('eventmanagement/events');
        }
    }

    public function bookings($id) {
        $event = $this->eventModel->getEventById($id);
        if ($event->organiser_id != $_SESSION['user_id']) {
            redirect('eventmanagement/events');
        }
    
        $bookings = $this->eventModel->getEventBookings($id);
        $data = [
            'event' => $event,
            'bookings' => $bookings
        ];
    
        $this->view('users/event_management/bookings', $data);
    }

    public function income($id) {
        $event = $this->eventModel->getEventById($id);
        if($event->organizer_id != $_SESSION['user_id']) {
            redirect('eventmanagement/events');
        }

        $income = $this->eventModel->getEventIncome($id);
        $data = [
            'event' => $event,
            'income' => $income
        ];

        $this->view('users/event_management/income', $data);
    }

    public function profile() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $organizerId = $_SESSION['user_id'];
        $organizer = $this->eventModel->getOrganizerById($organizerId);
        
        $data = [
            'organizer' => $organizer
        ];

        $this->view('users/event_management/profile', $data);
    }

    public function editProfile() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'organizer_id' => $_SESSION['user_id'],
                'username' => trim($_POST['username']),
                'organization' => trim($_POST['organization']),
                'username_err' => '',
                'organization_err' => ''
            ];

            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            if (empty($data['organization'])) {
                $data['organization_err'] = 'Please enter organization name';
            }

            if (empty($data['username_err']) && empty($data['organization_err'])) {
                if ($this->eventModel->updateOrganizerProfile($data)) {
                    flash('profile_message', 'Profile updated successfully');
                    redirect('eventmanagement/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/event_management/profile', $data);
            }
        } else {
            $organizerId = $_SESSION['user_id'];
            $organizer = $this->eventModel->getOrganizerById($organizerId);
            
            $data = [
                'organizer_id' => $organizer->organiser_id,
                'username' => $organizer->username,
                'organization' => $organizer->Organization,
                'username_err' => '',
                'organization_err' => ''
            ];

            $this->view('users/event_management/profile', $data);
        }
    }
} 