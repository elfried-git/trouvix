<?php
session_start();
header('Content-Type: application/json');
$response = ['connected' => false, 'is_admin' => false];
if (isset($_SESSION['admin_id'])) {
    $response['connected'] = true;
    $response['is_admin'] = true;
    $response['username'] = $_SESSION['admin_username'] ?? '';
} elseif (isset($_SESSION['user_id'])) {
    $response['connected'] = true;
    $response['username'] = $_SESSION['username'] ?? '';
}
echo json_encode($response);