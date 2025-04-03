<?php
require_once 'BaseModel.php';

class RoomModel extends BaseModel {
    protected $table = 'rooms';

    public function __construct() {
        parent::__construct();
    }

    public function getAvailableRooms($checkIn, $checkOut) {
        $query = "SELECT r.* FROM " . $this->table . " r 
                  LEFT JOIN bookings b ON r.id = b.room_id 
                  WHERE r.status = ? 
                  AND (b.id IS NULL 
                  OR (b.check_out <= ? OR b.check_in >= ?))";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([ROOM_STATUS_AVAILABLE, $checkIn, $checkOut]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomsByType($type) {
        $query = "SELECT * FROM " . $this->table . " WHERE type = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomsByPriceRange($minPrice, $maxPrice) {
        $query = "SELECT * FROM " . $this->table . " WHERE price BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$minPrice, $maxPrice]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRoomStatus($roomId, $status) {
        $query = "UPDATE " . $this->table . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $roomId]);
    }

    public function searchRooms($filters) {
        $conditions = [];
        $params = [];

        if (!empty($filters['type'])) {
            $conditions[] = "type = ?";
            $params[] = $filters['type'];
        }

        if (!empty($filters['min_price'])) {
            $conditions[] = "price >= ?";
            $params[] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $conditions[] = "price <= ?";
            $params[] = $filters['max_price'];
        }

        if (!empty($filters['status'])) {
            $conditions[] = "status = ?";
            $params[] = $filters['status'];
        }

        $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        $query = "SELECT * FROM " . $this->table . " " . $whereClause;
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedRooms($limit = 3) {
        $query = "SELECT r.*, 
                  (SELECT AVG(rating) FROM reviews WHERE room_id = r.id) as avg_rating 
                  FROM " . $this->table . " r 
                  WHERE r.status = ? 
                  ORDER BY avg_rating DESC 
                  LIMIT $limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([ROOM_STATUS_AVAILABLE]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomTypes() {
        $query = "SELECT DISTINCT type FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function countTotalRooms() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function countTotalBookings() {
        $query = "SELECT COUNT(*) as total FROM bookings WHERE status = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([BOOKING_STATUS_COMPLETED]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getRoomStats() {
        $query = "SELECT r.type,
                  COUNT(*) as total_rooms,
                  COUNT(CASE WHEN r.status = ? THEN 1 END) as available_rooms,
                  COUNT(CASE WHEN r.status = ? THEN 1 END) as booked_rooms,
                  COUNT(CASE WHEN r.status = ? THEN 1 END) as maintenance_rooms,
                  AVG(r.price) as avg_price
                  FROM " . $this->table . " r
                  GROUP BY r.type";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ROOM_STATUS_AVAILABLE,
            ROOM_STATUS_BOOKED,
            ROOM_STATUS_MAINTENANCE
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSimilarRooms($roomId, $roomType, $limit = 3) {
        $sql = "SELECT * FROM rooms 
                WHERE type = :type 
                AND id != :room_id 
                AND status = :status 
                LIMIT :limit";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':type', $roomType, PDO::PARAM_STR);
        $stmt->bindValue(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindValue(':status', ROOM_STATUS_AVAILABLE, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE rooms SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
} 