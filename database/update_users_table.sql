-- Thêm cột phone và address vào bảng users nếu chưa tồn tại
ALTER TABLE users
ADD COLUMN IF NOT EXISTS phone VARCHAR(20) NULL,
ADD COLUMN IF NOT EXISTS address TEXT NULL; 