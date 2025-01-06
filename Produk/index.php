<?php
require_once 'db.php';
$db = new Database();
$products = $db->getProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ADVC - Product</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style2.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
</head>
<body>
    <header>
        <div class="logo">
            <a href="/Project PBL Kelompok 3/Landingpage/">ADVC</a>
        </div>
        <div class="nav">
            <div class="nav-link">
                <a href="/Home/">Home</a>
                <a href="/about-us/">About Us</a>
                <a class="active" href="#">Product</a>
            </div>
        </div>
        <div class="icons">
            <a href=""><i class="fas fa-user"></i></a>
            <a href="javascript:void(0)" id="cart-button">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count">0</span>
            </a>
        </div>
    </header>

    <main>
        <section class="hero">
            <h1>Bringing you quality goods</h1>
            <p>We provide the products we offer</p>
        </section>

        <section class="products">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product">
                <?php 
                // Check if image exists in database and is not empty
                if (!empty($product['image'])): 
                    // Check if image is a full URL
                    if (filter_var($product['image'], FILTER_VALIDATE_URL)): ?>
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                    <?php 
                    // If not a URL, use the /product-stock/ path
                    else: ?>
                        <img src="/product-stock/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                    <?php endif; ?>
                <?php 
                // Show default image if no image is found
                else: ?>
                    <img src="/product-stock/default-product.jpg" alt="<?= htmlspecialchars($product['name']) ?>" />
                <?php endif; ?>
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="category"><?= htmlspecialchars($product['category']) ?></p>
                    <p class="sizes">Available sizes: <?= htmlspecialchars($product['sizes']) ?></p>
                    <p class="stock">Pieces left: <?= htmlspecialchars($product['piece']) ?></p>
                    <p class="price">Rp <?= number_format($product['price'], 0, ",", ".") ?></p>
                </div>
                <div class="button-group">
                    <button class="buy-now" onclick="openSizeModal('<?= number_format($product['price'], 0, ',', '.') ?>', '<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>')">
                        Buy now
                    </button>
                    <button class="add-to-cart" onclick="openSizeModal('<?= number_format($product['price'], 0, ',', '.') ?>', '<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>')">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</section>
    </main>

    <!-- Cart Modal -->
    <div id="cart-modal" class="modal">
        <div class="modal-content">
            <h2>Your Shopping Cart</h2>
            <p class="cart-count">You have <span id="cart-items-count">0</span> items in your cart.</p>
            <div id="cart-items-list"></div>
            <div class="modal-buttons">
                <button class="checkout-btn" onclick="checkout()">Check Out</button>
                <button class="close-btn" onclick="closeCartModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Size Modal -->
    <div id="size-modal" class="modal">
        <div class="modal-content">
            <h2>Select Size</h2>
            <form id="size-form">
                <div class="sizes">
                    <input type="radio" id="size-s" name="size" value="S" required />
                    <label for="size-s">S</label>
                    
                    <input type="radio" id="size-m" name="size" value="M" required />
                    <label for="size-m">M</label>
                    
                    <input type="radio" id="size-l" name="size" value="L" required />
                    <label for="size-l">L</label>
                    
                    <input type="radio" id="size-xl" name="size" value="XL" required />
                    <label for="size-xl">XL</label>
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="confirm-btn">Confirm</button>
                    <button type="button" class="close-btn" onclick="closeSizeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>