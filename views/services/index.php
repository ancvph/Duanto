<?php require_once 'views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Dịch vụ của chúng tôi</h1>
                <p class="lead mb-4">
                    Trải nghiệm dịch vụ đẳng cấp 5 sao với đội ngũ nhân viên chuyên nghiệp, 
                    tận tâm phục vụ 24/7.
                </p>
            </div>
            <div class="col-lg-6">
                <img src="<?php echo BASE_URL; ?>/assets/images/services-hero.jpg" alt="Services Hero" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Services List -->
<section class="services-list py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="<?php echo $service['icon']; ?> fa-3x text-primary mb-3"></i>
                        <h5 class="card-title"><?php echo $service['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $service['description']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 text-primary">
                                <?php echo number_format($service['price'], 0, ',', '.'); ?>đ
                            </span>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" 
                                    data-bs-target="#serviceModal<?php echo $service['id']; ?>">
                                Chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Service Modals -->
<?php foreach ($services as $service): ?>
<div class="modal fade" id="serviceModal<?php echo $service['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $service['name']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="<?php echo BASE_URL; ?>/assets/images/services/<?php echo $service['image']; ?>" 
                             class="img-fluid rounded mb-3" alt="<?php echo $service['name']; ?>">
                        <p class="text-muted"><?php echo $service['description']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Chi tiết dịch vụ:</h6>
                        <ul class="list-unstyled">
                            <?php foreach (explode("\n", $service['details']) as $detail): ?>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <?php echo $detail; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="mt-4">
                            <h5 class="text-primary mb-3">
                                <?php echo number_format($service['price'], 0, ',', '.'); ?>đ
                            </h5>
                            <a href="<?php echo BASE_URL; ?>/contact" class="btn btn-primary">
                                <i class="fas fa-envelope me-2"></i>Liên hệ đặt dịch vụ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

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

.modal-content {
    border: none;
    border-radius: 10px;
}

.modal-header {
    border-bottom: 1px solid #eee;
}

.modal-body {
    padding: 2rem;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 