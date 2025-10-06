<!DOCTYPE html>
<html>

<head>
    <title>Data Menu Makanan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th,
        td {
            padding: 8px 14px;
            border: 1px solid #333;
            text-align: center;
        }

        th {
            background: orange;
            color: white;
        }

        h2 {
            text-align: center;
        }

        .btn-tambah {
            display: inline-block;
            background-color: orange;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-left: 10%;
        }

        .btn-tambah:hover {
            background-color: pink;
        }

        .btn-edit,
        .btn-hapus {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-edit {
            background-color: blue;
            color: white;
        }

        .btn-edit:hover {
            background-color: darkblue;
        }

        .btn-hapus {
            background-color: red;
            color: white;
        }

        .btn-hapus:hover {
            background-color: darkred;
        }

        /* Modal Background */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 70px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Gambar dalam Modal */
        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
            border-radius: 10px;
            box-shadow: 0 0 20px #000;
        }

        /* Tombol Close */
        .close {
            position: absolute;
            top: 30px;
            right: 50px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: red;
        }
    </style>
</head>

<body>
    <h2>Data Menu Makanan</h2>

    <a href="input_menu.php" class="btn-tambah">+ Tambah Data</a>

    <table>
        <tr>
            <th>NO</th>
            <th>ID MENU</th>
            <th>NAMA MENU</th>
            <th>HARGA</th>
            <th>KATEGORI</th>
            <th>GAMBAR</th>
            <th>Aksi</th>
        </tr>

        <?php
        include 'koneksi.php';
        $no = 1;
        $ambildata = mysqli_query($koneksi, "SELECT * FROM menu");
        while ($tampil = mysqli_fetch_array($ambildata)) {
        ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $tampil['id_menu']; ?></td>
                <td><?= $tampil['nama_menu']; ?></td>
                <td><?= $tampil['harga']; ?></td>
                <td><?= $tampil['kategori']; ?></td>
                <td>
                    <?php if (!empty($tampil['gambar'])) { ?>
                        <img src="uploads/<?= $tampil['gambar']; ?>" width="100" style="cursor:pointer" onclick="showModal(this)">
                    <?php } else { ?>
                        <span style="color:gray;">(tidak ada gambar)</span>
                    <?php } ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $tampil['id_menu']; ?>" class="btn-edit">Edit</a>
                    <a href="hapus.php?id=<?= $tampil['id_menu']; ?>" class="btn-hapus" onclick="return confirm('Yakin mau hapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php
            $no++;
        }
        ?>
    </table>

    <!-- Modal Gambar -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <script>
        function showModal(imgElement) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("modalImg");

            modal.style.display = "block";
            modalImg.src = imgElement.src;
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        // Klik di luar gambar menutup modal
        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
