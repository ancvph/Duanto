<?php require_once 'views/layouts/header.php'; ?>

<!-- Room Detail -->
<section class="room-detail py-5">
    <div class="container">
        <!-- Image Carousel -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($room['images'] as $index => $image): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo BASE_URL; ?>/assets/images/rooms/<?php echo $image; ?>" 
                                 class="d-block w-100" alt="Room Image">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo $room['name']; ?></h3>
                        <p class="text-muted mb-3"><?php echo $room['description']; ?></p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h4 mb-0 text-primary">
                                <?php echo number_format($room['price'], 0, ',', '.'); ?>đ/đêm
                            </span>
                            <span class="badge <?php echo $room['status'] === 'available' ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo $room['status'] === 'available' ? 'Còn trống' : 'Đã đặt'; ?>
                            </span>
                        </div>
                        <form action="<?php echo BASE_URL; ?>?act=booking" method="POST" class="booking-form">
                            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Ngày nhận phòng</label>
                                <input type="date" class="form-control" name="check_in" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày trả phòng</label>
                                <input type="date" class="form-control" name="check_out" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số người</label>
                                <select class="form-select" name="guests" required>
                                    <?php for ($i = 1; $i <= $room['max_guests']; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> người</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" 
                                    <?php echo $room['status'] !== 'available' ? 'disabled' : ''; ?>>
                                <i class="fas fa-calendar-check me-2"></i>Đặt phòng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Info -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Thông tin phòng</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-bed me-2 text-primary"></i>
                                        <?php echo $room['bed_type']; ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-users me-2 text-primary"></i>
                                        Tối đa <?php echo $room['max_guests']; ?> người
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-ruler-combined me-2 text-primary"></i>
                                        <?php echo $room['size']; ?>m²
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-wifi me-2 text-primary"></i>
                                        WiFi miễn phí
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-tv me-2 text-primary"></i>
                                        Smart TV
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-snowflake me-2 text-primary"></i>
                                        Điều hòa nhiệt độ
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-shower me-2 text-primary"></i>
                                        Phòng tắm riêng
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-coffee me-2 text-primary"></i>
                                        Mini bar
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Dịch vụ bổ sung</h5>
                        <?php foreach ($services as $service): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" 
                                   value="<?php echo $service['id']; ?>" id="service<?php echo $service['id']; ?>">
                            <label class="form-check-label" for="service<?php echo $service['id']; ?>">
                                <?php echo $service['name']; ?> 
                                (<?php echo number_format($service['price'], 0, ',', '.'); ?>đ)
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Đánh giá</h4>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="<?php echo BASE_URL; ?>?act=room-review&id=<?php echo $room['id']; ?>" 
                              method="POST" class="mb-4">
                            <div class="mb-3">
                                <label class="form-label">Đánh giá của bạn</label>
                                <div class="rating">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" 
                                           id="star<?php echo $i; ?>" required>
                                    <label for="star<?php echo $i; ?>">
                                        <i class="fas fa-star"></i>
                                    </label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhận xét</label>
                                <textarea class="form-control" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                            </button>
                        </form>
                        <?php endif; ?>

                        <?php foreach ($reviews as $review): ?>
                        <div class="review-item border-bottom pb-3 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <img src="<?php echo BASE_URL; ?>/assets/images/avatar.png" 
                                     alt="User Avatar" class="rounded-circle me-3" width="40">
                                <div>
                                    <h6 class="mb-0"><?php echo $review['user_name']; ?></h6>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y', strtotime($review['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                            <div class="mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="mb-0"><?php echo $review['comment']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Rooms -->
<section class="related-rooms bg-light py-5">
    <div class="container">
        <h3 class="text-center mb-5">Phòng tương tự</h3>
        <div class="row g-4">
            <?php foreach ($relatedRooms as $relatedRoom): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo BASE_URL; ?>/assets/images/rooms/<?php echo $relatedRoom['image']; ?>" 
                         class="card-img-top" alt="<?php echo $relatedRoom['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $relatedRoom['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $relatedRoom['description']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 text-primary">
                                <?php echo number_format($relatedRoom['price'], 0, ',', '.'); ?>đ/đêm
                            </span>
                            <a href="<?php echo BASE_URL; ?>?act=room-detail&id=<?php echo $relatedRoom['id']; ?>" 
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

<style>
.carousel-item img {
    height: 500px;
    object-fit: cover;
}

.booking-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    padding: 0 0.2rem;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffd700;
}

.review-item:last-child {
    border-bottom: none !important;
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 