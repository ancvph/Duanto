<?php
// Định nghĩa đường dẫn gốc
define('ROOT_PATH', dirname(__DIR__));

// Include các file cần thiết
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';

try {
    // Đọc nội dung file SQL
    $sql = file_get_contents(__DIR__ . '/update_users_table.sql');
    
    // Thực thi câu lệnh SQL
    $pdo->exec($sql);
    
    echo "Cập nhật cấu trúc bảng users thành công!";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
} 