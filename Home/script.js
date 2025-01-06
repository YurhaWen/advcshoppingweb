// Mendapatkan elemen yang dibutuhkan
const decreaseBtn = document.getElementById("decrease");
const increaseBtn = document.getElementById("increase");
const quantityElem = document.getElementById("quantity");
const priceElem = document.getElementById("price");
const subtotalElem = document.getElementById("subtotal");
const totalElem = document.getElementById("total");
const shippingCostElem = document.getElementById("shipping-cost");
const serviceFeeElem = document.getElementById("service-fee");
const productCountElem = document.getElementById("product-count");

// Harga awal
const unitPrice = 499000;
const shippingCost = 25000;
const serviceFee = 1000;

// Fungsi untuk memperbarui total
function updateTotal(quantity) {
  const subtotal = quantity * unitPrice;
  const total = subtotal + shippingCost + serviceFee;

  // Update elemen di HTML
  priceElem.textContent = `Rp ${subtotal.toLocaleString()}`;
  subtotalElem.textContent = `Rp ${subtotal.toLocaleString()}`;
  totalElem.textContent = `Rp ${total.toLocaleString()}`;
  productCountElem.textContent = quantity; // Update jumlah produk
  shippingCostElem.textContent = `Rp ${shippingCost.toLocaleString()}`;
  serviceFeeElem.textContent = `Rp ${serviceFee.toLocaleString()}`;
}

// Event listener untuk tombol "Decrease"
decreaseBtn.addEventListener("click", () => {
  let quantity = parseInt(quantityElem.textContent);
  if (quantity > 1) {
    quantity -= 1;
    quantityElem.textContent = quantity;
    updateTotal(quantity);
  }
});

// Event listener untuk tombol "Increase"
increaseBtn.addEventListener("click", () => {
  let quantity = parseInt(quantityElem.textContent);
  quantity += 1;
  quantityElem.textContent = quantity;
  updateTotal(quantity);
});

// Inisialisasi harga dan total saat halaman pertama kali dimuat
document.addEventListener("DOMContentLoaded", () => {
  const initialQuantity = parseInt(quantityElem.textContent);
  updateTotal(initialQuantity);
});
// Function to toggle the search bar visibility
function toggleSearch() {
  var searchBar = document.getElementById("search-bar");
  if (searchBar.style.display === "none" || searchBar.style.display === "") {
    searchBar.style.display = "block";
  } else {
    searchBar.style.display = "none";
  }
}

// Function to increment the shopping cart count
function incrementCart() {
  var cartCount = document.getElementById("cart-count");
  var currentCount = parseInt(cartCount.textContent);
  cartCount.textContent = currentCount + 1;
}
