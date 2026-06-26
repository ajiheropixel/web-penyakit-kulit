<?php
// Middleware proteksi halaman khusus USER
require_once __DIR__ . '/functions.php';

if (!isLoggedIn()) {
    flash('login_required', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
    redirect('../auth/login.php');
}