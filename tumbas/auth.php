<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function requireLogin($allowedRoles = ['kasir', 'dapur', 'admin']) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    if (!in_array($_SESSION['user_role'], $allowedRoles)) {
        header("Location: login.php?err=access");
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function userName() {
    return $_SESSION['user_nama'] ?? 'Unknown';
}

function userRole() {
    return $_SESSION['user_role'] ?? '';
}
