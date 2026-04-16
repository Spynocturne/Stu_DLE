<?php

require __DIR__ . '/config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") { /*si formulaire envoyer*/

    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];

    try {
        $sql = "INSERT INTO users (pseudo, email) VALUES ('$pseudo', '$email')";
        $pdo->exec($sql);

        /*redirection*/
        header("Location: index.php");
        exit;   

        echo "Utilisateur ajouté !";

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

/* Partie affichage */

include __DIR__ . '/includes/header.php'; /*__DIR__  permet de donner le chemin absolue */
include __DIR__ . '/pages/accueil.php'; 
include __DIR__ . '/includes/footer.php';
/* require possible à la place de include */
