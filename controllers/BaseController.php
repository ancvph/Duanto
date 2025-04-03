<?php
class BaseController {
    protected function render($view, $data = []) {
        // Extract data để có thể sử dụng trong view
        extract($data);
        
        // Include header
        require_once 'views/layouts/header.php';
        
        // Include view
        require_once 'views/' . $view . '.php';
        
        // Include footer
        require_once 'views/layouts/footer.php';
    }

    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === ROLE_ADMIN;
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function validateRequest($required = []) {
        $errors = [];
        
        foreach ($required as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $errors[$field] = 'Trường này là bắt buộc';
            }
        }
        
        return $errors;
    }
} 