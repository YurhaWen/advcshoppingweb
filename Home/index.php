<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ADVC - Home</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <!--Header-->
    <div class="header">
      <div class="logo">
        <a href="/Project PBL Kelompok 3/Landingpage/">ADVC</a>
      </div>
      <div class="nav">
        <a class="active" href="#">Home</a>
        <a href="/about-us/">About Us</a>
        <a href="/Produk/">Produk</a>
      </div>
      <div class="icons">
        <a href="javascript:void(0)" onclick="toggleSearch()"
          ><i class="fas fa-search"></i
        ></a>
        <a href="javascript:void(0)" onclick="incrementCart()"
          ><i class="fas fa-shopping-cart"></i><span id="cart-count"></span
        ></a>
      </div>
    </div>

    <!-- Search Bar (Initially Hidden) -->
    <div id="search-bar" class="search-bar">
      <input type="text" placeholder="Search for products..." />
      <button onclick="toggleSearch()">Close</button>
    </div>

    <!--Main Content-->
    <div class="main-content">
      <div class="promo-container">
        <div class="text-content">
          <h1>HOODIE AND CREWNECK PRODUCTS</h1>
          <p>
            Made of quality materials and sewn very carefully, creating a luxury
            that is so enchanting
          </p>
        </div>
        <div class="promo-section">
          <div class="discount">
            <p>Get discount up to</p>
            <h2>30%</h2>
          </div>
          <a href="/Produk/" class="shop-btn">SHOP NOW</a>
        </div>
        <div class="promo-image">
          <img src="Discount.jpg" alt="Discount Image" />
        </div>
      </div>

      <!--Product-->
      <div class="products">
        <h2>Our Products</h2>
        <div class="product-list">
        <?php
$conn = new mysqli('localhost', 'advcshop', 'osttamvan123', 'advcshop_product');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, category, price, piece, sizes, image FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product">';

        $imagePath = '/product-stock/' . $row['image'];

        if (!empty($row['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
            echo '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($row['name']) . '"/>';
        } else {
            echo '<img src="/product-stock/uploads/default.jpg" alt="No Image Available"/>';
        }

        echo '<div class="product-info">';
        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
        echo '<p class="category">Category: ' . htmlspecialchars($row['category']) . '</p>';
        echo '<p class="size">Sizes: ' . htmlspecialchars($row['sizes']) . '</p>';
        echo '<p class="piece">Stock: ' . htmlspecialchars($row['piece']) . '</p>';
        echo '<p class="price">Rp ' . number_format($row["price"], 0, ",", ".") . '</p>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p>No products found.</p>';
}
?>


        </div>
        <a
          href="/Produk/"
          class="btn btn-primary btn-lg btn-block mt-4"
          style="background-color: #faa0a0; border-color: #faa0a0"
        >
          Go to Product
        </a>
      </div>
    </div>

    <!-- JavaScript Link -->
    <script src="script.js"></script>
  </body>
</html>
