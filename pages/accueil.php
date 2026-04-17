<!-- contenue principale -->
<main>
        <h2>Inscription</h2>

    <form method="POST" action="">   <!--method="POST" envoie les données à PHP --> <!--action="" → envoie vers la même page -->
        <input type="text"     name="pseudo"   placeholder="Pseudo" required> <!-- tout les champ required doivent être rempli-->
        <input type="email"    name="email"    placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit"  name="register">S'inscrire</button>
    </form>

    <h2>Liste des utilisateurs</h2>


    <!-- LISTER les utilisateur -->
<?php
$sql = "SELECT * FROM users";
$result = $pdo->query($sql); /*query execute une requete SQL sur la base de donné*/

foreach ($result as $row) {/* Pour chaque personne on peut supr ou modif le compte */
     echo $row['pseudo'] . " - " . $row['email'];
    
     if (!empty($_SESSION['user']) && $_SESSION['role'] === 'admin') { /*Afficher uniquement si connecté et admin */
        echo " <a href='?delete=" . $row['id'] . "'>Supprimer</a>";
        echo " <a href='?edit="   . $row['id'] . "'>Modifier</a>";
    }

    echo "<br>";
}
?>


<h2>Connexion</h2>

<form method="POST">
    <input  type="email"    name="email"    placeholder="Email"        required>
    <input  type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit"   name="login">Se connecter</button>
</form>


<!--MODIFICATION de compte-->
<?php
if (isset($_GET['edit'])) { /*si le edit present dans Modifier est vrai/appuyer */
    $id = $_GET['edit'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
?>

<h2>Modifier utilisateur</h2>

<form method="POST">
    <input  type="hidden" name="id"     value="<?= $user['id'] ?>"> <!-- le "hidden" est pour le faire apparaitre uniquement quand on veut modif-->
    <input  type="text"   name="pseudo" value="<?= $user['pseudo'] ?>" required>
    <input  type="email"  name="email"  value="<?= $user['email'] ?>" required>
    <button type="submit" name="update">Modifier</button>
</form>

<?php } ?>

</main>