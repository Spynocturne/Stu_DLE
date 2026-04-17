<?php

require __DIR__ . '/config/database.php';

/* SUPPRESSION de compte*/
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") { /*si formulaire envoyer*/

    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];

    try {
        /* Préparation de la requête avec des "trous"*/
        $stmt = $pdo->prepare("
        INSERT INTO users (pseudo, email) 
        VALUES (?, ?)"); 

        /*Exécution avec données*/ /* remplis les trous avec des element sécurisé */
        $stmt->execute([
            $pseudo, $email
            ]); 

        /*redirection*/
        header("Location: index.php");
        exit;   

        echo "Utilisateur ajouté !";

    } catch (PDOException $e) { /*récup l'erreur */
        echo "Erreur : " . $e->getMessage();
    }
}

/* Partie affichage */

include __DIR__ . '/includes/header.php'; /*__DIR__  permet de donner le chemin absolue */
include __DIR__ . '/pages/accueil.php'; 
include __DIR__ . '/includes/footer.php';
/* require possible à la place de include */
