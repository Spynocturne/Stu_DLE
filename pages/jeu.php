<h2> Trouve l'élève</h2>

<form method="POST">
    <input type="text" name="prenom" placeholder="Entrez un prénom" required>
    <button type="submit" name="guess">Tester</button>
</form>


<h2> Essais</h2>

<table class="game-table">
    <tr>
        <th>Prénom</th>
        <th>Sexe</th>
        <th>Parcours</th>
        <th>Naissance</th>
        <th>Taille</th>
        <th>Lunettes</th>
        <th>Cheuveux</th>
    </tr>

<!-- Pour chaque essaie faire les tests-->
<?php foreach ($_SESSION['essais'] as $essai): ?>
<tr>
    <td><?= $essai['prenom'] ?></td>

    <!-- Test Sexe -->
    <td class="<?= $essai['sexe'] ? 'ok' : 'no' ?>">
        <img src="assets/images/<?= $essai['sexe'] ? 'Reussite.png' : 'Erreur.png' ?>">
    </td>
    <!-- Test Parcours -->
    <td class="<?= $essai['parcours'] ? 'ok' : 'no' ?>">
        <img src="assets/images/<?= $essai['parcours'] ? 'Reussite.png' : 'Erreur.png' ?>" >
    </td>

    <!-- Test Naissance -->
    <td class="<?= $essai['naissance'] ?>">
        <?php if ($essai['naissance'] === 'up'): ?>
            <img src="assets/images/Fleche_UP.png" >
        <?php elseif ($essai['naissance'] === 'down'): ?>
            <img src="assets/images/Fleche_DOWN.png" >
        <?php else: ?>
            <img src="assets/images/Reussite.png" >
        <?php endif; ?>
    </td>

    <!-- Test taille -->
    <td class="<?= $essai['taille'] ?>">
                <?php if ($essai['taille'] === 'up'): ?>
            <img src="assets/images/Fleche_UP.png" >
        <?php elseif ($essai['taille'] === 'down'): ?>
            <img src="assets/images/Fleche_DOWN.png" >
        <?php else: ?>
            <img src="assets/images/Reussite.png" >
        <?php endif; ?>
    </td>

    <!-- Test Lunette -->
    <td class="<?= $essai['lunettes'] ? 'ok' : 'no' ?>">
        <img src="assets/images/<?= $essai['lunettes'] ? 'Reussite.png' : 'Erreur.png' ?>" >
    </td>

    <!-- Test Cheuveux -->
    <td class="<?= $essai['cheuveux'] ? 'ok' : 'no' ?>">
        <img src="assets/images/<?= $essai['cheuveux'] ? 'Reussite.png' : 'Erreur.png' ?>">
    </td>

</tr>
<?php endforeach; ?>

</table>


<h2>📜 Historique des essais</h2>

<?php if (!empty($_SESSION['essais'])): ?>

    <?php foreach ($_SESSION['essais'] as $essai): ?>
        <div class="result">
            <td><?= $essai['prenom'] ?></td> <!-- Affiche tableau-->
        </div>
    <?php endforeach; ?>

<?php endif; ?>
