<?php
require_once 'BaseController.php';
require_once 'models/ServiceModel.php';

class ServiceController extends BaseController {
    private $serviceModel;

    public function __construct() {
        $this->serviceModel = new ServiceModel();
    }

    public function index() {
        // Lấy danh sách dịch vụ
        $services = $this->serviceModel->getActiveServices();
        
        // Render view với dữ liệu
        $this->render('services/index', [
            'services' => $services,
            'title' => 'Dịch vụ khách sạn'
        ]);
    }

    public function show($id) {
        // Lấy thông tin chi tiết dịch vụ
        $service = $this->serviceModel->getById($id);
        
        if (!$service) {
            $this->redirect('/services');
        }

        // Render view với dữ liệu
        $this->render('services/show', [
            'service' => $service,
            'title' => $service['name']
        ]);
    }

    public function order($serviceId) {
        // Kiểm tra đăng nhập
        $this->requireLogin();

        // Lấy thông tin dịch vụ
        $service = $this->serviceModel->getById($serviceId);
        if (!$service) {
            $this->redirect('/services');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['booking_id', 'quantity']);
            
            if (empty($errors)) {
                $orderData = [
                    'service_id' => $serviceId,
                    'booking_id' => $_POST['booking_id'],
                    'quantity' => $_POST['quantity'],
                    'total_price' => $service['price'] * $_POST['quantity'],
                    'status' => 'pending'
                ];

                if ($this->serviceModel->createServiceOrder($orderData)) {
                    $_SESSION['success'] = 'Đặt dịch vụ thành công!';
                    $this->redirect('/my-bookings');
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            // Lấy danh sách đặt phòng của người dùng
            $bookings = $this->serviceModel->getUserBookings($_SESSION['user_id']);
            
            $this->render('services/order', [
                'service' => $service,
                'bookings' => $bookings,
                'errors' => $errors,
                'title' => 'Đặt dịch vụ ' . $service['name']
            ]);
        } else {
            // Lấy danh sách đặt phòng của người dùng
            $bookings = $this->serviceModel->getUserBookings($_SESSION['user_id']);
            
            $this->render('services/order', [
                'service' => $service,
                'bookings' => $bookings,
                'title' => 'Đặt dịch vụ ' . $service['name']
            ]);
        }
    }

    public function cancel($orderId) {
        // Kiểm tra đăng nhập
        $this->requireLogin();

        // Lấy thông tin đơn đặt dịch vụ
        $order = $this->serviceModel->getServiceOrder($orderId);
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            $this->redirect('/my-bookings');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->serviceModel->updateServiceStatus($orderId, 'cancelled')) {
                $_SESSION['success'] = 'Hủy đặt dịch vụ thành công!';
                $this->redirect('/my-bookings');
            } else {
                $error = 'Có lỗi xảy ra, vui lòng thử lại';
                $this->render('services/cancel', [
                    'order' => $order,
                    'error' => $error,
                    'title' => 'Hủy đặt dịch vụ'
                ]);
            }
        } else {
            $this->render('services/cancel', [
                'order' => $order,
                'title' => 'Hủy đặt dịch vụ'
            ]);
        }
    }

    public function review($serviceId) {
        // Kiểm tra đăng nhập
        $this->requireLogin();

        // Lấy thông tin dịch vụ
        $service = $this->serviceModel->getById($serviceId);
        if (!$service) {
            $this->redirect('/services');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['rating', 'comment']);
            
            if (empty($errors)) {
                $reviewData = [
                    'service_id' => $serviceId,
                    'user_id' => $_SESSION['user_id'],
                    'rating' => $_POST['rating'],
                    'comment' => $_POST['comment']
                ];

                if ($this->serviceModel->createReview($reviewData)) {
                    $_SESSION['success'] = 'Gửi đánh giá thành công!';
                    $this->redirect('/services/' . $serviceId);
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $this->render('services/review', [
                'service' => $service,
                'errors' => $errors,
                'title' => 'Đánh giá dịch vụ ' . $service['name']
            ]);
        } else {
            $this->render('services/review', [
                'service' => $service,
                'title' => 'Đánh giá dịch vụ ' . $service['name']
            ]);
        }
    }

    // Phương thức dành cho Admin
    public function adminIndex() {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        // Lấy danh sách tất cả dịch vụ
        $services = $this->serviceModel->getAll();
        
        $this->render('admin/services/index', [
            'services' => $services,
            'title' => 'Quản lý dịch vụ'
        ]);
    }

    public function adminCreate() {
        // Kiểm tra quyền admin
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
                    $_SESSION['success'] = 'Thêm dịch vụ thành công!';
                    $this->redirect('/admin/services');
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $this->render('admin/services/create', [
                'errors' => $errors,
                'title' => 'Thêm dịch vụ mới'
            ]);
        } else {
            $this->render('admin/services/create', [
                'title' => 'Thêm dịch vụ mới'
            ]);
        }
    }

    public function adminEdit($id) {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        // Lấy thông tin dịch vụ
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
                    $_SESSION['success'] = 'Cập nhật dịch vụ thành công!';
                    $this->redirect('/admin/services');
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $this->render('admin/services/edit', [
                'service' => $service,
                'errors' => $errors,
                'title' => 'Chỉnh sửa dịch vụ'
            ]);
        } else {
            $this->render('admin/services/edit', [
                'service' => $service,
                'title' => 'Chỉnh sửa dịch vụ'
            ]);
        }
    }

    public function adminDelete($id) {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        if ($this->serviceModel->delete($id)) {
            $_SESSION['success'] = 'Xóa dịch vụ thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }

        $this->redirect('/admin/services');
    }

    public function adminOrders() {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        // Lấy danh sách đơn đặt dịch vụ
        $orders = $this->serviceModel->getAllOrders();
        
        $this->render('admin/services/orders', [
            'orders' => $orders,
            'title' => 'Quản lý đơn đặt dịch vụ'
        ]);
    }

    public function adminUpdateOrderStatus($orderId) {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            
            if (in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
                if ($this->serviceModel->updateServiceStatus($orderId, $status)) {
                    $_SESSION['success'] = 'Cập nhật trạng thái đơn hàng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }
        }

        $this->redirect('/admin/services/orders');
    }
} 