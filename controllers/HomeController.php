<?php
require_once 'BaseController.php';
require_once 'models/RoomModel.php';
require_once 'models/ServiceModel.php';
require_once 'models/ReviewModel.php';
require_once 'models/BookingModel.php';

class HomeController extends BaseController {
    private $roomModel;
    private $serviceModel;
    private $reviewModel;
    private $bookingModel;

    public function __construct() {
        $this->roomModel = new RoomModel();
        $this->serviceModel = new ServiceModel();
        $this->reviewModel = new ReviewModel();
        $this->bookingModel = new BookingModel();
    }

    public function index() {
        // Lấy danh sách phòng nổi bật
        $featuredRooms = $this->roomModel->getFeaturedRooms(3);

        // Lấy danh sách dịch vụ
        $services = $this->serviceModel->getAll();

        // Lấy đánh giá mới nhất
        $recentReviews = $this->reviewModel->getRecentReviews(3);

        // Lấy danh sách loại phòng
        $roomTypes = $this->roomModel->getRoomTypes();

        // Lấy thống kê tổng quan
        $stats = [
            'total_rooms' => $this->roomModel->countTotalRooms(),
            'total_bookings' => $this->bookingModel->countTotalBookings(),
            'total_services' => $this->serviceModel->countTotalServices(),
            'total_reviews' => $this->reviewModel->countTotalReviews()
        ];

        $this->render('home/index', [
            'featuredRooms' => $featuredRooms,
            'services' => $services,
            'recentReviews' => $recentReviews,
            'stats' => $stats,
            'roomTypes' => $roomTypes
        ]);
    }

    public function search() {
        $filters = [
            'check_in' => $_GET['check_in'] ?? '',
            'check_out' => $_GET['check_out'] ?? '',
            'type' => $_GET['type'] ?? '',
            'price_range' => $_GET['price_range'] ?? ''
        ];

        // Xử lý khoảng giá
        if (!empty($filters['price_range'])) {
            list($min_price, $max_price) = explode('-', $filters['price_range']);
            $filters['min_price'] = $min_price;
            $filters['max_price'] = $max_price;
        }

        // Lấy danh sách phòng theo bộ lọc
        $rooms = $this->roomModel->searchRooms($filters);

        // Lấy danh sách loại phòng cho dropdown
        $roomTypes = $this->roomModel->getRoomTypes();

        $data = [
            'rooms' => $rooms,
            'roomTypes' => $roomTypes,
            'filters' => $filters
        ];

        $this->render('home/search', $data);
    }

    public function about() {
        $this->render('home/about');
    }

    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý gửi form liên hệ
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';

            // TODO: Gửi email hoặc lưu vào database
            $_SESSION['success'] = 'Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi sớm nhất có thể.';
            $this->redirect('/contact');
        }

        $this->render('home/contact');
    }

    public function terms() {
        $this->render('home/terms');
    }

    public function privacy() {
        $this->render('home/privacy');
    }

    public function booking() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để đặt phòng.';
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'room_id' => $_POST['room_id'],
                'check_in' => $_POST['check_in'],
                'check_out' => $_POST['check_out'],
                'guests' => $_POST['guests'],
                'total_amount' => $_POST['total_amount']
            ];

            if ($this->bookingModel->create($data)) {
                $_SESSION['success'] = 'Đặt phòng thành công!';
                $this->redirect('/my-bookings');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi đặt phòng.';
            }
        }

        $roomId = $_GET['room_id'] ?? null;
        if (!$roomId) {
            $this->redirect('/');
        }

        $room = $this->roomModel->getById($roomId);
        if (!$room) {
            $_SESSION['error'] = 'Không tìm thấy phòng.';
            $this->redirect('/');
        }

        $this->render('home/booking', ['room' => $room]);
    }

    public function myBookings() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem đặt phòng.';
            $this->redirect('/login');
        }

        $bookings = $this->bookingModel->getUserBookings($_SESSION['user_id']);
        $this->render('home/my-bookings', ['bookings' => $bookings]);
    }
} 