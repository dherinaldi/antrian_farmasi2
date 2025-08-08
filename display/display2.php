<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Display Antrian Bergilir Poli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/style.css" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header class="header container-fluid d-flex justify-content-between align-items-center">
        <h2 class="m-0">DISPLAY ANTRIAN</h2>
        <div><span id="tanggal"></span> - <span id="jam"></span></div>
    </header>
   <!--  <input type="text" value="" id="inp_poli"> -->

    <!-- Konten -->
    <main class="container mt-4">
        <div class="content p-3 rounded">
            <div class="poli-title mb-3" id="poliTitle">POLI ANAK</div>
            <div class="row g-3">
                <div class="col-lg-4 col-md-6 col-12">
                    <h5 class="section-title">Admisi</h5>
                    <div id="listDipanggil" class="scroll-list border-start border-success"></div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <h5 class="section-title">Dilayani (Layanan Poli)</h5>
                    <div id="listMenunggu" class="scroll-list border-start border-warning"></div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <h5 class="section-title">Sudah</h5>
                    <div id="listSudah" class="scroll-list border-start border-info"></div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/script.js"></script>
</body>

</html>