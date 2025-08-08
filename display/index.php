<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Display Antrian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .api-card {
        border-left: 5px solid #28a745;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .api-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #28a745;
    }

    .status {
        font-weight: 500;
    }

    .status-icon {
        color: #28a745;
        margin-right: 5px;
    }
    </style>
</head>

<body>
    <div class="container py-5">
        <h2 class="text-center mb-4">LIST DISPLAY ANTRIAN ADMISI DAN POLI</h2>
        <div class="row g-4">

            <!-- Card Template -->
             <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card api-card p-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Display Poli 1</h5>
                        <a href="display.php" class="btn btn-sm btn-success" target="_blank">Show Datas</a>                        
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card api-card p-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Display Poli 2</h5>
                        <a href="display2.php" class="btn btn-sm btn-success" target="_blank">Show Datas</a>
                    </div>
                </div>
            </div>
            
            <!-- Tambahkan kartu lain seperti Get SEP, Get Diagnosa, Get Surat Kontrol, dst sesuai gambar -->
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   

</body>

</html>