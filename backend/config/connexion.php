<?php
//démarrer une session
session_start();


try {
    $pdo = new PDO("mysql:host=localhost;dbname=exercice_login", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}