<?php
session_start();

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function flash($name, $message = '') {
    if (!empty($message) && empty($_SESSION[$name])) {
        $_SESSION[$name] = $message;
    } elseif (!empty($_SESSION[$name])) {
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                ' . $_SESSION[$name] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
        unset($_SESSION[$name]);
    }
}
?>