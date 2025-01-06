// Parse prices at initialization
const cart = JSON.parse(localStorage.getItem("cart")) || [];
cart.forEach((item) => {
  // Ensure price is a number
  if (typeof item.price === "string") {
    item.price = parseInt(item.price.replace(/[^0-9]/g, ""), 10); // Remove non-numeric characters
  }
});

const shippingCost = 25000;
const serviceFee = 1000;

function calculateSubtotal() {
  return cart.reduce((total, item) => total + item.price * item.quantity, 0);
}

function formatPrice(price) {
  return `Rp ${price.toLocaleString()}`;
}

function updateCheckout() {
  const cartItemsContainer = document.getElementById("cart-items");
  const productCountElement = document.getElementById("product-count");
  const productCountSummaryElement = document.getElementById(
    "product-count-summary"
  );
  const subtotalElement = document.getElementById("subtotal");
  const totalElement = document.getElementById("total");

  cartItemsContainer.innerHTML = "";

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = "<p>Your cart is empty.</p>";
    productCountElement.textContent = 0;
    productCountSummaryElement.textContent = 0;
    subtotalElement.textContent = formatPrice(0);
    totalElement.textContent = formatPrice(0);
    return;
  }

  productCountElement.textContent = cart.length;
  productCountSummaryElement.textContent = cart.length;

  cart.forEach((item, index) => {
    const productElement = document.createElement("div");
    productElement.classList.add("product-details");
    productElement.innerHTML = `
      <div class="product-image">
        <img src="${item.image}" alt="${item.name}" />
      </div>
      <div class="product-info">
        <h2>${item.name}</h2>
        <p>${item.description || ""}</p>
        <p>Size: ${item.size || "-"}</p>
      </div>
      <div class="product-price">
        <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
        <span>${item.quantity}</span>
        <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
        <p>${formatPrice(item.price)}</p>
      </div>
    `;
    cartItemsContainer.appendChild(productElement);
  });

  const subtotal = calculateSubtotal();
  subtotalElement.textContent = formatPrice(subtotal);

  const total = subtotal + shippingCost + serviceFee;
  totalElement.textContent = formatPrice(total);
}

function updateQuantity(index, change) {
  cart[index].quantity += change;

  if (cart[index].quantity <= 0) {
    cart.splice(index, 1); // Remove item if quantity is less than or equal to 0
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  updateCheckout();
}

document.addEventListener("DOMContentLoaded", () => {
  updateCheckout();
});
