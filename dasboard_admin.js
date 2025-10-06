document.addEventListener("DOMContentLoaded", function () {
  /* === CHART === */
  var options = {
    series: [{
      name: 'Series 1',
      data: [31, 40, 28, 51, 42, 109, 100]
    }, {
      name: 'Series 2',
      data: [11, 32, 45, 32, 34, 52, 41]
    }],
    chart: {
      height: 350,
      type: 'area',
      toolbar: { show: false },
      zoom: { enabled: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth' },
    xaxis: {
      type: 'datetime',
      categories: [
        "2018-09-19T00:00:00.000Z",
        "2018-09-19T01:30:00.000Z",
        "2018-09-19T02:30:00.000Z",
        "2018-09-19T03:30:00.000Z",
        "2018-09-19T04:30:00.000Z",
        "2018-09-19T05:30:00.000Z",
        "2018-09-19T06:30:00.000Z"
      ]
    },
    tooltip: { x: { format: 'dd/MM/yy HH:mm' } }
  };

  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();

  /* === CLOCK (WAKTU REALTIME DI ATAS DASHBOARD) === */
  function updateClock() {
    const now = new Date();
    const jam = now.getHours().toString().padStart(2, '0');
    const menit = now.getMinutes().toString().padStart(2, '0');
    const detik = now.getSeconds().toString().padStart(2, '0');
    const clock = document.getElementById('clock');
    if (clock) clock.textContent = `${jam}:${menit}:${detik}`;
  }
  setInterval(updateClock, 1000);
  updateClock();

  /* === DURASI ONLINE PER USER === */
  function updateDurasi() {
    const now = Math.floor(Date.now() / 1000);
    document.querySelectorAll('.durasi').forEach(el => {
      const loginTime = parseInt(el.getAttribute('data-login'));
      if (!loginTime) return;
      const diff = now - loginTime;
      const jam = Math.floor(diff / 3600);
      const menit = Math.floor((diff % 3600) / 60);
      const detik = diff % 60;
      el.textContent = `${jam.toString().padStart(2, '0')}:${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;
    });
  }
  setInterval(updateDurasi, 1000);
  updateDurasi();
});
