<?php
if (!isset($_SESSION['user'])) {
    die("Vous devez être connecté");
}

if ($_SESSION['role'] !== 'admin') {
    die("Accès refusé");
}
?>

<h2>Panel Admin</h2>

<h3>Liste des utilisateurs</h3>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Pseudo</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>

<?php
$sql = "SELECT * FROM users";
$result = $pdo->query($sql);

/*TABLEAU des users*/
foreach ($result as $row) { 
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['pseudo'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";

    echo "<td>";
    echo "<a href='?page=admin&delete=" . $row['id'] . "' onclick=\"return confirm('Supprimer ?')\">❌</a> ";
    echo "<a href='?page=admin&edit="   . $row['id'] . "'>✒️</a>";
    echo "</td>";

    echo "</tr>";
}
?>
</table>

<!-- Ajout de la mofication -->
<?php
if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
?>

<h3>Modifier utilisateur</h3>

<form method="POST">
    <input  type="hidden" name="id"     value="<?= $user['id'] ?>">
    <input  type="text"   name="pseudo" value="<?= $user['pseudo'] ?>" required>
    <input  type="email"  name="email"  value="<?= $user['email'] ?>"  required>
    <button type="submit" name="update">Modifier</button>
</form>

<?php } ?>