<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 text-primary mb-4">404</h1>
                <h2 class="mb-4">Không tìm thấy trang</h2>
                <p class="lead text-muted mb-5">
                    Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?php echo BASE_URL; ?>?act=home" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>Về trang chủ
                    </a>
                    <a href="<?php echo BASE_URL; ?>?act=rooms" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-bed me-2"></i>Xem phòng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    padding: 100px 0;
}

.error-page h1 {
    font-size: 8rem;
    font-weight: 700;
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.error-page h2 {
    font-size: 2.5rem;
    font-weight: 600;
}

.error-page .lead {
    font-size: 1.25rem;
}

.btn-lg {
    padding: 1rem 2rem;
    font-weight: 500;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 