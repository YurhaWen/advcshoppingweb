<?php
class Database {
    private $pdo;

    public function __construct() {
        $host = 'localhost';     
        $dbname = 'advcshop_product'; 
        $username = 'advcshop';      
        $password = 'osttamvan123';          

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("ERROR: Could not connect. " . $e->getMessage());
        }
    }

    public function getOrders($filters = []) {
        $query = "SELECT 
            o.*
        FROM orders o WHERE 1=1";
        
        $params = [];

        if (!empty($filters['name'])) {
            $query .= " AND o.name LIKE :name";
            $params[':name'] = '%' . $filters['name'] . '%';
        }
        if (!empty($filters['address'])) {
            $query .= " AND o.address LIKE :address";
            $params[':address'] = '%' . $filters['address'] . '%';
        }
        if (!empty($filters['date'])) {
            $query .= " AND DATE(o.date) = :date";
            $params[':date'] = $filters['date'];
        }
        if (!empty($filters['type'])) {
            $query .= " AND o.items_json LIKE :type";
            $params[':type'] = '%' . $filters['type'] . '%';
        }
        if (!empty($filters['size'])) {
            $query .= " AND o.items_json LIKE :size";
            $params[':size'] = '%"size":"' . $filters['size'] . '"%';
        }
        if (!empty($filters['status'])) {
            $query .= " AND o.status = :status";
            $params[':status'] = $filters['status'];
        }

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            return [];
        }
    }

    public function updateOrderStatus($orderName, $orderDate, $status) {
        try {
            $query = "UPDATE orders SET status = :status WHERE name = :name AND DATE(date) = :date";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([
                ':status' => $status,
                ':name' => $orderName,
                ':date' => $orderDate
            ]);
        } catch(PDOException $e) {
            error_log("Status update error: " . $e->getMessage());
            return false;
        }
    }
}
?>