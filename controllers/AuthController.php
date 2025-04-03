<?php
require_once 'BaseController.php';
require_once 'models/UserModel.php';

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
                $this->redirect('/login');
            }

            $user = $this->userModel->getByEmail($email);
            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION['error'] = 'Email hoặc mật khẩu không chính xác';
                $this->redirect('/login');
            }

            // Lưu thông tin user vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Chuyển hướng dựa vào role
            if ($user['role'] === ROLE_ADMIN) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/');
            }
        }

        $this->render('auth/login');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? ''
            ];

            // Validate dữ liệu
            if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password'])) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
                $this->redirect('/register');
            }

            if ($data['password'] !== $data['confirm_password']) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
                $this->redirect('/register');
            }

            if ($this->userModel->getByEmail($data['email'])) {
                $_SESSION['error'] = 'Email đã tồn tại';
                $this->redirect('/register');
            }

            // Mã hóa mật khẩu
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['role'] = ROLE_USER;

            // Tạo user mới
            if ($this->userModel->create($data)) {
                $_SESSION['success'] = 'Đăng ký thành công. Vui lòng đăng nhập';
                $this->redirect('/login');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại';
                $this->redirect('/register');
            }
        }

        $this->render('auth/register');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $user = $this->userModel->findByEmail($email);

            if ($user) {
                // Tạo token reset password
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Lưu token vào database
                $this->userModel->saveResetToken($user['id'], $token, $expires);
                
                // Gửi email reset password
                $resetLink = BASE_URL . '/reset-password?token=' . $token;
                $this->sendResetPasswordEmail($email, $resetLink);
                
                $success = 'Vui lòng kiểm tra email của bạn để đặt lại mật khẩu';
                $this->render('auth/forgot-password', ['success' => $success]);
            } else {
                $error = 'Email không tồn tại trong hệ thống';
                $this->render('auth/forgot-password', ['error' => $error]);
            }
        } else {
            $this->render('auth/forgot-password');
        }
    }

    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $this->redirect('/forgot-password');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['password', 'confirm_password']);
            
            if (empty($errors)) {
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
                } else {
                    $userId = $this->userModel->validateResetToken($token);
                    if ($userId) {
                        if ($this->userModel->updatePassword($userId, $_POST['password'])) {
                            $this->redirect('/login?reset=1');
                        } else {
                            $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                        }
                    } else {
                        $errors['general'] = 'Token không hợp lệ hoặc đã hết hạn';
                    }
                }
            }

            $this->render('auth/reset-password', ['errors' => $errors]);
        } else {
            $this->render('auth/reset-password');
        }
    }

    private function sendResetPasswordEmail($email, $resetLink) {
        $to = $email;
        $subject = 'Đặt lại mật khẩu - ' . SITE_NAME;
        $message = "Vui lòng click vào link sau để đặt lại mật khẩu: " . $resetLink;
        $headers = "From: " . SMTP_USERNAME;

        mail($to, $subject, $message, $headers);
    }
} 