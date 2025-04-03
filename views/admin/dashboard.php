<?php require_once 'views/admin/layouts/header.php'; ?>

<!-- Dashboard Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Tổng số phòng</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_rooms']; ?></h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bed"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Đặt phòng</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_bookings']; ?></h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Dịch vụ</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_services']; ?></h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Đánh giá</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_reviews']; ?></h2>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Đặt phòng gần đây</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Phòng</th>
                        <th>Ngày nhận</th>
                        <th>Ngày trả</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['recent_bookings'] as $booking): ?>
                    <tr>
                        <td>#<?php echo $booking['id']; ?></td>
                        <td><?php echo $booking['user_name']; ?></td>
                        <td><?php echo $booking['room_name']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($booking['check_in'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($booking['check_out'])); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $booking['status'] == BOOKING_STATUS_CONFIRMED ? 'success' : 
                                    ($booking['status'] == BOOKING_STATUS_PENDING ? 'warning' : 
                                    ($booking['status'] == BOOKING_STATUS_CANCELLED ? 'danger' : 'info')); 
                            ?>">
                                <?php echo $booking['status']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/admin/bookings?action=view&id=<?php echo $booking['id']; ?>" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Reviews -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Đánh giá gần đây</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Phòng</th>
                        <th>Đánh giá</th>
                        <th>Nội dung</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['recent_reviews'] as $review): ?>
                    <tr>
                        <td>#<?php echo $review['id']; ?></td>
                        <td><?php echo $review['user_name']; ?></td>
                        <td><?php echo $review['room_name']; ?></td>
                        <td>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                            <?php endfor; ?>
                        </td>
                        <td><?php echo substr($review['content'], 0, 50) . '...'; ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $review['status'] == REVIEW_STATUS_APPROVED ? 'success' : 
                                    ($review['status'] == REVIEW_STATUS_PENDING ? 'warning' : 'danger'); 
                            ?>">
                                <?php echo $review['status']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($review['status'] == REVIEW_STATUS_PENDING): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/reviews?action=approve&id=<?php echo $review['id']; ?>" 
                               class="btn btn-sm btn-success">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="<?php echo BASE_URL; ?>/admin/reviews?action=reject&id=<?php echo $review['id']; ?>" 
                               class="btn btn-sm btn-danger">
                                <i class="fas fa-times"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'views/admin/layouts/footer.php'; ?> 