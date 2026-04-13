<?php

$host = 'localhost'; /* crée une variable host qui contient le texte localhost */
$dbname = 'student_dle'; 
$username = 'root'; 
$password = ''; 

try {/*  Fonction qui tente de ce connecter */
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); /* Crée une connexion avec les variables au dessus */
    
    /* Mode erreur (important) */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion réussie !";
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}