// Ambil context untuk canvas
const ctx = document.getElementById('salesChart').getContext('2d');

// Data untuk grafik
const data = {
  labels: ['5k', '10k', '15k', '20k', '25k', '30k', '35k', '40k', '45k', '50k'],
  datasets: [{
    label: 'Sales Details',
    data: [12, 19, 3, 5, 2, 3, 5, 7, 9, 6],
    borderColor: '#4e73df',
    fill: false,
    tension: 0.1
  }]
};

// Opsi grafik
const options = {
  responsive: true,
  scales: {
    y: {
      beginAtZero: true
    }
  }
};

// Membuat grafik
const salesChart = new Chart(ctx, {
  type: 'line',
  data: data,
  options: options
});
