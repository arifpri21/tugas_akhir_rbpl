<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
  // Pengguna belum login, arahkan ke halaman login
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

// Mengambil data pengguna dari database berdasarkan username
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Memproses pembaruan data pengguna
if (isset($_POST['submit'])) {
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = $_POST['password'];

  // Mengunggah foto profil
  $targetDir = "uploads/"; // Direktori tempat menyimpan foto profil
  $targetFile = $targetDir . basename($_FILES["profilePicture"]["name"]); // Path file foto yang diunggah
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Cek apakah file gambar yang diunggah adalah gambar valid
  if ($_FILES["profilePicture"]["size"] > 0) {
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if ($check !== false) {
      $uploadOk = 1;
    } else {
      echo "File yang diunggah bukan gambar.";
      $uploadOk = 0;
    }
  }

  // Jika file gambar valid, lakukan upload
  if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
      // Perbarui data pengguna dalam database
      $updateSql = "UPDATE users SET fullname = '$fullname', email = '$email', phone = '$phone', password = '$password', profile_picture = '$targetFile' WHERE username = '$username'";
      if (mysqli_query($conn, $updateSql)) {
        // Data berhasil diperbarui, arahkan kembali ke halaman profil
        header("Location: edit_profil.php");
        exit;
      } else {
        // Terjadi kesalahan saat memperbarui data, tampilkan pesan kesalahan
        echo "Error: " . mysqli_error($conn);
      }
    } else {
      echo "Terjadi kesalahan saat mengunggah foto profil.";
    }
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Edit Profil</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="pemesanan.css">
  <link href='logoweb.png' rel='shortcut icon'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script>
    function previewProfilePicture(event) {
      var input = event.target;
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>

</head>

<body>
  <nav class="navbar navbar-expand-lg nav-light bg-blue">
    <a class="navbar-brand" href="#"><img src="ripan.png" width="110" height="40" class="mr-2"></a>
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
            Pesanan Saya
          </a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Cari" aria-label="Cari">
        <button type="button" class="btn btn-outline-light">Cari</button>
      </form>
    </div>
    <div class="ml-auto">
      <a class="nav-link dropdown-toggle dropdown-username" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php if (!empty($row['profile_picture'])) : ?>
          <img src="<?php echo $row['profile_picture']; ?>" width="30" height="30" class="rounded-circle mr-2">
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
    <h2>Edit Profil</h2>
    <form method="post" action="" enctype="multipart/form-data">
      <div class="form-group">
        <label for="fullname">Full Name:</label>
        <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $row['fullname']; ?>" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" value="<?php echo $row['password']; ?>" required>
      </div>
      <div class="form-group">
        <label for="profilePicture">Profile Picture:</label>
        <input type="file" class="form-control-file" id="profilePicture" name="profilePicture" onchange="previewProfilePicture(event)">
        <img id="preview" src="<?php echo $row['profile_picture']; ?>" width="100" height="100" alt="Preview">
      </div>
      <div class="form-group">
        <label for="profilePicture">Profile Picture:</label>
        <input type="file" class="form-control-file" id="profilePicture" name="profilePicture">
      </div>
      <button type="submit" class="btn btn-primary" name="submit">Update</button>
    </form>
  </div>

  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
      <li class="nav-item"><a href="about.php" class="nav-link px-2 text-muted">About</a></li>
    </ul>
    <p class="text-center text-muted">&copy; 2023 riPan Air, Inc</p>
  </footer>
  </div>
</body>

</html>