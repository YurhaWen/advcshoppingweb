<?php
require_once 'koneksi.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize database connection
$db = new Database();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['date']) && isset($_POST['status'])) {
    $orderName = $_POST['name'];
    $orderDate = $_POST['date'];
    $status = $_POST['status'];
    
    $success = $db->updateOrderStatus($orderName, $orderDate, $status);
    if ($success) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating status";
    }
}

// Handle filtering
$filters = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['filter-name'])) $filters['name'] = $_GET['filter-name'];
    if (!empty($_GET['filter-address'])) $filters['address'] = $_GET['filter-address'];
    if (!empty($_GET['filter-date'])) $filters['date'] = $_GET['filter-date'];
    if (!empty($_GET['filter-type'])) $filters['type'] = $_GET['filter-type'];
    if (!empty($_GET['filter-size'])) $filters['size'] = $_GET['filter-size'];
    if (!empty($_GET['filter-status'])) $filters['status'] = $_GET['filter-status'];
}

// Fetch orders with applied filters
$orders = $db->getOrders($filters);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Filter Table</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  </head>
  <body>
    <header class="header">
      <div class="logo">ADVC</div>
      <div class="search-bar">
        <i class="fas fa-search"></i>
        <input placeholder="Search" type="text" />
      </div>
      <div class="user-info">
        <div class="country-info">
          <img
            class="flag"
            alt="Flag of Indonesia"
            src="https://upload.wikimedia.org/wikipedia/commons/9/9f/Flag_of_Indonesia.svg"
          />
          <span class="country-name">Indonesia</span>
        </div>
        <div class="admin-info">
          <img
            class="avatar"
            alt="User Avatar"
            src="https://storage.googleapis.com/a1aa/image/a1QZlEWzDDbyB9avUEU67x6UJvRcHtiyNHyBp8zps6FWRJ9E.jpg"
          />
          <div class="admin-details">
            <span class="admin-name">Faiz</span>
            <span class="admin-role">Admin</span>
          </div>
        </div>
      </div>
    </header>

    <div class="sidebar">
      <ul>
        <a href="#"><li><i class="fas fa-tachometer-alt"></i> Dashboard</li></a>
        <a href="#"><li><i class="fas fa-box"></i> Products</li></a>
        <a href="#"><li><i class="fas fa-heart"></i> Favourites</li></a>
        <a href="#"><div class="active"><li><i class="fas fa-list"></i> Order Lists</li></div></a>
        <a href="#"><li class="active"><i class="fas fa-warehouse"></i> Product Stock</li></a>
        <a href="#"><li><i class="fas fa-cog"></i> Settings</li></a>
      </ul>
    </div>

    <h1>Order List</h1>

    <div class="filter-container">
        <form method="GET">
            <input type="text" id="filter-name" name="filter-name" placeholder="Filter by Name" value="<?= htmlspecialchars($_GET['filter-name'] ?? '') ?>" />
            <input type="text" id="filter-address" name="filter-address" placeholder="Filter by Address" value="<?= htmlspecialchars($_GET['filter-address'] ?? '') ?>" />
            <input type="date" id="filter-date" name="filter-date" value="<?= htmlspecialchars($_GET['filter-date'] ?? '') ?>" />
            <select id="filter-type" name="filter-type">
                <option value="">Select Order Type</option>
                <option value="Hoodie" <?= (isset($_GET['filter-type']) && $_GET['filter-type'] == 'Hoodie') ? 'selected' : '' ?>>Hoodie</option>
                <option value="Crewneck" <?= (isset($_GET['filter-type']) && $_GET['filter-type'] == 'Crewneck') ? 'selected' : '' ?>>Crewneck</option>
                <option value="Sweater" <?= (isset($_GET['filter-type']) && $_GET['filter-type'] == 'Sweater') ? 'selected' : '' ?>>Sweater</option>
            </select>
            <select id="filter-size" name="filter-size"> 
                <option value="">Select Size</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>
            <select id="filter-status" name="filter-status">
                <option value="">Select Status</option>
                <option value="Completed" <?= (isset($_GET['filter-status']) && $_GET['filter-status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
                <option value="Rejected" <?= (isset($_GET['filter-status']) && $_GET['filter-status'] == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                <option value="In Progress" <?= (isset($_GET['filter-status']) && $_GET['filter-status'] == 'In Progress') ? 'selected' : '' ?>>In Progress</option>
            </select>
            <button type="submit">Apply Filter</button>
            <button type="button" onclick="window.location.href='<?= $_SERVER['PHP_SELF'] ?>'">Reset Filter</button>
        </form>
    </div>

    <div class="main">
        <div class="table-container">
            <table id="order-table">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>ADDRESS</th>
                        <th>DATE</th>
                        <th>TYPE</th>
                        <th>SIZE</th>
                        <th>PRICE</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <?php 
                        $items = json_decode($order['items_json'], true);
                        $firstItem = reset($items);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($order['name']) ?></td>
                            <td><?= htmlspecialchars($order['address']) ?></td>
                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($order['date']))) ?></td>
                            <td><?= htmlspecialchars($firstItem['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($firstItem['size'] ?? '') ?></td>
                            <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                            <td class="status"><?= htmlspecialchars($order['status']) ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="name" value="<?= htmlspecialchars($order['name']) ?>" />
                                    <input type="hidden" name="date" value="<?= htmlspecialchars(date('Y-m-d', strtotime($order['date']))) ?>" />
                                    <button type="submit" name="status" value="Completed" class="btn btn-success btn-sm action-btn">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button type="submit" name="status" value="Rejected" class="btn btn-danger btn-sm action-btn">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    <button type="submit" name="status" value="In Progress" class="btn btn-primary btn-sm action-btn">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No orders found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>