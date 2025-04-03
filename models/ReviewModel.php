<?php
require_once 'BaseModel.php';

class ReviewModel extends BaseModel {
    protected $table = 'reviews';

    public function __construct() {
        parent::__construct();
    }

    public function getRoomReviews($roomId) {
        $query = "SELECT r.*, u.name as user_name 
                  FROM " . $this->table . " r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.room_id = ? 
                  ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$roomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageRating($roomId) {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                  FROM " . $this->table . " 
                  WHERE room_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$roomId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createReview($data) {
        // Kiểm tra xem người dùng đã từng đặt phòng này chưa
        $bookingModel = new BookingModel();
        $hasBooked = $bookingModel->hasUserBookedRoom($data['user_id'], $data['room_id']);
        
        if (!$hasBooked) {
            return false;
        }

        return $this->create($data);
    }

    public function getRecentReviews($limit = 5) {
        $query = "SELECT r.*, u.name as user_name, rm.name as room_name 
                  FROM " . $this->table . " r 
                  JOIN users u ON r.user_id = u.id 
                  JOIN rooms rm ON r.room_id = rm.id 
                  ORDER BY r.created_at DESC 
                  LIMIT $limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateReview($reviewId, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET rating = ?, comment = ? 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['rating'],
            $data['comment'],
            $reviewId
        ]);
    }

    public function deleteReview($reviewId) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$reviewId]);
    }

    public function countTotalReviews() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
} 