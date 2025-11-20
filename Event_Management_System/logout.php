<?php
require_once 'config.php';

// Clear "Remember Me" cookies
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/');
}

// Destroy all session data
session_unset();
session_destroy();

// Start a fresh session (prevents session fixation)
session_start();
session_regenerate_id(true);

// Redirect to login page
redirect('login.php');
?>