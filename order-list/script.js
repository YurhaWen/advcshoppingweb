// Fungsi untuk memfilter tabel berdasarkan input
function filterTable() {
  const idFilter = document.getElementById("filter-id").value.toLowerCase();
  const nameFilter = document.getElementById("filter-name").value.toLowerCase();
  const addressFilter = document
    .getElementById("filter-address")
    .value.toLowerCase();
  const dateFilter = document.getElementById("filter-date").value;
  const typeFilter = document.getElementById("filter-type").value.toLowerCase();
  const statusFilter = document
    .getElementById("filter-status")
    .value.toLowerCase();

  const table = document.getElementById("order-table");
  const rows = table.getElementsByTagName("tr");

  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName("td");
    const id = cells[0].textContent.trim().toLowerCase();
    const name = cells[1].textContent.trim().toLowerCase();
    const address = cells[2].textContent.trim().toLowerCase();
    const date = cells[3].textContent.trim();
    const type = cells[4].textContent.trim().toLowerCase();
    const status = cells[5].textContent.trim().toLowerCase();

    let isVisible = true;

    // Filter berdasarkan ID
    if (idFilter && !id.includes(idFilter)) {
      isVisible = false;
    }

    // Filter berdasarkan Name
    if (nameFilter && !name.includes(nameFilter)) {
      isVisible = false;
    }

    // Filter berdasarkan Address
    if (addressFilter && !address.includes(addressFilter)) {
      isVisible = false;
    }

    // Filter berdasarkan Tanggal
    if (dateFilter && date !== dateFilter) {
      isVisible = false;
    }

    // Filter berdasarkan Type
    if (typeFilter && !type.includes(typeFilter)) {
      isVisible = false;
    }

    // Filter berdasarkan Status
    if (statusFilter && !status.includes(statusFilter)) {
      isVisible = false;
    }

    // Tampilkan atau sembunyikan baris
    rows[i].style.display = isVisible ? "" : "none";
  }
}

// Fungsi untuk reset filter
function resetFilters() {
  document.getElementById("filter-id").value = "";
  document.getElementById("filter-name").value = "";
  document.getElementById("filter-address").value = "";
  document.getElementById("filter-date").value = "";
  document.getElementById("filter-type").value = "";
  document.getElementById("filter-status").value = "";
  filterTable(); // Refresh tabel setelah reset
}

// Event listeners untuk masing-masing filter
document.getElementById("filter-id").addEventListener("input", filterTable);
document.getElementById("filter-name").addEventListener("input", filterTable);
document
  .getElementById("filter-address")
  .addEventListener("input", filterTable);
document.getElementById("filter-date").addEventListener("input", filterTable);
document.getElementById("filter-type").addEventListener("change", filterTable);
document
  .getElementById("filter-status")
  .addEventListener("change", filterTable);

const actionButtons = document.querySelectorAll(".action-btn");

actionButtons.forEach((button) => {
  button.addEventListener("click", function () {
    const newStatus = this.getAttribute("data-status");

    const row = this.closest("tr");

    const statusCell = row.querySelector(".status");
    statusCell.textContent = newStatus;
  });
});
