<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Chi tiết liên hệ</h5>
                        <a href="/admin/contacts" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-4">Thông tin người liên hệ</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Họ tên</label>
                                        <p class="mb-0"><?php echo htmlspecialchars($contact['name']); ?></p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <p class="mb-0">
                                            <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" 
                                               class="text-decoration-none">
                                                <?php echo htmlspecialchars($contact['email']); ?>
                                            </a>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Số điện thoại</label>
                                        <p class="mb-0">
                                            <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>" 
                                               class="text-decoration-none">
                                                <?php echo htmlspecialchars($contact['phone']); ?>
                                            </a>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Chủ đề</label>
                                        <p class="mb-0"><?php echo htmlspecialchars($contact['subject']); ?></p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Trạng thái</label>
                                        <p class="mb-0">
                                            <?php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusText = [
                                                'pending' => 'Chờ xử lý',
                                                'processing' => 'Đang xử lý',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass[$contact['status']]; ?>">
                                                <?php echo $statusText[$contact['status']]; ?>
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Ngày tạo</label>
                                        <p class="mb-0"><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-4">Nội dung tin nhắn</h6>
                                    <div class="bg-light p-3 rounded">
                                        <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-4">Cập nhật trạng thái</h6>
                                    <form action="/admin/contacts/update-status/<?php echo $contact['id']; ?>" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Trạng thái</label>
                                            <select class="form-select" name="status" required>
                                                <option value="pending" <?php echo $contact['status'] == 'pending' ? 'selected' : ''; ?>>
                                                    Chờ xử lý
                                                </option>
                                                <option value="processing" <?php echo $contact['status'] == 'processing' ? 'selected' : ''; ?>>
                                                    Đang xử lý
                                                </option>
                                                <option value="completed" <?php echo $contact['status'] == 'completed' ? 'selected' : ''; ?>>
                                                    Hoàn thành
                                                </option>
                                                <option value="cancelled" <?php echo $contact['status'] == 'cancelled' ? 'selected' : ''; ?>>
                                                    Đã hủy
                                                </option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Cập nhật
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 