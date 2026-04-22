<?php

require __DIR__ . '/config/database.php';

session_start();

/* CREATION du token CSRF*/
if (!isset($_SESSION['token'])) { /*si pas de token*/
    $_SESSION['token'] = bin2hex(random_bytes(32)); /*bin2hex — Convertit des données binaires en représentation hexadécimale */
}

/*VERIFIER la connexion*/
if (isset($_POST['login'])) { /*si login pressé */

    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch();/*fetch() permet de récupérer une ligne d'un ensemble de résultats associé à un PDOStatementobjet.*/

    if ($user && password_verify($password, $user['password'])) { /* password_verify Vérifie que le hachage fourni correspond bien au mot de passe fourni */

        $_SESSION['user'] = $user['pseudo'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit;

    } else {
        echo "Identifiants incorrects";
    }
}

/*DECONNEXION*/
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}


/*ENREGISTRER un mdp*/
if (isset($_POST['register'])) {

    $pseudo = $_POST['pseudo'];
    $email  = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); /*password_hash chiffre le mdp*/

    try {
        $stmt = $pdo->prepare("INSERT INTO users (pseudo, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$pseudo, $email, $password]);

        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

/* SUPPRESSION de compte*/
if (isset($_GET['delete'])) {

    /*Vérification est connecté*/
    if (!isset($_SESSION['user'])) {
        die("Vous devez être connecté");
    }

    /* Vérification admin*/
    if ($_SESSION['role'] !== 'admin') {
        die("Accès refusé");
    }

    /* Vérification bon token*/
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['token']) {
    die("Token invalide");
    }

    /*Recup l'uttilisateur*/
    $id = (int) $_GET['delete']; /*Force un int donc un id */

    /*Vécifier que l'utilisateur existe */
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Utilisateur introuvable");
    }

    /*Empécher l'auto supression */
    if ($user['pseudo'] === $_SESSION['user']) {
        die("Vous ne pouvez pas vous supprimer vous-même");
    }

    /*suppression*/
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

/* MODIFICATION de compte */
if (isset($_POST['update'])) {

    /*Vérification est connecté*/
    if (!isset($_SESSION['user'])) {
        die("Vous devez être connecté");
    }
    
    /* Vérification admin*/
    if ($_SESSION['role'] !== 'admin') {
        die("Accès refusé");
    }

    $id     = $_POST['id'];
    $pseudo = $_POST['pseudo'];
    $email  = $_POST['email'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET pseudo = ?, email = ? WHERE id = ?");
        $stmt->execute([$pseudo, $email, $id]);

        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}


/* Partie affichage */

include __DIR__ . '/includes/header.php'; /*__DIR__  permet de donner le chemin absolue */

$allowedPages = ['accueil', 'admin', 'jeu']; /*Whiteliste de mon projet */

/*permet de crée un chemin accessible uniquement pour les admin*/
$page = $_GET['page'] ?? 'accueil'; 

if (in_array($page, $allowedPages)) { /*in_array — Indique si une valeur appartient à un tableau (si la page est dans la liste)*/
    include __DIR__ . '/pages/' . $page . '.php';
} else {
    echo "Page introuvable";
}


include __DIR__ . '/includes/footer.php';
/* require possible à la place de include */
