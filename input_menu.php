<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Menu Makanan & Minuman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.7);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(45deg, #fa5502, #fc9803);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }

        .btn-submit {
            background-color: #fc9803;
            border-color: #fc9803;
            color: white;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .btn-submit:hover {
            background-color: #fc7303;
            border-color: #fc7303;
        }
    </style>
</head>

<body>
    <div class="overlay">
        <div class="container">
            <!-- Form Input Menu -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header text-center">
                            <h2><i class="fas fa-utensils me-2"></i>Input Menu Makanan/Minuman</h2>
                            <p class="mb-0">Tambahkan menu makanan dan minuman Anda</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="proses_menu.php" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_menu" class="form-label">Nama Menu</label>
                                        <input type="text" class="form-control" id="nama_menu" name="nama_menu" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <select class="form-select" id="kategori" name="kategori" required>
                                            <option value="" selected disabled>Pilih Kategori</option>
                                            <option value="Makanan">Makanan</option>
                                            <option value="Minuman">Minuman</option>
                                            <option value="Snack">Snack</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="harga" class="form-label">Harga (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="harga" name="harga" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gambar" class="form-label">Gambar</label>
                                        <input type="file" class="form-control" id="gambar" name="gambar" required>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-submit btn-lg">
                                        <i class="fas fa-save me-2"></i>Simpan Menu
                                    </button>
                                </div>
                                <br>
                                <div class="d-grid">
                                    <a type="submit" href="data_menu.php" class="btn btn-submit btn-lg">
                                        Kembali
                                </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
