<?php
include 'koneksi.php';

// Memulai session
session_start();

// Cek apakah pengguna sudah login
if (isset($_SESSION['username'])) {
    // Pengguna sudah login, arahkan ke halaman dashboard atau sesuai kebutuhan
    header("Location: dashboard.php");
    exit;
}

// Jika form login disubmit
if (isset($_POST['login'])) {
    // Ambil data dari form login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lakukan validasi atau filter data input jika diperlukan

    // Query untuk mencari pengguna dengan username dan password yang cocok
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    // Periksa hasil query
    if (mysqli_num_rows($result) == 1) {
        // Data pengguna ditemukan, login berhasil
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;

        // Periksa apakah pengguna adalah admin
        if ($user['role'] == 'admin') {
            // Redirect ke halaman admin
            header("Location: dashboard_admin.php");
            exit;
        } else {
            // Redirect ke halaman dashboard atau sesuai kebutuhan
            header("Location: dashboard.php");
            exit;
        }
    } else {
        // Data pengguna tidak ditemukan, tampilkan pesan error
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Halaman Login</title>
    <link href='creazy.co.png' rel='shortcut icon'>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url(bg1.jpg);
            /* Ganti dengan URL gambar latar belakang yang diinginkan */
            background-size: cover;
            background-position: bottom;
        }

        .container {
            max-width: 400px;
            padding: 40px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;

        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        button[type="submit"] {
            background-color: rgb(27, 160, 226);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: rgb(27, 160, 226)F50;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Halaman Login</h2>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST" action="">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit" name="login">Login</button>
            </div>
        </form>
        <div class="register-link">
            Belum memiliki akun? <a href="save_registration.php">Daftar disini</a>
        </div>
    </div>
</body>

</html>