<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Helper.php';
require_once __DIR__ . '/core/Database.php';
$singleFile = [
    'name' => 'test.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => __DIR__ . '/index.php', // fake a file
    'error' => 0,
    'size' => 1024
];
$filename = Helper::uploadFile($singleFile, 'listings');
var_dump($filename);
