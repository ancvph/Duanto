<?php require_once 'views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Chào mừng đến với <?php echo SITE_NAME; ?></h1>
                <p class="lead mb-4">
                    Trải nghiệm đẳng cấp 5 sao với dịch vụ hoàn hảo, 
                    phòng nghỉ tiện nghi và đội ngũ nhân viên chuyên nghiệp.
                </p>
                <a href="<?php echo BASE_URL; ?>?act=rooms" class="btn btn-light btn-lg">
                    <i class="fas fa-bed me-2"></i>Xem phòng
                </a>
            </div>
            <div class="col-lg-6">
                <img src="<?php echo BASE_URL; ?>/assets/images/hero.jpg" alt="Hotel Hero" class="img-fluid rounded shadow">
            </div>
        </div>
        
        <!-- Search Form -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <form action="<?php echo BASE_URL; ?>?act=search" method="GET" class="row g-3">
                            <input type="hidden" name="act" value="search">
                            <div class="col-md-3">
                                <label for="check_in" class="form-label">Ngày nhận phòng</label>
                                <input type="date" class="form-control" id="check_in" name="check_in" required 
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="check_out" class="form-label">Ngày trả phòng</label>
                                <input type="date" class="form-control" id="check_out" name="check_out" required 
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label">Loại phòng</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">Tất cả</option>
                                    <?php foreach ($roomTypes as $type): ?>
                                        <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="price_range" class="form-label">Khoảng giá</label>
                                <select class="form-select" id="price_range" name="price_range">
                                    <option value="">Tất cả</option>
                                    <option value="0-500000">Dưới 500,000đ</option>
                                    <option value="500000-1000000">500,000đ - 1,000,000đ</option>
                                    <option value="1000000-2000000">1,000,000đ - 2,000,000đ</option>
                                    <option value="2000000-999999999">Trên 2,000,000đ</option>
                                </select>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search me-2"></i>Tìm kiếm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Rooms -->
<section class="featured-rooms py-5">
    <div class="container">
        <h2 class="text-center mb-5">Phòng nghỉ nổi bật</h2>
        <div class="row g-4">
            <?php foreach ($featuredRooms as $room): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo BASE_URL; ?>/assets/images/rooms/<?php echo $room['image']; ?>" 
                         class="card-img-top" alt="<?php echo $room['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $room['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $room['description']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 text-primary">
                                <?php echo number_format($room['price'], 0, ',', '.'); ?>đ/đêm
                            </span>
                            <a href="<?php echo BASE_URL; ?>?act=room-detail&id=<?php echo $room['id']; ?>" 
                               class="btn btn-outline-primary">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Services -->
<section class="services bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Dịch vụ của chúng tôi</h2>
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="<?php echo $service['icon']; ?> fa-3x text-primary mb-3"></i>
                        <h5 class="card-title"><?php echo $service['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $service['description']; ?></p>
                        <span class="badge bg-primary">
                            <?php echo number_format($service['price'], 0, ',', '.'); ?>đ
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Recent Reviews -->
<section class="reviews py-5">
    <div class="container">
        <h2 class="text-center mb-5">Đánh giá gần đây</h2>
        <div class="row g-4">
            <?php foreach ($recentReviews as $review): ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?php echo BASE_URL; ?>/assets/images/avatar.png" 
                                 alt="User Avatar" class="rounded-circle me-3" width="40">
                            <div>
                                <h6 class="mb-0"><?php echo $review['user_name']; ?></h6>
                                <small class="text-muted">
                                    <?php echo date('d/m/Y', strtotime($review['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="card-text"><?php echo $review['comment']; ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats bg-primary text-white py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4 mb-md-0">
                <i class="fas fa-bed fa-3x mb-3"></i>
                <h3 class="mb-0"><?php echo $stats['total_rooms']; ?></h3>
                <p class="mb-0">Tổng số phòng</p>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <i class="fas fa-calendar-check fa-3x mb-3"></i>
                <h3 class="mb-0"><?php echo $stats['total_bookings']; ?></h3>
                <p class="mb-0">Đặt phòng thành công</p>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <i class="fas fa-concierge-bell fa-3x mb-3"></i>
                <h3 class="mb-0"><?php echo $stats['total_services']; ?></h3>
                <p class="mb-0">Dịch vụ đa dạng</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-star fa-3x mb-3"></i>
                <h3 class="mb-0"><?php echo $stats['total_reviews']; ?></h3>
                <p class="mb-0">Đánh giá từ khách hàng</p>
            </div>
        </div>
    </div>
</section>

<style>
.hero {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    padding: 100px 0;
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.social-links a {
    transition: opacity 0.3s ease;
}

.social-links a:hover {
    opacity: 0.8;
}

.stats i {
    opacity: 0.9;
}

.stats h3 {
    font-size: 2.5rem;
    font-weight: bold;
}

.stats p {
    font-size: 1.1rem;
    opacity: 0.9;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 