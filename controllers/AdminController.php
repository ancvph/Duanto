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
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== ROLE_ADMIN) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này.';
            $this->redirect('/login');
        }

        $this->userModel = new UserModel();
        $this->roomModel = new RoomModel();
        $this->bookingModel = new BookingModel();
        $this->serviceModel = new ServiceModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index() {
        // Get statistics
        $stats = [
            'total_rooms' => $this->roomModel->countTotalRooms(),
            'total_bookings' => $this->bookingModel->countTotalBookings(),
            'total_services' => $this->serviceModel->countTotalServices(),
            'total_reviews' => $this->reviewModel->countTotalReviews(),
            'recent_bookings' => $this->bookingModel->getRecentBookings(5),
            'recent_reviews' => $this->reviewModel->getRecentReviews(5)
        ];

        $this->render('admin/dashboard', [
            'stats' => $stats
        ]);
    }

    public function rooms() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Xử lý tạo phòng mới
                    $data = [
                        'name' => $_POST['name'],
                        'type' => $_POST['type'],
                        'description' => $_POST['description'],
                        'price' => $_POST['price'],
                        'status' => $_POST['status']
                    ];
                    
                    // Xử lý upload ảnh
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                        $data['image'] = $this->uploadImage($_FILES['image'], 'rooms');
                    }
                    
                    if ($this->roomModel->create($data)) {
                        $_SESSION['success'] = 'Thêm phòng mới thành công.';
                        $this->redirect('/admin/rooms');
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi thêm phòng mới.';
                    }
                }
                
                $this->render('admin/rooms/create', ['currentPage' => 'rooms']);
                break;
                
            case 'edit':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->redirect('/admin/rooms');
                }
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Xử lý cập nhật phòng
                    $data = [
                        'name' => $_POST['name'],
                        'type' => $_POST['type'],
                        'description' => $_POST['description'],
                        'price' => $_POST['price'],
                        'status' => $_POST['status']
                    ];
                    
                    // Xử lý upload ảnh mới
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                        $data['image'] = $this->uploadImage($_FILES['image'], 'rooms');
                    }
                    
                    if ($this->roomModel->update($id, $data)) {
                        $_SESSION['success'] = 'Cập nhật phòng thành công.';
                        $this->redirect('/admin/rooms');
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật phòng.';
                    }
                }
                
                $room = $this->roomModel->getById($id);
                $this->render('admin/rooms/edit', [
                    'room' => $room,
                    'currentPage' => 'rooms'
                ]);
                break;
                
            case 'delete':
                $id = $_GET['id'] ?? null;
                if ($id && $this->roomModel->delete($id)) {
                    $_SESSION['success'] = 'Xóa phòng thành công.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa phòng.';
                }
                $this->redirect('/admin/rooms');
                break;
                
            default:
                // Hiển thị danh sách phòng
                $rooms = $this->roomModel->getAll();
                $this->render('admin/rooms/index', [
                    'rooms' => $rooms,
                    'currentPage' => 'rooms'
                ]);
        }
    }

    public function bookings() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'view':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->redirect('/admin/bookings');
                }
                
                $booking = $this->bookingModel->getById($id);
                $this->render('admin/bookings/view', [
                    'booking' => $booking,
                    'currentPage' => 'bookings'
                ]);
                break;
                
            case 'update-status':
                $id = $_GET['id'] ?? null;
                $status = $_GET['status'] ?? null;
                
                if ($id && $status && $this->bookingModel->updateStatus($id, $status)) {
                    $_SESSION['success'] = 'Cập nhật trạng thái đặt phòng thành công.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật trạng thái.';
                }
                $this->redirect('/admin/bookings');
                break;
                
            default:
                // Hiển thị danh sách đặt phòng
                $bookings = $this->bookingModel->getAll();
                $this->render('admin/bookings/index', [
                    'bookings' => $bookings,
                    'currentPage' => 'bookings'
                ]);
        }
    }

    public function services() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Xử lý tạo dịch vụ mới
                    $data = [
                        'name' => $_POST['name'],
                        'description' => $_POST['description'],
                        'price' => $_POST['price'],
                        'status' => $_POST['status']
                    ];
                    
                    if ($this->serviceModel->create($data)) {
                        $_SESSION['success'] = 'Thêm dịch vụ mới thành công.';
                        $this->redirect('/admin/services');
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi thêm dịch vụ mới.';
                    }
                }
                
                $this->render('admin/services/create', ['currentPage' => 'services']);
                break;
                
            case 'edit':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->redirect('/admin/services');
                }
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Xử lý cập nhật dịch vụ
                    $data = [
                        'name' => $_POST['name'],
                        'description' => $_POST['description'],
                        'price' => $_POST['price'],
                        'status' => $_POST['status']
                    ];
                    
                    if ($this->serviceModel->update($id, $data)) {
                        $_SESSION['success'] = 'Cập nhật dịch vụ thành công.';
                        $this->redirect('/admin/services');
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật dịch vụ.';
                    }
                }
                
                $service = $this->serviceModel->getById($id);
                $this->render('admin/services/edit', [
                    'service' => $service,
                    'currentPage' => 'services'
                ]);
                break;
                
            case 'delete':
                $id = $_GET['id'] ?? null;
                if ($id && $this->serviceModel->delete($id)) {
                    $_SESSION['success'] = 'Xóa dịch vụ thành công.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa dịch vụ.';
                }
                $this->redirect('/admin/services');
                break;
                
            default:
                // Hiển thị danh sách dịch vụ
                $services = $this->serviceModel->getAll();
                $this->render('admin/services/index', [
                    'services' => $services,
                    'currentPage' => 'services'
                ]);
        }
    }

    public function users() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Xử lý tạo người dùng mới
                    $data = [
                        'name' => $_POST['name'],
                        'email' => $_POST['email'],
                        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                        'role' => $_POST['role']
                    ];
                    
                    if ($this->userModel->create($data)) {
                        $_SESSION['success'] = 'Thêm người dùng mới thành công.';
                        $this->redirect('/admin/users');
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi thêm người dùng mới.';
                    }
                }
                
                $this->render('admin/users/create', ['currentPage' => 'users']);
                break;
                
            case 'edit':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->redirect('/admin/users');
                }
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Xử lý cập nhật người dùng
                    $data = [
                        'name' => $_POST['name'],
                        'email' => $_POST['email'],
                        'role' => $_POST['role']
                    ];
                    
                    // Cập nhật mật khẩu nếu có
                    if (!empty($_POST['password'])) {
                        $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    }
                    
                    if ($this->userModel->update($id, $data)) {
                        $_SESSION['success'] = 'Cập nhật người dùng thành công.';
                        $this->redirect('/admin/users');
                    } else {
                        $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật người dùng.';
                    }
                }
                
                $user = $this->userModel->getById($id);
                $this->render('admin/users/edit', [
                    'user' => $user,
                    'currentPage' => 'users'
                ]);
                break;
                
            case 'delete':
                $id = $_GET['id'] ?? null;
                if ($id && $this->userModel->delete($id)) {
                    $_SESSION['success'] = 'Xóa người dùng thành công.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa người dùng.';
                }
                $this->redirect('/admin/users');
                break;
                
            default:
                // Hiển thị danh sách người dùng
                $users = $this->userModel->getAll();
                $this->render('admin/users/index', [
                    'users' => $users,
                    'currentPage' => 'users'
                ]);
        }
    }

    public function reviews() {
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'approve':
                $id = $_GET['id'] ?? null;
                if ($id && $this->reviewModel->updateStatus($id, REVIEW_STATUS_APPROVED)) {
                    $_SESSION['success'] = 'Duyệt đánh giá thành công.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi duyệt đánh giá.';
                }
                $this->redirect('/admin/reviews');
                break;
                
            case 'reject':
                $id = $_GET['id'] ?? null;
                if ($id && $this->reviewModel->updateStatus($id, REVIEW_STATUS_REJECTED)) {
                    $_SESSION['success'] = 'Từ chối đánh giá thành công.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi từ chối đánh giá.';
                }
                $this->redirect('/admin/reviews');
                break;
                
            case 'delete':
                $id = $_GET['id'] ?? null;
                if ($id && $this->reviewModel->delete($id)) {
                    $_SESSION['success'] = 'Xóa đánh giá thành công.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa đánh giá.';
                }
                $this->redirect('/admin/reviews');
                break;
                
            default:
                // Hiển thị danh sách đánh giá
                $reviews = $this->reviewModel->getAll();
                $this->render('admin/reviews/index', [
                    'reviews' => $reviews,
                    'currentPage' => 'reviews'
                ]);
        }
    }

    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý cập nhật cài đặt
            $settings = [
                'site_name' => $_POST['site_name'],
                'site_description' => $_POST['site_description'],
                'contact_email' => $_POST['contact_email'],
                'contact_phone' => $_POST['contact_phone'],
                'address' => $_POST['address']
            ];
            
            // TODO: Lưu cài đặt vào database hoặc file config
            
            $_SESSION['success'] = 'Cập nhật cài đặt thành công.';
            $this->redirect('/admin/settings');
        }
        
        $this->render('admin/settings', ['currentPage' => 'settings']);
    }

    public function updateBookingStatus() {
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;

        if (!$id || !$status) {
            $_SESSION['error'] = 'Thiếu thông tin cần thiết';
            header('Location: ' . BASE_URL . '/admin/bookings');
            exit;
        }

        if ($this->bookingModel->updateBookingStatus($id, $status)) {
            $_SESSION['success'] = 'Cập nhật trạng thái đặt phòng thành công';
        } else {
            $_SESSION['error'] = 'Cập nhật trạng thái đặt phòng thất bại';
        }

        header('Location: ' . BASE_URL . '/admin/bookings');
        exit;
    }

    public function updateServiceStatus() {
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;

        if (!$id || !$status) {
            $_SESSION['error'] = 'Thiếu thông tin cần thiết';
            header('Location: ' . BASE_URL . '/admin/services');
            exit;
        }

        if ($this->serviceModel->updateServiceStatus($id, $status)) {
            $_SESSION['success'] = 'Cập nhật trạng thái dịch vụ thành công';
        } else {
            $_SESSION['error'] = 'Cập nhật trạng thái dịch vụ thất bại';
        }

        header('Location: ' . BASE_URL . '/admin/services');
        exit;
    }

    public function updateReviewStatus() {
        $id = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;

        if (!$id || !$status) {
            $_SESSION['error'] = 'Thiếu thông tin cần thiết';
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        if ($this->reviewModel->updateStatus($id, $status)) {
            $_SESSION['success'] = 'Cập nhật trạng thái đánh giá thành công';
        } else {
            $_SESSION['error'] = 'Cập nhật trạng thái đánh giá thất bại';
        }

        header('Location: ' . BASE_URL . '/admin/reviews');
        exit;
    }

    private function uploadImage($file, $folder) {
        $target_dir = "uploads/" . $folder . "/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Kiểm tra file ảnh
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là ảnh.");
        }
        
        // Kiểm tra kích thước file
        if ($file["size"] > 5000000) {
            throw new Exception("File quá lớn.");
        }
        
        // Kiểm tra định dạng file
        if (!in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new Exception("Chỉ chấp nhận file JPG, JPEG, PNG & GIF.");
        }
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $new_filename;
        } else {
            throw new Exception("Có lỗi xảy ra khi upload file.");
        }
    }
} 