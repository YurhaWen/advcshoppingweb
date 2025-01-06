// Element untuk Add Product
const openAddProductBtn = document.getElementById("openaddproductmodal");
const addProductPopup = document.getElementById("addProductPopup");
const addProductOverlay = document.getElementById("addProductOverlay");
const closeAddProductBtn = document.getElementById("closeAddProduct");

// Element untuk Edit Product
const editButtons = document.querySelectorAll(".fa-edit"); // Semua tombol edit
const editProductPopup = document.getElementById("editProductPopup");
const editProductOverlay = document.getElementById("editProductOverlay");
const closeEditProductBtn = document.getElementById("closeEditProduct");

// Element untuk pop up menghapus
const deleteBtn = document.querySelector(".fa-trash");
const deleteOverlay = document.getElementById("deleteOverlay");
const deletePopup = document.getElementById("deletePopup");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");

// Fungsi Membuka Popup
function openPopup(popup, overlay) {
  popup.style.display = "block";
  overlay.style.display = "block";
}

// Fungsi Menutup Popup
function closePopup(popup, overlay) {
  popup.style.display = "none";
  overlay.style.display = "none";
}

// Membuka popup
deleteBtn.addEventListener("click", () => {
  deleteOverlay.style.display = "block";
  deletePopup.style.display = "block";
});

// Menutup popup (tanpa menghapus)
cancelDeleteBtn.addEventListener("click", () => {
  deleteOverlay.style.display = "none";
  deletePopup.style.display = "none";
});

// Konfirmasi penghapusan
confirmDeleteBtn.addEventListener("click", () => {
  alert("Item deleted!");
  // Tambahkan logika penghapusan di sini
  deleteOverlay.style.display = "none";
  deletePopup.style.display = "none";
});

// Menutup popup saat overlay diklik
deleteOverlay.addEventListener("click", () => {
  deleteOverlay.style.display = "none";
  deletePopup.style.display = "none";
});

// Event Listener untuk Add Product
openAddProductBtn.addEventListener("click", () =>
  openPopup(addProductPopup, addProductOverlay)
);
closeAddProductBtn.addEventListener("click", () =>
  closePopup(addProductPopup, addProductOverlay)
);
addProductOverlay.addEventListener("click", () =>
  closePopup(addProductPopup, addProductOverlay)
);

// Event Listener untuk Edit Product
editButtons.forEach((button) => {
  button.addEventListener("click", () =>
    openPopup(editProductPopup, editProductOverlay)
  );
});

// Event listener untuk hapus product
const deleteButtons = document.querySelectorAll(".fa-trash");
deleteButtons.forEach((button) => {
  button.addEventListener("click", () => openPopup(deletePopup, deleteOverlay));
});

closeEditProductBtn.addEventListener("click", () =>
  closePopup(editProductPopup, editProductOverlay)
);
editProductOverlay.addEventListener("click", () =>
  closePopup(editProductPopup, editProductOverlay)
);

const plusButtons = document.querySelectorAll(".plus");
const minusButtons = document.querySelectorAll(".minus");
const numDisplays = document.querySelectorAll(".num");

// Iterasi setiap baris (plus, minus, num)
plusButtons.forEach((plusBtn, index) => {
  let a = 0; // Set nilai awal untuk setiap baris

  plusBtn.addEventListener("click", () => {
    a++;
    numDisplays[index].innerText = a < 10 ? "0" + a : a; // Format dua digit
    console.log(`Baris ${index + 1}: ${a}`);
  });

  minusButtons[index].addEventListener("click", () => {
    if (a > 0) {
      a--;
      numDisplays[index].innerText = a < 10 ? "0" + a : a;
      console.log(`Baris ${index + 1}: ${a}`);
    }
  });
});

