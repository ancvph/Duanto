<?php require_once 'views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Quản lý liên hệ</h5>
                        <div class="d-flex gap-2">
                            <a href="/admin/contacts/filter?status=pending" class="btn btn-warning btn-sm">
                                <i class="fas fa-clock me-1"></i>Chờ xử lý
                            </a>
                            <a href="/admin/contacts/filter?status=processing" class="btn btn-info btn-sm">
                                <i class="fas fa-spinner me-1"></i>Đang xử lý
                            </a>
                            <a href="/admin/contacts/filter?status=completed" class="btn btn-success btn-sm">
                                <i class="fas fa-check me-1"></i>Hoàn thành
                            </a>
                            <a href="/admin/contacts/filter?status=cancelled" class="btn btn-danger btn-sm">
                                <i class="fas fa-times me-1"></i>Đã hủy
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thanh tìm kiếm -->
                    <form action="/admin/contacts/search" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="keyword" 
                                   placeholder="Tìm kiếm theo tên, email, chủ đề..." 
                                   value="<?php echo $keyword ?? ''; ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Bảng liên hệ -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th>Chủ đề</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contacts as $contact): ?>
                                    <tr>
                                        <td><?php echo $contact['id']; ?></td>
                                        <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                        <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                        <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                        <td>
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
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/admin/contacts/show/<?php echo $contact['id']; ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#updateStatusModal<?php echo $contact['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal<?php echo $contact['id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Modal cập nhật trạng thái -->
                                            <div class="modal fade" id="updateStatusModal<?php echo $contact['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Cập nhật trạng thái</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="/admin/contacts/update-status/<?php echo $contact['id']; ?>" method="POST">
                                                            <div class="modal-body">
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
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal xóa -->
                                            <div class="modal fade" id="deleteModal<?php echo $contact['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Xác nhận xóa</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Bạn có chắc chắn muốn xóa liên hệ này?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                            <a href="/admin/contacts/delete/<?php echo $contact['id']; ?>" 
                                                               class="btn btn-danger">Xóa</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 