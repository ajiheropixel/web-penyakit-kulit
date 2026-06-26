<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

// Jika sudah login, redirect sesuai role
if (isLoggedIn()) redirect('../user/dashboard.php');
if (isAdmin()) redirect('../admin/dashboard.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $db = new Database();
    $conn = $db->getConnection();

    // Cek dulu di tabel admin (login dengan username)
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :identifier LIMIT 1");
    $stmt->bindParam(':identifier', $email);
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama'];
        redirect('../admin/dashboard.php');
    }

    // Jika bukan admin, cek di tabel users (login dengan email)
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nama'] = $user['nama'];
        redirect('../user/dashboard.php');
    } else {
        $error = 'Email/username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SkinDiag</title>
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
        <h4 class="fw-bold mt-3">Selamat Datang Kembali</h4>
        <p class="text-muted mb-4">Masuk untuk melanjutkan diagnosa kesehatan kulit Anda</p>

        <?php flash('register_success'); ?>
        <?php flash('login_required'); ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email / Username</label>
                <input type="text" name="email" class="form-control" placeholder="Masukkan email atau username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-gradient w-100 mt-2">Masuk</button>
        </form>

        <p class="text-center mt-4 mb-0">Belum punya akun? <a href="register.php" class="fw-semibold">Daftar di sini</a></p>
        <p class="text-center mt-2"><a href="../index.php" class="text-muted small"><i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Beranda</a></p>
    </div>
</div>

</body>
</html>