<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Live Antrian Farmasi</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #e0f2f1;
        margin: 0;
        padding: 0;
    }

    .container {
        display: flex;
        justify-content: space-around;
        padding: 20px;
    }

    .column {
        background: #00695c;
        padding: 15px;
        border-radius: 15px;
        width: 30%;
        color: white;
        min-height: 80vh;
    }

    .column h2 {
        text-align: center;
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 24px;
    }

    .item {
        background: #26a69a;
        margin: 10px 0;
        padding: 10px;
        border-radius: 10px;
        font-size: 18px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .status-belum {
        background-color: #ff7043;
    }

    .status-dilayani {
        background-color: #ffee58;
        color: #000;
    }

    .status-selesai {
        background-color: #66bb6a;
    }

    .cito {
        color: red;
        /* Warna tulisan merah */
        font-weight: bold;
    }

    #hospital-name {
        font-size: 36px;
        margin-bottom: 20px;
    }
    </style>
</head>

<body>

    <div class="container">
        <h1 id="hospital-name">Display Antrian Farmasi RSUD Lawang</h1>
        <div class="column" id="belum_diterima">
            <h2>Belum Diterima</h2>
        </div>
        <div class="column" id="dilayani">
            <h2>Dilayani</h2>
        </div>
        <div class="column" id="selesai">
            <h2>Selesai</h2>
        </div>
    </div>

    <script>
    function sensorNama(nama) {
        if (nama.length <= 2) return nama; // Tidak cukup panjang untuk disensor
        const awal = nama.slice(0, 2); // Ambil 2 huruf pertama
        const akhir = nama.slice(-1); // Ambil 1 huruf terakhir
        const tengah = '*'.repeat(nama.length - 3); // Bintang sesuai panjang
        return awal + tengah + akhir;
    }

    function loadAntrian() {
        fetch('get_antrian.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('belum_diterima').innerHTML = '<h2>Belum Diterima</h2>';
                document.getElementById('dilayani').innerHTML = '<h2>Dilayani</h2>';
                document.getElementById('selesai').innerHTML = '<h2>Selesai</h2>';

                data.belum_diterima.forEach(item => {
                    const isCito = item.CITO === "1" ? 'cito animate__animated animate__bounce' : '';
                    const isRacikan = item.RACIKAN === "1" ? "R" : "NR";

                    document.getElementById('belum_diterima').innerHTML +=
                        `<div class="item status-belum ${isCito}">${item.NORM} ${item.NAMA} ${item.ASAL_RUANGAN}<br>${isRacikan} ${item.TANGGAL}</div>`;
                });

                data.dilayani.forEach(item => {
                    document.getElementById('dilayani').innerHTML +=
                        `<div class="item status-dilayani">${item.NORM} ${item.NAMA}<br>${item.TANGGAL}</div>`;
                });

                data.selesai.forEach(item => {
                    document.getElementById('selesai').innerHTML +=
                        `<div class="item status-selesai">${item.NORM} ${item.NAMA}<br>${item.TANGGAL}</div>`;
                });
            });
    }

    // Jalankan load setiap 15 detik
    setInterval(loadAntrian, 15000);

    // Pertama kali load
    loadAntrian();
    </script>

</body>

</html>