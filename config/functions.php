<!-- config\functions.php -->
<?php
require_once __DIR__ . '/config.php';

if (!function_exists('asset')) {
    function asset($path) {
        return SITE_URL . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url($path) {
        return SITE_URL . '/' . ltrim($path, '/');
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice($amount) {
        return '₹' . number_format($amount, 2);
    }
}