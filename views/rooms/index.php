<?php require_once 'views/layouts/header.php'; ?>

<!-- Search Section -->
<section class="search-section bg-light py-5">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>?act=room-search" method="GET" class="row g-3">
                    <input type="hidden" name="act" value="room-search">
                    <div class="col-md-3">
                        <label class="form-label">Ngày nhận phòng</label>
                        <input type="date" class="form-control" name="check_in" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ngày trả phòng</label>
                        <input type="date" class="form-control" name="check_out" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Loại phòng</label>
                        <select class="form-select" name="type">
                            <option value="">Tất cả</option>
                            <?php foreach ($roomTypes as $type): ?>
                            <option value="<?php echo $type['id']; ?>">
                                <?php echo $type['name']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Khoảng giá</label>
                        <select class="form-select" name="price_range">
                            <option value="">Tất cả</option>
                            <option value="0-500000">Dưới 500.000đ</option>
                            <option value="500000-1000000">500.000đ - 1.000.000đ</option>
                            <option value="1000000-2000000">1.000.000đ - 2.000.000đ</option>
                            <option value="2000000-5000000">2.000.000đ - 5.000.000đ</option>
                            <option value="5000000-999999999">Trên 5.000.000đ</option>
                        </select>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Rooms List -->
<section class="rooms-section py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($rooms as $room): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="<?php echo BASE_URL; ?>/assets/images/rooms/<?php echo $room['image']; ?>" 
                             class="card-img-top" alt="<?php echo $room['name']; ?>">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge <?php echo $room['status'] === 'available' ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo $room['status'] === 'available' ? 'Còn trống' : 'Đã đặt'; ?>
                            </span>
                        </div>
                    </div>
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

<!-- Room Types -->
<section class="room-types bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Loại phòng</h2>
        <div class="row g-4">
            <?php foreach ($roomTypes as $type): ?>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="<?php echo $type['icon']; ?> fa-3x text-primary mb-3"></i>
                        <h5 class="card-title"><?php echo $type['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $type['description']; ?></p>
                        <a href="<?php echo BASE_URL; ?>?act=rooms&type=<?php echo $type['id']; ?>" 
                           class="btn btn-outline-primary">
                            Xem phòng
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.search-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.rooms-section .card-img-top {
    height: 200px;
    object-fit: cover;
}

.room-types .card {
    background: white;
}

.room-types .card:hover {
    background: #f8f9fa;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 