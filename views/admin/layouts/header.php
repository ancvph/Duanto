<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="d-flex">
        <div class="sidebar bg-dark text-white">
            <div class="sidebar-header p-3">
                <h4 class="mb-0"><?php echo SITE_NAME; ?></h4>
                <small>Admin Panel</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo $currentPage == 'dashboard' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/admin">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo $currentPage == 'rooms' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/admin/rooms">
                        <i class="fas fa-bed me-2"></i>Quản lý phòng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo $currentPage == 'bookings' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/admin/bookings">
                        <i class="fas fa-calendar-check me-2"></i>Quản lý đặt phòng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo $currentPage == 'services' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/admin/services">
                        <i class="fas fa-concierge-bell me-2"></i>Quản lý dịch vụ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo $currentPage == 'users' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/admin/users">
                        <i class="fas fa-users me-2"></i>Quản lý người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo $currentPage == 'reviews' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/admin/reviews">
                        <i class="fas fa-star me-2"></i>Quản lý đánh giá
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo $currentPage == 'settings' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/admin/settings">
                        <i class="fas fa-cog me-2"></i>Cài đặt
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-link" id="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>
                                <?php echo $_SESSION['user_name'] ?? 'Admin'; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/profile">
                                        <i class="fas fa-user me-2"></i>Hồ sơ
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/settings">
                                        <i class="fas fa-cog me-2"></i>Cài đặt
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid py-4"> 