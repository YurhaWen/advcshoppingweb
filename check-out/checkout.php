<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkoutData = json_decode($_POST['checkoutData'], true);
    
    if ($checkoutData) {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $totalPrice = $checkoutData['totalPrice'];
        $items = $checkoutData['items'];
        
        // For single item checkout
        if (count($items) === 1) {
            $item = $items[0];
            $type = $item['type'];
            $size = $item['size'];
        } else {
            // For multiple items, you might want to store them separately
            // or choose how to handle multiple types/sizes
            $types = array_map(function($item) { return $item['type']; }, $items);
            $sizes = array_map(function($item) { return $item['size']; }, $items);
            $type = implode(', ', array_unique($types));
            $size = implode(', ', array_unique($sizes));
        }

        // Your database insertion code here
        $stmt = $conn->prepare("INSERT INTO orders (name, address, date, price, status, type, size) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $date = date('Y-m-d');
        $status = 'In Progress';
        
        $stmt->bind_param("sssdsss", 
            $name,
            $address,
            $date,
            $totalPrice,
            $status,
            $type,
            $size
        );

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Order created successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error creating order"]);
        }
    }
}
?>