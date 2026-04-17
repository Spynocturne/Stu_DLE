<!-- contenue principale -->
<main>
        <h2>Ajouter un utilisateur</h2>

    <form method="POST" action=""> <!--method="POST" envoie les données à PHP --> <!--action="" → envoie vers la même page -->
        <input type="text" name="pseudo" placeholder="Pseudo" required> <!-- tout les champ required doivent être rempli-->
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Envoyer</button>
    </form>

    <h2>Liste des utilisateurs</h2>

<?php
$sql = "SELECT * FROM users";
$result = $pdo->query($sql); /*query execute une requete SQL sur la base de donné*/

foreach ($result as $row) {
    echo $row['pseudo'] . " - " . $row['email'];
    echo " <a href='?delete=" . $row['id'] . "'>Supprimer</a>";
    echo " <a href='?edit=" . $row['id'] . "'>Modifier</a>";
    echo "<br>";
}
?>

</main>