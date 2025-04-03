<?php require_once 'views/layouts/header.php'; ?>

<!-- Search Results Section -->
<section class="search-results py-5">
    <div class="container">
        <h2 class="text-center mb-5">Kết quả tìm kiếm</h2>
        
        <!-- Search Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>?act=search" method="GET" class="row g-3">
                    <input type="hidden" name="act" value="search">
                    <div class="col-md-3">
                        <label for="check_in" class="form-label">Ngày nhận phòng</label>
                        <input type="date" class="form-control" id="check_in" name="check_in" 
                               value="<?php echo $filters['check_in']; ?>" required 
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="check_out" class="form-label">Ngày trả phòng</label>
                        <input type="date" class="form-control" id="check_out" name="check_out" 
                               value="<?php echo $filters['check_out']; ?>" required 
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label">Loại phòng</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Tất cả</option>
                            <?php foreach ($roomTypes as $type): ?>
                                <option value="<?php echo $type; ?>" 
                                        <?php echo $filters['type'] == $type ? 'selected' : ''; ?>>
                                    <?php echo $type; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="price_range" class="form-label">Khoảng giá</label>
                        <select class="form-select" id="price_range" name="price_range">
                            <option value="">Tất cả</option>
                            <option value="0-500000" <?php echo $filters['price_range'] == '0-500000' ? 'selected' : ''; ?>>
                                Dưới 500,000đ
                            </option>
                            <option value="500000-1000000" <?php echo $filters['price_range'] == '500000-1000000' ? 'selected' : ''; ?>>
                                500,000đ - 1,000,000đ
                            </option>
                            <option value="1000000-2000000" <?php echo $filters['price_range'] == '1000000-2000000' ? 'selected' : ''; ?>>
                                1,000,000đ - 2,000,000đ
                            </option>
                            <option value="2000000-999999999" <?php echo $filters['price_range'] == '2000000-999999999' ? 'selected' : ''; ?>>
                                Trên 2,000,000đ
                            </option>
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

        <!-- Results -->
        <?php if (empty($rooms)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Không tìm thấy phòng phù hợp với tiêu chí tìm kiếm của bạn.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($rooms as $room): ?>
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
        <?php endif; ?>
    </div>
</section>

<?php require_once 'views/layouts/footer.php'; ?> 