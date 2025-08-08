<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Display Antrian Bergilir Poli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        background-color: #263238;
        color: white;
        font-family: 'Segoe UI', sans-serif;
    }

    .header {
        background-color: #880e4f;
        padding: 20px;
        color: white;
    }

    .content {
        background-color: #37474f;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
    }

    .poli-title {
        text-align: center;
        font-size: 32px;
        font-weight: bold;
        color: #ffeb3b;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .section-title {
        font-size: 22px;
        margin-top: 20px;
        border-bottom: 1px solid #90a4ae;
        padding-bottom: 5px;
    }

    .item {
        background-color: #102027;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .item .no {
        font-size: 28px;
        font-weight: bold;
        color: #ffeb3b;
    }

    .item .nama {
        font-size: 18px;
    }

    .item .info {
        font-size: 14px;
        color: #cfd8dc;
    }

    #listDipanggil,
    #listMenunggu,
    #listSudah {
        max-height: 200px;
        /* atur sesuai kebutuhan */
        overflow-y: auto;
        /* muncul scroll vertikal kalau konten panjang */
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background: #f9f9f9;
    }

    #listDipanggil {
        border-left: 4px solid #28a745;
        /* hijau */
    }

    #listMenunggu {
        border-left: 4px solid #ffc107;
        /* kuning */
    }

    #listSudah {
        border-left: 4px solid #17a2b8;
        /* biru */
    }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="container-fluid header d-flex justify-content-between align-items-center">
        <h2>DISPLAY ANTRIAN </h2>
        <div><span id="tanggal"></span> - <span id="jam"></span></div>
    </div>

    <!-- Konten Poli Aktif -->
    <div class="container">
        <div class="content">
            <div class="poli-title" id="poliTitle">POLI ANAK</div>

            <div class="poli-columns">
                <div class="poli-column">
                    <div class="section-title">Admisi</div>
                    <div id="listDipanggil" class="scroll-list"></div>
                </div>

                <div class="poli-column">
                    <div class="section-title">Menunggu (Layanan Poli)</div>
                    <div id="listMenunggu" class="scroll-list"></div>
                </div>

                <div class="poli-column">
                    <div class="section-title">Sudah</div>
                    <div id="listSudah" class="scroll-list"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let poliData = {};
    let poliList = [];
    let currentIndex = 0;

    function updateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        $('#tanggal').text(now.toLocaleDateString('id-ID', options));
        $('#jam').text(now.toLocaleTimeString('id-ID'));
    }

    function fetchData() {
        $.getJSON('get_antrian_display.php', function(data) {
            poliData = data;
            poliList = Object.keys(data);
            showPoli(); // tampilkan pertama kali
        });
    }

    function showPoli() {
        if (poliList.length === 0) return;

        const poli = poliList[currentIndex];
        const content = poliData[poli];
        $('#poliTitle').text(poli);
        console.log(content);

        $('#listDipanggil').html(renderList(content.dipanggil));
        $('#listMenunggu').html(renderList(content.menunggu));
        $('#listSudah').html(renderList(content.sudah));

        // Naikkan indeks poli
        currentIndex = (currentIndex + 1) % poliList.length;
    }

    function renderList(list) {
        if (!list || list.length === 0) {
            return '<div class="text-center text-light">Tidak ada data</div>';
        }

        return list.map(item => `
        <div class="item">
          <div class="no">#${item.no_antrian}</div>
          <div class="nama">${item.nama_pasien}</div>
          <div class="info">${item.no_rm} - ${item.waktu_kunjungan}</div>
        </div>
      `).join('');
    }

    setInterval(updateTime, 1000);
    setInterval(showPoli, 5000); // Ganti tampilan setiap 5 detik
    updateTime();
    fetchData();
    setInterval(fetchData, 10000); // Refresh data dari server setiap 10 detik

    function autoScroll(elementId) {
        const el = document.getElementById(elementId);
        let scrollDown = true; // arah awal

        setInterval(() => {
            if (scrollDown) {
                el.scrollTop += 1; // scroll ke bawah
                if (el.scrollTop + el.clientHeight >= el.scrollHeight) {
                    scrollDown = false; // sampai bawah → balik arah
                }
            } else {
                el.scrollTop -= 1; // scroll ke atas
                if (el.scrollTop <= 0) {
                    scrollDown = true; // sampai atas → balik arah
                }
            }
        }, 30); // kecepatan scroll (ms)
    }

    // Jalankan auto scroll untuk semua list
    autoScroll("listDipanggil");
    autoScroll("listMenunggu");
    autoScroll("listSudah");
    </script>
</body>

</html>