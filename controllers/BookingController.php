<?php
require_once 'BaseController.php';
require_once 'models/BookingModel.php';
require_once 'models/RoomModel.php';
require_once 'models/ServiceModel.php';

class BookingController extends BaseController {
    private $bookingModel;
    private $roomModel;
    private $serviceModel;

    public function __construct() {
        $this->bookingModel = new BookingModel();
        $this->roomModel = new RoomModel();
        $this->serviceModel = new ServiceModel();
    }

    public function index() {
        $this->requireLogin();

        $bookings = $this->bookingModel->getUserBookings($_SESSION['user_id']);
        $this->render('bookings/index', ['bookings' => $bookings]);
    }

    public function show($id) {
        $this->requireLogin();

        $booking = $this->bookingModel->getBookingDetails($id);
        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/bookings');
        }

        $services = $this->serviceModel->getServiceOrders($id);
        
        $data = [
            'booking' => $booking,
            'services' => $services
        ];

        $this->render('bookings/show', $data);
    }

    public function cancel($id) {
        $this->requireLogin();

        $booking = $this->bookingModel->getById($id);
        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/bookings');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->bookingModel->updateBookingStatus($id, BOOKING_STATUS_CANCELLED)) {
                $this->redirect('/bookings?cancelled=1');
            } else {
                $error = 'Có lỗi xảy ra, vui lòng thử lại';
                $this->render('bookings/cancel', [
                    'booking' => $booking,
                    'error' => $error
                ]);
            }
        } else {
            $this->render('bookings/cancel', ['booking' => $booking]);
        }
    }

    public function addService($bookingId) {
        $this->requireLogin();

        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/bookings');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['service_id']);
            
            if (empty($errors)) {
                $serviceData = [
                    'service_id' => $_POST['service_id'],
                    'booking_id' => $bookingId
                ];

                if ($this->serviceModel->createServiceOrder($serviceData)) {
                    $this->redirect('/bookings/' . $bookingId . '?service_added=1');
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $services = $this->serviceModel->getActiveServices();
            $this->render('bookings/add-service', [
                'booking' => $booking,
                'services' => $services,
                'errors' => $errors
            ]);
        } else {
            $services = $this->serviceModel->getActiveServices();
            $this->render('bookings/add-service', [
                'booking' => $booking,
                'services' => $services
            ]);
        }
    }

    public function payment($id) {
        $this->requireLogin();

        $booking = $this->bookingModel->getById($id);
        if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/bookings');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['payment_method']);
            
            if (empty($errors)) {
                // Xử lý thanh toán
                $paymentSuccess = $this->processPayment($booking, $_POST['payment_method']);
                
                if ($paymentSuccess) {
                    $this->bookingModel->updateBookingStatus($id, BOOKING_STATUS_CONFIRMED);
                    $this->redirect('/bookings?paid=1');
                } else {
                    $errors['general'] = 'Thanh toán thất bại, vui lòng thử lại';
                }
            }

            $this->render('bookings/payment', [
                'booking' => $booking,
                'errors' => $errors
            ]);
        } else {
            $this->render('bookings/payment', ['booking' => $booking]);
        }
    }

    private function processPayment($booking, $method) {
        // TODO: Implement payment processing
        // This is just a mock implementation
        return true;
    }
} 