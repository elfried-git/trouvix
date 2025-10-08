<?php
session_start();
header('Content-Type: application/json');
$response = ['connected' => false];
if (isset($_SESSION['user_id'])) {
    $response['connected'] = true;
    $response['username'] = $_SESSION['username'] ?? '';
}
echo json_encode($response);