<?php
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ecv_2021_b2_Yaakov';

try {
    $connexion = new PDO("mysql:host=" . $servername . ";dbname=" . $dbname . ";", $username, $password);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// initialiser les variables de sessions
