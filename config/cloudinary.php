<!-- config\cloudinary.php -->
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => 'duyauncgi',
        'api_key'    => '785154263816532',
        'api_secret' => 'raBmcXM1RXevmwJJqkhQBzvKORk'
    ],
    'url' => [
        'secure' => true
    ]
]);