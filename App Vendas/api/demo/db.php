<?php
$pdo = new PDO(
    "mysql:host=127.0.0.1;
    dbname=demo;
    charset=utf8mb4",
    "demo",
    "demo@2026",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);