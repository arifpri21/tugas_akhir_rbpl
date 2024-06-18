<?php
include 'koneksi.php';

// Memulai session
session_start();

// Jika form pendaftaran disubmit
if (isset($_POST['register'])) {
    // Ambil data dari form pendaftaran
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Lakukan validasi atau filter data input jika diperlukan

    // Query untuk memeriksa apakah username sudah digunakan
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    // Periksa apakah username sudah digunakan
    if (mysqli_num_rows($checkResult) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        // Query untuk menyimpan data pengguna baru ke database
        // Sesuaikan query ini dengan struktur tabel 'users' di database Anda
        $insertQuery = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        $insertResult = mysqli_query($conn, $insertQuery);

        // Periksa hasil query
        if ($insertResult) {
            // Pendaftaran berhasil, arahkan ke halaman process_login.php
            header("Location: process_login.php");
            exit;
        } else {
            // Pendaftaran gagal, tampilkan pesan error
            $error = "Pendaftaran gagal. Silakan coba lagi.";
        }
    }
}

// Pengecekan session untuk mengarahkan pengguna ke halaman dashboard jika sudah login
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Halaman Registrasi</title>
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
            max-width: 800px;
            /* Ubah nilai lebar container di sini */
            margin: 0 auto;
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
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
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
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .login-link {
            text-align: center;
            color: #555;
        }

        @media screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            label {
                font-size: 14px;
            }

            input[type="text"],
            input[type="password"],
            input[type="email"] {
                font-size: 14px;
            }

            button[type="submit"] {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Halaman Registrasi</h2>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST" action="">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit" name="register">Daftar</button>
            </div>
        </form>
        <div class="login-link">
            Sudah memiliki akun? <a href="process_login.php">Login disini</a>
        </div>
    </div>
</body>

</html>