<?php
// Định nghĩa các hằng số cơ bản
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Hotel Management System');
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/DuAnTo');
}

// Định nghĩa các trạng thái
define('ROOM_STATUS_AVAILABLE', 'available');
define('ROOM_STATUS_BOOKED', 'booked');
define('ROOM_STATUS_MAINTENANCE', 'maintenance');

define('BOOKING_STATUS_PENDING', 'pending');
define('BOOKING_STATUS_CONFIRMED', 'confirmed');
define('BOOKING_STATUS_CANCELLED', 'cancelled');
define('BOOKING_STATUS_COMPLETED', 'completed');

define('SERVICE_STATUS_ACTIVE', 'active');
define('SERVICE_STATUS_INACTIVE', 'inactive');

define('REVIEW_STATUS_PENDING', 'pending');
define('REVIEW_STATUS_APPROVED', 'approved');
define('REVIEW_STATUS_REJECTED', 'rejected');

// Định nghĩa các role người dùng
define('ROLE_USER', 'user');
define('ROLE_ADMIN', 'admin');

// Cấu hình email
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-password');
define('SMTP_FROM_EMAIL', 'your-email@gmail.com');
define('SMTP_FROM_NAME', SITE_NAME);

// Cấu hình upload file
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Cấu hình session
define('SESSION_LIFETIME', 3600); // 1 hour 