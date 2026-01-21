import './bootstrap';
import Alpine from 'alpinejs'
import Chart from 'chart.js/auto'
window.Alpine = Alpine
window.Chart = Chart
Alpine.start()

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('stokBar')
  if (!el) return

  const labels = JSON.parse(el.dataset.labels || '[]')
  const dataIn = JSON.parse(el.dataset.in || '[]')
  const dataOut = JSON.parse(el.dataset.out || '[]')

  const ctx = el.getContext('2d')
  if (window.__stokChart) window.__stokChart.destroy()

  window.__stokChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        { label: 'Penerimaan (Qty)', data: dataIn },
        { label: 'Pengeluaran (Qty)', data: dataOut }
      ]
    },
    options: { responsive: true, maintainAspectRatio: false }
  })
})

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('stokPie')
  if (!el) return

  const labels = JSON.parse(el.dataset.labels || '[]')
  const values = JSON.parse(el.dataset.values || '[]')

  const ctx = el.getContext('2d')
  if (window.__stokPieChart) window.__stokPieChart.destroy()

  window.__stokPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels,
      datasets: [{ data: values }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: (tt) => `${tt.label}: ${Number(tt.raw || 0).toLocaleString()}`
          }
        }
      }
    }
  })
})
