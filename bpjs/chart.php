<label for="intervalSelect">Interval (detik):</label>
<select id="intervalSelect">
    <option value="1000">1 detik</option>
    <option value="5000" selected>5 detik</option>
    <option value="10000">10 detik</option>
    <option value="30000">30 detik</option>
    <option value="300000">5 menit</option>
    <option value="900000">15 menit</option>
</select>

<div id="loading-indicator" class="text-secondary d-flex align-items-center gap-2" style="display:none;">
    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
    <span>Mengatur ulang interval polling...</span>
</div>


<div id="chart-container" class="my-5"></div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
Highcharts.setOptions({
    time: {
        timezone: 'Asia/Jakarta',
        useUTC: false
    },
    lang: {
        months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ],
        weekdays: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
    }
});

let chart = Highcharts.chart('chart-container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Monitoring Chart Bridging BPJS (VCLAIM dan ANTROL)'
    },
    xAxis: {
        type: 'datetime',
        labels: {
            format: '{value:%H:%M:%S}'
        },
        title: {
            text: 'Waktu (WIB)'
        }
    },
    yAxis: {
        title: {
            text: 'Response Time (ms)'
        }
    },
    series: [{
            name: 'peserta',
            data: []
        },
        {
            name: 'rujukan',
            data: []
        },
        {
            name: 'diagnosa',
            data: []
        },
        {
            name: 'surkon',
            data: []
        },

    ]
});


function fetchResponse(endpoint, url, seriesIndex) {
    const start = performance.now();
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function(data, textStatus, xhr) {
            const end = performance.now();
            const time = Math.round(end - start);
            const x = (new Date()).getTime(); // timestamp
            chart.series[seriesIndex].addPoint([x, time], true, chart.series[seriesIndex].data.length >=
                20);
        },
        error: function() {
            const x = (new Date()).getTime();
            chart.series[seriesIndex].addPoint([x, null], true, chart.series[seriesIndex].data.length >=
                20);
        }
    });
}

// Update tiap 5 detik


let intervalId;
let currentInterval = 5000; // default 5 detik

function startInterval() {
    if (intervalId) clearInterval(intervalId); // stop yang lama
    intervalId = setInterval(() => {
        fetchResponse('peserta', "../bpjs/controller.php?param=nik&noka=6201052510750001", 0);
        fetchResponse('rujukan', "../bpjs/controller.php?param=rujukan", 1);       
    }, currentInterval);
}

// Jalankan pertama kali
startInterval();

// Ganti interval saat dropdown berubah
document.getElementById('intervalSelect').addEventListener('change', function() {
    currentInterval = parseInt(this.value);

    // Tampilkan loading
    document.getElementById('loading-indicator').style.display = 'block';

    // Jalankan interval baru
    startInterval();

    // Sembunyikan loading setelah 1 detik
    setTimeout(() => {
        document.getElementById('loading-indicator').style.display = 'none';
    }, 1000);
});
</script>