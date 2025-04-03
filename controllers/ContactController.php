<?php
require_once 'BaseController.php';
require_once 'models/ContactModel.php';

class ContactController extends BaseController {
    private $contactModel;

    public function __construct() {
        $this->contactModel = new ContactModel();
    }

    public function index() {
        // Render trang liên hệ
        $this->render('contact/index', [
            'title' => 'Liên hệ'
        ]);
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['name', 'email', 'phone', 'subject', 'message']);
            
            if (empty($errors)) {
                $contactData = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                    'subject' => $_POST['subject'],
                    'message' => $_POST['message']
                ];

                if ($this->contactModel->create($contactData)) {
                    $_SESSION['success'] = 'Gửi tin nhắn thành công! Chúng tôi sẽ phản hồi sớm nhất có thể.';
                    $this->redirect('/contact');
                } else {
                    $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }

            $this->render('contact/index', [
                'errors' => $errors,
                'title' => 'Liên hệ'
            ]);
        } else {
            $this->redirect('/contact');
        }
    }

    // Phương thức dành cho Admin
    public function adminIndex() {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        // Lấy danh sách tất cả liên hệ
        $contacts = $this->contactModel->getAll();
        
        $this->render('admin/contacts/index', [
            'contacts' => $contacts,
            'title' => 'Quản lý liên hệ'
        ]);
    }

    public function adminShow($id) {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        // Lấy thông tin chi tiết liên hệ
        $contact = $this->contactModel->getById($id);
        if (!$contact) {
            $this->redirect('/admin/contacts');
        }

        $this->render('admin/contacts/show', [
            'contact' => $contact,
            'title' => 'Chi tiết liên hệ'
        ]);
    }

    public function adminUpdateStatus($id) {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            
            if (in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
                if ($this->contactModel->updateStatus($id, $status)) {
                    $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                }
            }
        }

        $this->redirect('/admin/contacts');
    }

    public function adminDelete($id) {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        if ($this->contactModel->delete($id)) {
            $_SESSION['success'] = 'Xóa liên hệ thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }

        $this->redirect('/admin/contacts');
    }

    public function adminSearch() {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        $keyword = $_GET['keyword'] ?? '';
        $contacts = [];

        if (!empty($keyword)) {
            $contacts = $this->contactModel->search($keyword);
        } else {
            $contacts = $this->contactModel->getAll();
        }

        $this->render('admin/contacts/index', [
            'contacts' => $contacts,
            'keyword' => $keyword,
            'title' => 'Tìm kiếm liên hệ'
        ]);
    }

    public function adminFilter() {
        // Kiểm tra quyền admin
        $this->requireAdmin();

        $status = $_GET['status'] ?? '';
        $contacts = [];

        if (!empty($status)) {
            $contacts = $this->contactModel->getByStatus($status);
        } else {
            $contacts = $this->contactModel->getAll();
        }

        $this->render('admin/contacts/index', [
            'contacts' => $contacts,
            'status' => $status,
            'title' => 'Lọc liên hệ'
        ]);
    }
} 