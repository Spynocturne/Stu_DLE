<!-- contenue principale -->
<div class="container">

    <h2>Inscription</h2>

    <form method="POST" action="">   <!--method="POST" envoie les données à PHP --> <!--action="" → envoie vers la même page -->
        <input type="text"     name="pseudo"   placeholder="Pseudo" required> <!-- tout les champ required doivent être rempli-->
        <input type="email"    name="email"    placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit"  name="register">S'inscrire</button>
    </form>

    <h2><img src="assets/images/Livre.png" >Liste des utilisateurs</h2>


    <!-- LISTER les utilisateur -->
<?php
$sql = "SELECT * FROM users";
$result = $pdo->query($sql); /*query execute une requete SQL sur la base de donné*/

foreach ($result as $row) {/* Pour chaque personne on peut supr ou modif le compte */
echo "<div class='user'>";
echo "<strong>" . $row['pseudo'] . "</strong><br>";
echo $row['email'];

if (!empty($_SESSION['user']) && $_SESSION['role'] === 'admin') {
    echo "<br><a href='?delete=" . $row['id'] . "&token=" . $_SESSION['token'] . "'>Supprimer ❌</a>"; /*suppression avec token*/
    echo " <a href='?edit=" . $row['id'] . "'>Modifié : ✏️</a>";
}

echo "</div>";
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
<!-- Partie qui s'affiche quand on appuie sur modifié-->
<h2>Modifier utilisateur</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <input type="text" name="pseudo" value="<?= htmlspecialchars($user['pseudo']) ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <button type="submit" name="update">Modifier</button>
</form>

<?php } ?>



</div>