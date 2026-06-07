
<?php
// pages/auth/logout.php

// Agar session pehle se shuru nahi hai toh shuru karein
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Saare session variables ko khali karein
$_SESSION = array();

// Session cookies ko delete karein (Browser se session trace khatam karne ke liye)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session ko puri tarah destroy karein
session_destroy();

// Redirect route: Kyuki file 'pages/auth/' ke andar hai,
// hume 2 folder piche (../../) login.php par bhejna hoga.
header("Location: ../../pages/auth/login.php");
exit;
?>