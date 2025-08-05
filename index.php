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

    #hospital-name {
        font-size: 36px;
        text-align: center;
        padding-top: 20px;
    }

    #global-timer {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #004d40;
    }

    #digital-clock {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #004d40;
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
        margin-bottom: 10px;
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
        font-weight: bold;
    }
    </style>
</head>

<body>

    <h1 id="hospital-name">Display Antrian Farmasi RSUD Lawang</h1>
    <div id="digital-clock"></div>

    <div class="container">
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

    <script src="assets/clock.js"></script>

    <script>
    function sensorNama(nama) {
        if (nama.length <= 2) return nama;
        const awal = nama.slice(0, 2);
        const akhir = nama.slice(-1);
        const tengah = '*'.repeat(nama.length - 3);
        return awal + tengah + akhir;
    }

    function sensorNamaLengkap(nama) {
        return nama
            .split(' ')
            .map(kata => {
                if (kata.length <= 1) return kata;
                return kata[0] + '*'.repeat(kata.length - 1);
            })
            .join(' ');
    }

    //console.log(sensorNamaLengkap("DANIAR RINALDI")); // Output: D***** R******


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
                        `<div class="item status-belum ${isCito}">${item.NORM} ${sensorNamaLengkap(item.NAMA)} ${item.ASAL_RUANGAN}<br>${isRacikan} ${item.TANGGAL}</div>`;
                });

                data.dilayani.forEach(item => {
                    document.getElementById('dilayani').innerHTML +=
                        `<div class="item status-dilayani">${item.NORM} ${sensorNamaLengkap(item.NAMA)}<br>${item.TANGGAL}</div>`;
                });

                data.selesai.forEach(item => {
                    document.getElementById('selesai').innerHTML +=
                        `<div class="item status-selesai">${item.NORM} ${sensorNamaLengkap(item.NAMA)}<br>${item.TANGGAL}</div>`;
                });
            });
    }

    loadAntrian();
    setInterval(loadAntrian, 30000);
    </script>

</body>

</html>