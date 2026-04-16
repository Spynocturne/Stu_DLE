<?php

$host = 'localhost'; /* crée une variable host qui contient le texte localhost */
$dbname = 'studle'; 
$username = 'root'; 
$password = ''; 

try {/*  Fonction qui tente de ce connecter */
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); /* Si quelque chose ne va pas, arrête tout et envoie une erreur dans le catch */
    
    /* Mode erreur */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); /*Comment PDO doit réagir dans certaines situations*/
    /*PDO::ATTR_ERRMODE
Le mode pour reporter les erreurs de PDO. Peut prendre une des valeurs suivantes :    
    PDO::ERRMODE_EXCEPTION
Lance des PDOExceptions.*/

    echo "Connexion réussie !";
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage(); /* permet de recup le message */
}

/*remplacé le message d'erreur par die("Une erreur est survenue"); permet d'empécher l'uttilisateur de savoir où est le problème et limiter des scams potentiels*/