<?php
class EventManagement extends Controller {
    private $eventModel;
    private $userModel;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        
        if(!isOrganizer()) {
            redirect('events');
        }

        $this->eventModel = $this->model('Event');
        $this->userModel = $this->model('User');
    }

    public function index() {
        $events = $this->eventModel->getOrganizerEvents($_SESSION['user_id']);
        $data = [
            'events' => $events
        ];
        $this->view('users/event_management/index', $data);
    }

    public function create() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_date' => trim($_POST['event_date']),
                'event_time' => trim($_POST['event_time']),
                'venue' => trim($_POST['venue']),
                'image' => $_FILES['image'],
                'ticket_types' => $_POST['ticket_types'],
                'title_err' => '',
                'description_err' => '',
                'event_date_err' => '',
                'event_time_err' => '',
                'venue_err' => '',
                'image_err' => ''
            ];

            // Validate data
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

            if(empty($data['image']['name'])) {
                $data['image_err'] = 'Please select an event image';
            }

            // Make sure no errors
            if(empty($data['title_err']) && empty($data['description_err']) && 
               empty($data['event_date_err']) && empty($data['event_time_err']) && 
               empty($data['venue_err']) && empty($data['image_err'])) {
                
                // Upload image
                $imageName = time() . '_' . $data['image']['name'];
                $targetDir = "public/img/events/";
                $targetFile = $targetDir . $imageName;
                
                if(move_uploaded_file($data['image']['tmp_name'], $targetFile)) {
                    $data['image'] = $imageName;
                    
                    // Create event
                    if($this->eventModel->createEvent($data, $_SESSION['user_id'])) {
                        flash('event_message', 'Event created successfully');
                        redirect('eventmanagement');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    $data['image_err'] = 'Failed to upload image';
                }
            }
        } else {
            $data = [
                'title' => '',
                'description' => '',
                'event_date' => '',
                'event_time' => '',
                'venue' => '',
                'image' => '',
                'ticket_types' => [],
                'title_err' => '',
                'description_err' => '',
                'event_date_err' => '',
                'event_time_err' => '',
                'venue_err' => '',
                'image_err' => ''
            ];
        }

        $this->view('users/event_management/create', $data);
    }

    public function edit($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_date' => trim($_POST['event_date']),
                'event_time' => trim($_POST['event_time']),
                'venue' => trim($_POST['venue']),
                'image' => $_FILES['image'],
                'ticket_types' => $_POST['ticket_types'],
                'title_err' => '',
                'description_err' => '',
                'event_date_err' => '',
                'event_time_err' => '',
                'venue_err' => '',
                'image_err' => ''
            ];

            // Validate data
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

            // Make sure no errors
            if(empty($data['title_err']) && empty($data['description_err']) && 
               empty($data['event_date_err']) && empty($data['event_time_err']) && 
               empty($data['venue_err'])) {
                
                // Handle image upload if new image is provided
                if(!empty($data['image']['name'])) {
                    $imageName = time() . '_' . $data['image']['name'];
                    $targetDir = "public/img/events/";
                    $targetFile = $targetDir . $imageName;
                    
                    if(move_uploaded_file($data['image']['tmp_name'], $targetFile)) {
                        $data['image'] = $imageName;
                    } else {
                        $data['image_err'] = 'Failed to upload image';
                        $this->view('users/event_management/edit', $data);
                        return;
                    }
                } else {
                    // Keep existing image
                    $event = $this->eventModel->getEventById($id);
                    $data['image'] = $event->image;
                }
                
                // Update event
                if($this->eventModel->updateEvent($data)) {
                    flash('event_message', 'Event updated successfully');
                    redirect('eventmanagement');
                } else {
                    die('Something went wrong');
                }
            }
        } else {
            // Get existing event
            $event = $this->eventModel->getEventById($id);
            
            // Check for owner
            if($event->organizer_id != $_SESSION['user_id']) {
                redirect('eventmanagement');
            }

            $data = [
                'id' => $id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'venue' => $event->venue,
                'image' => $event->image,
                'ticket_types' => $event->ticket_types,
                'title_err' => '',
                'description_err' => '',
                'event_date_err' => '',
                'event_time_err' => '',
                'venue_err' => '',
                'image_err' => ''
            ];
        }

        $this->view('users/event_management/edit', $data);
    }

    public function delete($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get existing event
            $event = $this->eventModel->getEventById($id);
            
            // Check for owner
            if($event->organizer_id != $_SESSION['user_id']) {
                redirect('eventmanagement');
            }

            if($this->eventModel->deleteEvent($id)) {
                flash('event_message', 'Event removed');
                redirect('eventmanagement');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('eventmanagement');
        }
    }

    public function bookings($id) {
        $event = $this->eventModel->getEventById($id);
        
        // Check for owner
        if($event->organizer_id != $_SESSION['user_id']) {
            redirect('eventmanagement');
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
        
        // Check for owner
        if($event->organizer_id != $_SESSION['user_id']) {
            redirect('eventmanagement');
        }

        $income = $this->eventModel->getEventIncome($id);
        $data = [
            'event' => $event,
            'income' => $income
        ];

        $this->view('users/event_management/income', $data);
    }
} 