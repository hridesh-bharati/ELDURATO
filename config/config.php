<?php
// config/config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_URL', 'http://localhost/belt'); 
define('SITE_NAME', 'Belt Store');
define('ASSETS_URL', 'http://localhost/belt/assets');
