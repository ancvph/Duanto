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

            $user = $this->userModel->findByEmail($email);

            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                $this->redirect('/');
            } else {
                $error = 'Email hoặc mật khẩu không chính xác';
                $this->render('auth/login', ['error' => $error]);
            }
        } else {
            $this->render('auth/login');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequest(['name', 'email', 'password', 'confirm_password']);
            
            if (empty($errors)) {
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
                } else {
                    $existingUser = $this->userModel->findByEmail($_POST['email']);
                    if ($existingUser) {
                        $errors['email'] = 'Email đã được sử dụng';
                    } else {
                        $userData = [
                            'name' => $_POST['name'],
                            'email' => $_POST['email'],
                            'password' => $_POST['password'],
                            'role' => ROLE_USER
                        ];

                        if ($this->userModel->createUser($userData)) {
                            $this->redirect('/login?registered=1');
                        } else {
                            $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
                        }
                    }
                }
            }

            $this->render('auth/register', ['errors' => $errors]);
        } else {
            $this->render('auth/register');
        }
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