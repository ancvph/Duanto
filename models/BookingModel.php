<?php
require_once 'BaseModel.php';

class BookingModel extends BaseModel {
    protected $table = 'bookings';

    public function __construct() {
        parent::__construct();
    }

    public function createBooking($data) {
        // Tính toán tổng giá
        $roomModel = new RoomModel();
        $room = $roomModel->getById($data['room_id']);
        
        $checkIn = new DateTime($data['check_in']);
        $checkOut = new DateTime($data['check_out']);
        $days = $checkOut->diff($checkIn)->days;
        
        $data['total_amount'] = $room['price'] * $days;
        return $this->create($data);
    }

    public function getUserBookings($userId) {
        $query = "SELECT b.*, r.name as room_name, r.type as room_type 
                  FROM " . $this->table . " b 
                  JOIN rooms r ON b.room_id = r.id 
                  WHERE b.user_id = ? 
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingDetails($bookingId) {
        $query = "SELECT b.*, r.name as room_name, r.type as room_type, 
                  u.name as user_name, u.email as user_email 
                  FROM " . $this->table . " b 
                  JOIN rooms r ON b.room_id = r.id 
                  JOIN users u ON b.user_id = u.id 
                  WHERE b.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookingsByDateRange($startDate, $endDate) {
        $query = "SELECT b.*, r.name as room_name, u.name as user_name 
                  FROM " . $this->table . " b 
                  JOIN rooms r ON b.room_id = r.id 
                  JOIN users u ON b.user_id = u.id 
                  WHERE b.check_in BETWEEN ? AND ? 
                  OR b.check_out BETWEEN ? AND ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$startDate, $endDate, $startDate, $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyBookings($month, $year) {
        $query = "SELECT COUNT(*) as total_bookings, SUM(total_amount) as total_revenue 
                  FROM " . $this->table . " 
                  WHERE MONTH(created_at) = ? AND YEAR(created_at) = ? 
                  AND status = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$month, $year, BOOKING_STATUS_COMPLETED]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasUserBookedRoom($userId, $roomId) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE user_id = ? AND room_id = ? 
                  AND status = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId, $roomId, BOOKING_STATUS_COMPLETED]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] > 0;
    }

    public function countTotalBookings() {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_amount) as total FROM " . $this->table . " WHERE status = :status";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['status' => BOOKING_STATUS_COMPLETED]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getRecentBookings($limit = 5) {
        $sql = "SELECT b.*, r.name as room_name, u.name as user_name 
                FROM " . $this->table . " b 
                JOIN rooms r ON b.room_id = r.id 
                JOIN users u ON b.user_id = u.id 
                ORDER BY b.created_at DESC 
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyStats() {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total_bookings,
                    SUM(total_amount) as total_revenue
                FROM " . $this->table . " 
                WHERE status = :status
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month DESC
                LIMIT 12";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['status' => BOOKING_STATUS_COMPLETED]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT b.*, r.name as room_name, u.name as user_name 
                FROM " . $this->table . " b 
                JOIN rooms r ON b.room_id = r.id 
                JOIN users u ON b.user_id = u.id 
                ORDER BY b.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT b.*, r.name as room_name, u.name as user_name 
                FROM " . $this->table . " b 
                JOIN rooms r ON b.room_id = r.id 
                JOIN users u ON b.user_id = u.id 
                WHERE b.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO " . $this->table . " (user_id, room_id, check_in, check_out, guests, total_amount, status) 
                VALUES (:user_id, :room_id, :check_in, :check_out, :guests, :total_amount, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'room_id' => $data['room_id'],
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'guests' => $data['guests'],
            'total_amount' => $data['total_amount'],
            'status' => BOOKING_STATUS_PENDING
        ]);
    }

    public function updateBookingStatus($id, $status) {
        $sql = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }
} 