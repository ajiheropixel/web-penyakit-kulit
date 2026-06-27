<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

if (isLoggedIn()) redirect('../user/dashboard.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = 'Email sudah terdaftar. Silakan gunakan email lain.';
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (:nama, :email, :password)");
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed);

            if ($stmt->execute()) {
                flash('register_success', 'Pendaftaran berhasil! Silakan masuk dengan akun Anda.');
                redirect('login.php');
            } else {
                $error = 'Terjadi kesalahan, silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SkinDiag</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/auth.css" rel="stylesheet">
</head>
<body class="auth-body">

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <i class="fa-solid fa-stethoscope"></i> SkinDiag
        </div>
        <h4 class="fw-bold mt-3">Buat Akun Baru</h4>
        <p class="text-muted mb-4">Daftar untuk mulai menggunakan sistem diagnosa kulit</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" minlength="6" required>
            </div>
            <button type="submit" class="btn btn-gradient w-100 mt-2">Daftar</button>
        </form>

        <p class="text-center mt-4 mb-0">Sudah punya akun? <a href="login.php" class="fw-semibold">Masuk di sini</a></p>
        <p class="text-center mt-2"><a href="../index.php" class="text-muted small"><i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Beranda</a></p>
    </div>
</div>
<script>
const pass = document.querySelector('input[name="password"]');
const confirm = document.querySelector('input[name="confirm_password"]');

confirm.addEventListener('input', function () {
    if (pass.value !== confirm.value) {
        confirm.classList.add('is-invalid');
    } else {
        confirm.classList.remove('is-invalid');
    }
});
</script>
</body>
</html>