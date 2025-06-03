<?php
require_once("connexion.php");

// Détruire la session
session_destroy();

// Rediriger vers la page d'inscription
header("Location: login.php");
exit();