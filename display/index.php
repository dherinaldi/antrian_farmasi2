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

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card api-card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-3">Display Poli</h5>

                        <div class="mb-3 flex-grow-1">
                            <label for="poli" class="form-label">Multiselect (bisa lebih dari 1) </label>
                            <select class="form-select" name="poli[]" multiple size="5" id="poli">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="intervalSelect" class="form-label">Interval Pergantian (detik)</label>
                            <select id="intervalSelect" class="form-select">
                                <option value="5000">5 Detik</option>
                                <option value="10000">10 Detik</option>
                                <option value="15000" selected>15 Detik</option>
                                <option value="30000">30 Detik</option>
                                <option value="60000">60 Detik</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <a href="display.php" class="btn btn-sm btn-success mt-auto" target="_blank"
                                id="tampilBtn1">
                                Display 1
                            </a>
                            <a href="display2.php" class="btn btn-sm btn-success mt-auto" target="_blank"
                                id="tampilBtn2">
                                Display 2
                            </a>
                        </div>
                        <div class="mb-3">

                        </div>
                    </div>
                </div>
            </div>


            <!-- Tambahkan kartu lain seperti Get SEP, Get Diagnosa, Get Surat Kontrol, dst sesuai gambar -->
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
    function loadDropdown(selector, jenis, label) {
        $.ajax({
            url: 'controller.php',
            type: 'GET',
            data: {
                jenis: jenis,
                select2: 0
            },
            dataType: 'json',
            success: function(data) {
                console.log(data);
                let $select = $(selector);
                $select.empty().append(`<option value="">${label}</option>`);
                data.forEach(function(item) {
                    $select.append(`<option value="${item.ID}">${item.DESKRIPSI}</option>`);
                });
            },
            error: function(xhr, status, error) {
                console.error(`Gagal load ${label.toLowerCase()}:`, error);
            }
        });
    }

    loadDropdown('#poli', 'poli', ' -- SEMUA Poli --');


    document.getElementById("tampilBtn1").addEventListener("click", function(e) {
        e.preventDefault(); // biar href default nggak jalan
        const selected = Array.from(document.getElementById("poli").selectedOptions)
            .map(option => option.value);
        const interval = document.getElementById("intervalSelect").value;

        const url = "display.php?poli=" + encodeURIComponent(selected.join(",")) + "&interval=" + interval;
        window.open(url, "_blank");
    });

    document.getElementById("tampilBtn2").addEventListener("click", function(e) {
        e.preventDefault();
        const selected = Array.from(document.getElementById("poli").selectedOptions)
            .map(option => option.value);
        const interval = document.getElementById("intervalSelect").value;

        const url = "display2.php?poli=" + encodeURIComponent(selected.join(",")) + "&interval=" + interval;
        window.open(url, "_blank");
    });
    </script>
    </script>


</body>

</html>