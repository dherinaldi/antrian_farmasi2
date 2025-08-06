<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring API VClaim BPJS</title>
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
        <h2 class="text-center mb-4">MONITORING API VCLAIM BPJS KESEHATAN</h2>
        <div class="row g-4">

            <!-- Card Template -->
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card api-card p-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Get Peserta</h5>
                        <p id="status-peserta"><i class="bi bi-clock-fill text-warning"></i> Loading...</p>
                        <p id="response-time-peserta"></p>
                        <p class="text-muted" id="last-update-peserta"></p>
                    </div>

                </div>
            </div>

            <!-- Ulangi dan ubah untuk setiap API berikut -->
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card api-card p-3 h-100">
                    <!-- Card Get Rujukan -->
                    <div class="card-body">
                        <h5 class="card-title">Get Rujukan</h5>
                        <p id="status-rujukan">Loading...</p>
                        <p id="response-time-rujukan"></p>
                        <p class="text-muted" id="last-update-rujukan"></p>
                    </div>
                </div>
            </div>

            <!-- Tambahkan kartu lain seperti Get SEP, Get Diagnosa, Get Surat Kontrol, dst sesuai gambar -->
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        const urls = {
            peserta: "../bpjs/controller.php?param=nik&noka=3507240510770000",
            rujukan: "../bpjs/controller.php?param=rujukan",
        };

        function fetchAndDisplay(id, url) {
            const start = performance.now();
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data, textStatus, xhr) {
                    const httpCode = xhr.status; // 200, 201, dll
                    console.log("HTTP Code:", httpCode);

                    const end = performance.now();
                    const time = Math.round(end - start);
                    const meta = data.metaData || data.metadata || {};
                    const code = meta.code;
                    const status = (httpCode === 200 || ["200", "201", "1"].includes(code)) ?
                        'CONNECTED' : 'ERROR';
                    const icon = status === 'CONNECTED' ? 'bi-check-circle-fill text-success' :
                        'bi-x-circle-fill text-danger';

                    console.log("HTTP Code:", httpCode, "| API Code:", code);

                    $(`#status-${id}`).html(`<i class="bi ${icon}"></i> Status: ${status}`);
                    $(`#response-time-${id}`).text(`Response Time: ${time} ms`);

                    const now = new Date();
                    $(`#last-update-${id}`).text("Last Update: " + now.toLocaleString('id-ID'));
                },
                error: function() {
                    $(`#status-${id}`).html(
                        `<i class="bi bi-x-circle-fill text-danger"></i> Status: ERROR`);
                    $(`#response-time-${id}`).text(`Response Time: -`);
                    $(`#last-update-${id}`).text(`Last Update: Gagal mengambil data`);
                }
            });
        }

        // Panggil untuk kedua URL
        fetchAndDisplay("peserta", urls.peserta);
        fetchAndDisplay("rujukan", urls.rujukan);

    });
    </script>


</body>

</html>