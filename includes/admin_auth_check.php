<?php
// Middleware proteksi halaman khusus ADMIN
require_once __DIR__ . '/functions.php';

if (!isAdmin()) {
    flash('login_required', 'Silakan login sebagai admin untuk mengakses halaman ini.');
    redirect('../auth/login.php');
}