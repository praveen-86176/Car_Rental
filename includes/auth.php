<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

function getRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

function isAgency() {
    return getRole() === 'agency';
}

function isCustomer() {
    return getRole() === 'customer';
}

function requireAgency() {
    if (!isLoggedIn() || !isAgency()) {
        header('Location: login.php');
        exit;
    }
}

function requireCustomer() {
    if (!isLoggedIn() || !isCustomer()) {
        header('Location: login.php');
        exit;
    }
}

function getUserName() {
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
}
?>
