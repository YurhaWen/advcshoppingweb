// Wait for DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
  let cart = [];
  let currentProduct = null;

  // Get DOM elements
  const cartModal = document.getElementById("cart-modal");
  const sizeModal = document.getElementById("size-modal");
  const cartButton = document.getElementById("cart-button");
  const cartCount = document.getElementById("cart-count");
  const cartItemsCount = document.getElementById("cart-items-count");
  const cartItemsList = document.getElementById("cart-items-list");
  const sizeForm = document.getElementById("size-form");

  // Cart functionality
  function openCartModal() {
    if (cartModal) {
      cartModal.style.display = "block";
      updateCartDisplay();
    }
  }

  function closeCartModal() {
    if (cartModal) {
      cartModal.style.display = "none";
    }
  }

  function openSizeModal(price, name) {
    currentProduct = { name: name, price: price };
    if (sizeModal) {
      sizeModal.style.display = "block";
    }
  }

  function closeSizeModal() {
    if (sizeModal) {
      sizeModal.style.display = "none";
      currentProduct = null;
    }
  }

  // Add event listeners
  if (cartButton) {
    cartButton.addEventListener("click", openCartModal);
  }

  if (sizeForm) {
    sizeForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const sizeInput = document.querySelector('input[name="size"]:checked');
      if (sizeInput && currentProduct) {
        addToCart({
          ...currentProduct,
          size: sizeInput.value,
          quantity: 1,
        });
      }
      closeSizeModal();
    });
  }

  function addToCart(item) {
    cart.push(item);
    updateCartCount();
    updateCartDisplay();
  }

  function updateCartCount() {
    const count = cart.length;
    if (cartCount) cartCount.textContent = count;
    if (cartItemsCount) cartItemsCount.textContent = count;
  }

  function updateCartDisplay() {
    if (!cartItemsList) return;

    cartItemsList.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
      const itemPrice = parseFloat(item.price.replace(/[^\d]/g, ""));
      total += itemPrice;

      const itemElement = document.createElement("div");
      itemElement.className = "cart-item";
      itemElement.innerHTML = `
              <span>${item.name} - Size: ${item.size} -&nbsp;</span>
              <span> Rp ${item.price}</span>
              <button onclick="window.removeFromCart(${index})">Remove</button>
          `;
      cartItemsList.appendChild(itemElement);
    });

    if (cart.length > 0) {
      const totalElement = document.createElement("div");
      totalElement.className = "cart-total";
      totalElement.innerHTML = `<strong>Total: Rp ${total.toLocaleString()}</strong>`;
      cartItemsList.appendChild(totalElement);
    }
  }

  // Make removeFromCart available globally
  window.removeFromCart = function (index) {
    cart.splice(index, 1);
    updateCartCount();
    updateCartDisplay();
  };

  window.checkout = function () {
    if (cart.length === 0) {
      alert("Your cart is empty!");
      return;
    }

    const modalContent = document.querySelector(".modal-content");
    if (!modalContent) return;

    modalContent.innerHTML = `
          <h2>Checkout Information</h2>
          <form id="checkout-form">
              <div class="form-group">
                  <label for="checkout-name">Name&nbsp;:&nbsp;</label>
                  <br>
                  <input type="text" id="checkout-name" required>
              </div>
              <br>
              <div class="form-group">
                  <label for="checkout-address">Delivery Address&nbsp;:&nbsp;</label>
                  <br>
                  <textarea id="checkout-address" required></textarea>
              </div>
<br>
              <div class="cart-actions">
                  <button type="submit" class="checkout-btn">Confirm Order</button>
                  <button type="button" class="close-btn" onclick="closeCartModal()">Cancel</button>
              </div>
          </form>
      `;

    const checkoutForm = document.getElementById("checkout-form");
    if (checkoutForm) {
      checkoutForm.addEventListener("submit", function (e) {
        e.preventDefault();
        processCheckout();
      });
    }
  };

  function processCheckout() {
    const nameInput = document.getElementById("checkout-name");
    const addressInput = document.getElementById("checkout-address");

    if (!nameInput || !addressInput) return;

    const name = nameInput.value;
    const address = addressInput.value;

    if (!name || !address) {
      alert("Please fill in all required fields");
      return;
    }

    const totalPrice = cart.reduce(
      (sum, item) => sum + parseFloat(item.price.replace(/[^\d]/g, "")),
      0
    );

    const checkoutData = {
      items: cart.map((item) => ({
        name: item.name,
        size: item.size,
        price: parseFloat(item.price.replace(/[^\d]/g, "")),
      })),
      totalPrice: totalPrice,
    };

    const formData = new FormData();
    formData.append("name", name);
    formData.append("address", address);
    formData.append("checkoutData", JSON.stringify(checkoutData));

    fetch("checkout.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Order placed successfully!");
          cart = [];
          updateCartCount();
          closeCartModal();
        } else {
          throw new Error(data.message || "Error placing order");
        }
      })
      .catch((error) => {
        alert(error.message);
      });
  }

  // Make functions available globally
  window.openSizeModal = openSizeModal;
  window.closeSizeModal = closeSizeModal;
  window.closeCartModal = closeCartModal;
});
