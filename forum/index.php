<?php
session_set_cookie_params(['path' => '/']);
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: admin-dashboard.html');
    exit();
} else {
    header('Location: admin-login.html');
    exit();
}
?>
