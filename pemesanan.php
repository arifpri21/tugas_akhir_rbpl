<?php
// Memulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  // Pengguna belum login, arahkan ke halaman login
  header("Location: process_login.php");
  exit;
}

// Mengambil data pengguna dari database
include 'koneksi.php';

// Mengambil username dari session
$username = $_SESSION['username'];

// Query untuk mengambil data pengguna berdasarkan username
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

// Memeriksa apakah data pengguna ditemukan
if (mysqli_num_rows($result) > 0) {
  // Mengisi variabel $row dengan data pengguna
  $row = mysqli_fetch_assoc($result);
}

// Mengambil nilai dari parameter query 'produk_id'
$produk_id = isset($_GET['produk_id']) ? $_GET['produk_id'] : '';

// Query untuk mengambil data produk berdasarkan id_produk
$sql_produk = "SELECT * FROM produk WHERE id_produk = '$produk_id'";
$result_produk = mysqli_query($conn, $sql_produk);

// Memeriksa apakah data produk ditemukan
if (mysqli_num_rows($result_produk) > 0) {
  $row_produk = mysqli_fetch_assoc($result_produk);
} else {
  // Produk tidak ditemukan, arahkan ke halaman sebelumnya atau tampilkan pesan
  echo "Produk tidak ditemukan.";
  exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Escape input untuk mencegah SQL injection
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
  $jumlah = (int)$_POST['jumlah'];
  $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
  $desain = $_FILES['desain']['name'];
  $tanggal = date('Y-m-d');
  $harga_satuan = $row_produk['harga']; // Harga satuan dari database
  $total_harga = $jumlah * $harga_satuan; // Total harga

  // Upload gambar desain
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["desain"]["name"]);
  move_uploaded_file($_FILES["desain"]["tmp_name"], $target_file);

  // Insert ke tabel pemesanan
  $sql_pemesanan = "INSERT INTO pesanan (username, id_produk, nama, alamat, jumlah, no_telp, desain, harga, total_harga, tanggal) 
                    VALUES ('$username', '$produk_id', '$nama', '$alamat', $jumlah, '$no_telp', '$desain', $harga_satuan, $total_harga, '$tanggal')";

  if (mysqli_query($conn, $sql_pemesanan)) {
    // Redirect ke halaman pembayaran
    header("Location: dashboard.php");
    exit;
  } else {
    echo "Error: " . $sql_pemesanan . "<br>" . mysqli_error($conn);
  }
}

// Menutup koneksi ke database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Pemesanan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="pemesanan.css">
  <link href='creazy.co.png' rel='shortcut icon'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg nav-light bg-blue">
    <a class="navbar-brand" href="#"><img src="creazy.co.png" width="110" height="40" class="mr-2"></a>
    <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link nav-link-home" href="dashboard.php" style="display: flex; align-items: top;">
            <img src="home.png" width="20" height="20" style="margin-right: 5px;">
            Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-pesanan" href="pesanan.php" style="display: flex; align-items: top;">
            <img src="pesan.png" width="20" height="20" style="margin-right: 3px;">
            Pesanan
          </a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Cari" aria-label="Cari">
        <button type="button" class="btn btn-outline-light">Cari</button>
      </form>
    </div>
    <div class="ml-auto">
      <div class="dropdown">
        <a class="nav-link dropdown-toggle dropdown-username" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php if (!empty($row['profile_picture'])) : ?>
            <img src="<?php echo $row['profile_picture']; ?>" width="35" height="35" class="rounded-circle mr-2">
          <?php else : ?>
            <img src="gambar_profil.png" width="30" height="30" class="rounded-circle mr-2">
          <?php endif; ?>
          <?php echo $_SESSION['username']; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="edit_profil.php" style="display: flex; align-items: center;">
            <img src="edit_profil.png" width="15" height="15" style="vertical-align: middle; margin-right: 1px;">
            Edit Profil
          </a>
          <a class="dropdown-item" href="logout.php" style="display: flex; align-items: center;">
            <img src="logout.jpg" width="15" height="15" style="vertical-align: middle; margin-right: 3px;">
            Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <h2>Pemesanan Produk</h2>
    <form action="pemesanan.php?produk_id=<?php echo $produk_id; ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
      </div>
      <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
      </div>
      <div class="form-group">
        <label for="jumlah">Jumlah</label>
        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
      </div>
      <div class="form-group">
        <label for="no_telp">Nomor Telepon</label>
        <input type="text" class="form-control" id="no_telp" name="no_telp" required>
      </div>
      <div class="form-group">
        <label for="desain">Upload Desain</label>
        <input type="file" class="form-control-file" id="desain" name="desain" required>
      </div>
      <input type="hidden" name="harga_satuan" value="<?php echo $row_produk['harga']; ?>"> <!-- Input tersembunyi untuk menyimpan harga satuan -->

      <div class="form-group">
        <label>Total Harga</label>
        <p><?php echo "Rp " . $row_produk['harga']; ?> x <span id="jumlah_display">0</span> = <span id="total_harga_display">Rp 0</span></p>
      </div>
      <button type="submit" class="btn btn-primary">Pesan</button>
    </form>
  </div>

  <script>
    document.getElementById('jumlah').addEventListener('input', function() {
      var jumlah = this.value;
      var harga = <?php echo $row_produk['harga']; ?>;
      var total_harga = jumlah * harga;
      document.getElementById('jumlah_display').innerText = jumlah;
      document.getElementById('total_harga_display').innerText = 'Rp ' + total_harga.toLocaleString();
    });
  </script>
</body>

</html>