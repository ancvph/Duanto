<?php require_once 'views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Về chúng tôi</h1>
                <p class="lead mb-4">
                    <?php echo SITE_NAME; ?> - Đẳng cấp 5 sao với dịch vụ hoàn hảo, 
                    phòng nghỉ tiện nghi và đội ngũ nhân viên chuyên nghiệp.
                </p>
            </div>
            <div class="col-lg-6">
                <img src="<?php echo BASE_URL; ?>/assets/images/about-hero.jpg" alt="About Hero" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="about-content py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Câu chuyện của chúng tôi</h3>
                        <p class="card-text">
                            <?php echo SITE_NAME; ?> được thành lập năm 2020 với tầm nhìn trở thành 
                            khách sạn đẳng cấp 5 sao hàng đầu tại Việt Nam. Chúng tôi tự hào mang đến 
                            cho khách hàng những trải nghiệm nghỉ dưỡng tuyệt vời nhất với dịch vụ 
                            hoàn hảo và đội ngũ nhân viên chuyên nghiệp.
                        </p>
                        <p class="card-text">
                            Với vị trí đắc địa tại trung tâm thành phố, chúng tôi cung cấp 
                            các phòng nghỉ tiện nghi, hiện đại cùng nhiều dịch vụ đa dạng 
                            như spa, nhà hàng, hồ bơi và phòng họp.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Tầm nhìn & Sứ mệnh</h3>
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-eye me-2"></i>Tầm nhìn
                            </h5>
                            <p class="card-text">
                                Trở thành khách sạn đẳng cấp 5 sao hàng đầu tại Việt Nam, 
                                được khách hàng tin tưởng và lựa chọn.
                            </p>
                        </div>
                        <div>
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-bullseye me-2"></i>Sứ mệnh
                            </h5>
                            <p class="card-text">
                                Mang đến cho khách hàng những trải nghiệm nghỉ dưỡng tuyệt vời 
                                với dịch vụ hoàn hảo, phòng nghỉ tiện nghi và đội ngũ nhân viên 
                                chuyên nghiệp, tận tâm.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Tại sao chọn chúng tôi?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-star fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Đẳng cấp 5 sao</h5>
                        <p class="card-text text-muted">
                            Tiêu chuẩn quốc tế với dịch vụ hoàn hảo và cơ sở vật chất hiện đại.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Vị trí đắc địa</h5>
                        <p class="card-text text-muted">
                            Nằm ở trung tâm thành phố, dễ dàng di chuyển đến các địa điểm du lịch.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-concierge-bell fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Dịch vụ đa dạng</h5>
                        <p class="card-text text-muted">
                            Spa, nhà hàng, hồ bơi, phòng họp và nhiều tiện ích khác.
                        </p>
                    </div>
                </div>
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

.features .card {
    background: white;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?> 