<?php
require_once 'BaseController.php';
require_once 'models/UserModel.php';
require_once 'models/RoomModel.php';
require_once 'models/BookingModel.php';
require_once 'models/ServiceModel.php';
require_once 'models/ReviewModel.php';

class AdminController extends BaseController {
    private $userModel;
    private $roomModel;
    private $bookingModel;
    private $serviceModel;
    private $reviewModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->roomModel = new RoomModel();
        $this->bookingModel = new BookingModel();
        $this->serviceModel = new ServiceModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index() {
        $this->requireAdmin();

        // Lấy thống kê tổng quan
        $stats = [
            'total_users' => $this->userModel->countTotalUsers(),
            'total_rooms' => $this->roomModel->countTotalRooms(),
            'total_bookings' => $this->bookingModel->countTotalBookings(),
            'total_revenue' => $this->bookingModel->getTotalRevenue()
        ];

        // Lấy đặt phòng gần đây
        $recentBookings = $this->bookingModel->getRecentBookings(5);

        // Lấy thống kê theo tháng
        $monthlyStats = $this->bookingModel->getMonthlyStats();

        $data = [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'monthlyStats' => $monthlyStats
        ];

        $this->render('admin/dashboard', $data);
    }

    public function users() {
        $this->requireAdmin();

        $users = $this->userModel->getAll();
        $this->render('admin/users/index', ['users' => $users]);
    }

    public function editUser($id) {
        $this->requireAdmin();

        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->redirect('/admin/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['name', 'email', 'role']);
            
            if (empty($errors)) {
                $userData = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'role' => $_POST['role']
                ];

                if ($this->userModel->update($id, $userData)) {
                    $this->redirect('/admin/users?updated=1');
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $this->render('admin/users/edit', [
                'user' => $user,
                'errors' => $errors
            ]);
        } else {
            $this->render('admin/users/edit', ['user' => $user]);
        }
    }

    public function deleteUser($id) {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->delete($id)) {
                $this->redirect('/admin/users?deleted=1');
            } else {
                $error = 'Có lỗi xảy ra, vui lòng thử lại';
                $this->render('admin/users/delete', [
                    'user' => $this->userModel->getById($id),
                    'error' => $error
                ]);
            }
        } else {
            $this->render('admin/users/delete', [
                'user' => $this->userModel->getById($id)
            ]);
        }
    }

    public function bookings() {
        $this->requireAdmin();

        $bookings = $this->bookingModel->getAll();
        $this->render('admin/bookings/index', ['bookings' => $bookings]);
    }

    public function updateBookingStatus($id) {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            
            if (in_array($status, [
                BOOKING_STATUS_PENDING,
                BOOKING_STATUS_CONFIRMED,
                BOOKING_STATUS_CANCELLED,
                BOOKING_STATUS_COMPLETED
            ])) {
                if ($this->bookingModel->updateBookingStatus($id, $status)) {
                    $this->redirect('/admin/bookings?status_updated=1');
                }
            }
            
            $this->redirect('/admin/bookings?error=1');
        }
    }

    public function services() {
        $this->requireAdmin();

        $services = $this->serviceModel->getAll();
        $this->render('admin/services/index', ['services' => $services]);
    }

    public function createService() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['name', 'price', 'description']);
            
            if (empty($errors)) {
                $serviceData = [
                    'name' => $_POST['name'],
                    'price' => $_POST['price'],
                    'description' => $_POST['description'],
                    'status' => 'active'
                ];

                if ($this->serviceModel->create($serviceData)) {
                    $this->redirect('/admin/services?created=1');
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $this->render('admin/services/create', ['errors' => $errors]);
        } else {
            $this->render('admin/services/create');
        }
    }

    public function editService($id) {
        $this->requireAdmin();

        $service = $this->serviceModel->getById($id);
        if (!$service) {
            $this->redirect('/admin/services');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['name', 'price', 'description']);
            
            if (empty($errors)) {
                $serviceData = [
                    'name' => $_POST['name'],
                    'price' => $_POST['price'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status']
                ];

                if ($this->serviceModel->update($id, $serviceData)) {
                    $this->redirect('/admin/services?updated=1');
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $this->render('admin/services/edit', [
                'service' => $service,
                'errors' => $errors
            ]);
        } else {
            $this->render('admin/services/edit', ['service' => $service]);
        }
    }

    public function deleteService($id) {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->serviceModel->delete($id)) {
                $this->redirect('/admin/services?deleted=1');
            } else {
                $error = 'Có lỗi xảy ra, vui lòng thử lại';
                $this->render('admin/services/delete', [
                    'service' => $this->serviceModel->getById($id),
                    'error' => $error
                ]);
            }
        } else {
            $this->render('admin/services/delete', [
                'service' => $this->serviceModel->getById($id)
            ]);
        }
    }

    public function reviews() {
        $this->requireAdmin();

        $reviews = $this->reviewModel->getAll();
        $this->render('admin/reviews/index', ['reviews' => $reviews]);
    }

    public function updateReviewStatus($id) {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            
            if (in_array($status, ['pending', 'approved', 'rejected'])) {
                if ($this->reviewModel->update($id, ['status' => $status])) {
                    $this->redirect('/admin/reviews?status_updated=1');
                }
            }
            
            $this->redirect('/admin/reviews?error=1');
        }
    }

    public function reports() {
        $this->requireAdmin();

        // Lấy thống kê phòng
        $roomStats = $this->roomModel->getRoomStats();

        // Lấy thống kê dịch vụ
        $serviceStats = $this->serviceModel->getServiceStats();

        // Lấy thống kê theo tháng
        $monthlyStats = $this->bookingModel->getMonthlyStats();

        $data = [
            'roomStats' => $roomStats,
            'serviceStats' => $serviceStats,
            'monthlyStats' => $monthlyStats
        ];

        $this->render('admin/reports/index', $data);
    }
} 