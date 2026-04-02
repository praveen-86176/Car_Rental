<?php
require_once 'cors.php';
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
        http_response_code(403);
        echo json_encode(["error" => "Unauthorized. Agency access required."]);
        exit;
    }
}

function requireCustomer() {
    if (!isLoggedIn() || !isCustomer()) {
        http_response_code(403);
        echo json_encode(["error" => "Unauthorized. Customer access required."]);
        exit;
    }
}
?>
