<?php

error_reporting(E_ALL);         /*“Affiche TOUTES les erreurs possibles”*/
ini_set('display_errors', 1);   /*Active l’affichage des erreurs directement sur la page web.*/

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

        $_SESSION['success'] = "Connexion réussie !"; 

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

/*PARTIE jeu*/
$maxEssais = 4;

/*initialise le tableau des essaie*/
if (!isset($_SESSION['essais'])) {
    $_SESSION['essais'] = [];
}

/*Reset de partie*/
if (isset($_POST['reset'])) {
    unset($_SESSION['target']); /*SUPPR*/
    unset($_SESSION['essais']);  /*SUPPR*/
    unset($_SESSION['resultat']);
    unset($_SESSION['game_over']);

    header("Location: index.php?page=jeu");
    exit;
}

/*recup éléve aléatoire*/
if (!isset($_SESSION['target'])) {
    $stmt = $pdo->query("SELECT * FROM eleves ORDER BY RAND() LIMIT 1");/*query execute la requete sql pour avoir un eleve random*/
    $_SESSION['target'] = $stmt->fetch();                               /*fetch() recup la valeur et la garde même si CTRL + R */
}
/*recup le nom tester*/
if (isset($_POST['guess'])) {

/*nb test max*/
    if (count($_SESSION['essais']) >= $maxEssais) {
        $_SESSION['error'] = "❌ Tu as atteint la limite de $maxEssais essais !";
        header("Location: index.php?page=jeu");
        exit;
    }

    $prenom = $_POST['prenom']; /*variable $prenom = prenom donné */

    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE prenom = ?");/*prepare() = Prépare une requête à l'exécution et retourne un objet*/
    $stmt->execute([$prenom]);

    $guess = $stmt->fetch();

    /*Si élève pas dans la liste*/
    if (!$guess) {
        $_SESSION['error'] = "Élève introuvable";
        header("Location: index.php?page=jeu");
        exit;
    }
}


/*Teste les resultats*/
if (isset($guess) && $guess) {

    $target = $_SESSION['target'];

    $resultat = "";

    $resultat.= "<h3>Résultat :</h3>";

    /*Prenom*/
    $resultat .= "<strong>"   . $guess['prenom'] . "</strong><br>";

    /* Sexe */
    $resultat.= "Sexe : "     .  ($guess['sexe']     === $target['sexe'] ?      "✅" : "❌") . "<br>";

    /* Parcours*/
    $resultat.= "Parcours : " .  ($guess['parcours'] === $target['parcours'] ?  "✅" : "❌") . "<br>";

    /* Lunettes */
    $resultat.= "Lunettes : " .  ($guess['lunettes'] ===  $target['lunettes'] ?  "✅" : "❌") . "<br>";

    /* Cheveux */
    $resultat.=  "Cheveux : " . ($guess['cheveux']  ===  $target['cheveux'] ?   "✅" : "❌") . "<br>";

    /* Naissance */
    if ($guess['naissance'] == $target['naissance']) {
            $resultat.= "Naissance : ✅<br>";
    } elseif ($guess['naissance'] < $target['naissance']) {
            $resultat.= "Naissance : ⬆️<br>";
    } else {
            $resultat.="Naissance : ⬇️<br>";
    }

    /* Taille */
    if ($guess['taille'] == $target['taille']) {
        $resultat.= "Taille : ✅<br>";
    }elseif ($guess['taille'] < $target['taille']) {
        $resultat.= "Taille : ⬆️<br>";
    } else {
        $resultat.= "Taille : ⬇️<br>";
    }

/* Test victoire*/
if ($guess['prenom'] === $target['prenom']) {

    $_SESSION['resultat'] = "
    <div class='victory'>
        <img src='assets/images/Trophee.png' width='50'>
        <h2> Bravo !</h2>
        <p>Tu as trouvé : <strong>" . $target['prenom'] . "</strong></p>
    </div>
    ";

    /* bloque la partie */
    $_SESSION['game_over'] = true;

    header("Location: index.php?page=jeu");
    exit;
}
    
    /* Permet de gerer les essaies */
    $_SESSION['essais'][] = [
        'prenom'   => $guess['prenom'],
        'sexe'     => ($guess['sexe']     === $target['sexe']),
        'parcours' => ($guess['parcours'] === $target['parcours']),
        'lunettes' => ($guess['lunettes'] == $target['lunettes']),
        'cheveux'  => ($guess['cheveux']  === $target['cheveux']),
        
        'naissance' => ($guess['naissance'] == $target['naissance']) ? "ok"
            : ($guess['naissance'] < $target['naissance'] ? "up" : "down"),

        'taille' => ($guess['taille'] == $target['taille']) ? "ok"
            : ($guess['taille'] < $target['taille'] ? "up" : "down"),
    ]; 

    /*si trop d'essaie*/
    if (count($_SESSION['essais']) >= $maxEssais && $guess['prenom'] !== $target['prenom']) {

    $_SESSION['error'] = "
    <div class='game-over'>
        <img src='assets/images/Trophee_Brise.png' width='40'>
        <p>Perdu ! L'élève était : <strong>" . $target['prenom'] . "</strong></p>
    </div>
";

$_SESSION['game_over'] = true;
}
}


/* Partie affichage */

include __DIR__ . '/includes/header.php'; /*__DIR__  permet de donner le chemin absolue */

$allowedPages = ['accueil', 'admin', 'jeu','contact', 'conditions']; /*Whiteliste de mon projet */

/*permet de crée un chemin accessible uniquement pour les admin*/
$page = $_GET['page'] ?? 'accueil'; 

if (in_array($page, $allowedPages)) { /*in_array — Indique si une valeur appartient à un tableau (si la page est dans la liste)*/
    include __DIR__ . '/pages/' . $page . '.php';
} else {
    echo "Page introuvable";
}


include __DIR__ . '/includes/footer.php';
/* require possible à la place de include */