// Create Product
document.getElementById("productForm").addEventListener("submit", (e) => {
  e.preventDefault();

  const formData = new FormData(document.getElementById("productForm"));
  formData.append("action", "create");

  fetch("api.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.message) {
        alert(data.message); // Notifikasi sukses
        document.getElementById("productForm").reset(); // Reset form
        closePopup(addProductPopup, addProductOverlay); // Tutup modal
        fetchProducts(); // Refresh product list
      } else if (data.error) {
        alert(`Error: ${data.error}`); // Notifikasi error
      }
    })
    .catch((error) => {
      alert(`Failed to add product: ${error.message}`);
    });
});

// Fetch and Display Products
function fetchProducts() {
  fetch("api.php")
    .then((response) => response.json())
    .then((data) => {
      const tbody = document.querySelector("table tbody");
      tbody.innerHTML = "";

      data.forEach((product) => {
        tbody.innerHTML += `
                    <tr>
                        <td><img src="${product.image}" height="50"></td>
                        <td>${product.name}</td>
                        <td>${product.category}</td>
                        <td>${product.price}</td>
                        <td>${product.piece}</td> <!-- Tampilkan piece -->
                        <td>${product.sizes}</td>
                        <td class="actions">
                            <i class="fas fa-edit" onclick="editProduct(${product.id})"></i>
                            <i class="fas fa-trash" onclick="deleteProduct(${product.id})"></i>
                        </td>
                    </tr>
                `;
      });
    });
}

// Delete Product
function deleteProduct(id) {
  const formData = new FormData();
  formData.append("action", "delete");
  formData.append("id", id);

  fetch("api.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      alert(data.message || data.error);
      fetchProducts(); // Refresh product list
    });
}

// Initialize
fetchProducts();
// Fungsi untuk edit product
document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".fa-edit");
  const editProductOverlay = document.getElementById("editProductOverlay");
  const editProductPopup = document.getElementById("editProductPopup");
  const closeEditProduct = document.getElementById("closeEditProduct");
  const editProductForm = document.getElementById("editProductForm");
  const editImage = document.getElementById("editImage");
  const previewImage = document.getElementById("previewImage");

  // Event untuk tombol edit
  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");
      const productId = row.dataset.id;
      const productData = {
        id: productId,
        name: row.cells[1].textContent,
        category: row.cells[2].textContent,
        price: parseFloat(row.cells[3].textContent.replace("$", "")),
        piece: parseInt(row.cells[4].textContent),
        sizes: row.cells[5].textContent,
        image: row.cells[0].querySelector("img").src,
      };

      fillEditForm(productData);
      editProductOverlay.style.display = "block";
      editProductPopup.style.display = "block";
    });
  });

  // Fungsi untuk mengisi form edit
  function fillEditForm(data) {
    document.getElementById("editProductId").value = data.id;
    document.getElementById("editProductName").value = data.name;
    document.getElementById("editCategory").value = data.category;
    document.getElementById("editPrice").value = data.price;
    document.getElementById("editPiece").value = data.piece;
    document.getElementById("editSizes").value = data.sizes;

    if (data.image) {
      previewImage.src = data.image;
      previewImage.style.display = "block";
      document.querySelector(".form-left .upload-icon").style.display = "none";
    }
  }

  // Event untuk preview image
  editImage.addEventListener("change", function (e) {
    if (e.target.files && e.target.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImage.src = e.target.result;
        previewImage.style.display = "block";
        document.querySelector(".form-left .upload-icon").style.display =
          "none";
      };
      reader.readAsDataURL(e.target.files[0]);
    }
  });

  // Event untuk close button
  closeEditProduct.addEventListener("click", function () {
    editProductOverlay.style.display = "none";
    editProductPopup.style.display = "none";
    editProductForm.reset();
    previewImage.style.display = "none";
    document.querySelector(".form-left .upload-icon").style.display = "block";
  });

  // Event untuk submit form edit
  editProductForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append("action", "update");

    try {
      const response = await fetch("api.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || "Failed to update product");
      }

      const data = await response.json();
      alert("Product updated successfully!");

      // Refresh table atau update row
      location.reload();

      // Tutup popup
      editProductOverlay.style.display = "none";
      editProductPopup.style.display = "none";
    } catch (error) {
      alert(error.message);
      console.error("Error:", error);
    }
  });
});
