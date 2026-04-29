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
        <th>Cheveux</th>
    </tr>

<?php foreach ($_SESSION['essais'] as $essai): ?>
<tr>
    <td><?= $essai['prenom'] ?></td>

    <td class="<?= $essai['sexe'] ? 'ok' : 'no' ?>"></td>
    <td class="<?= $essai['parcours'] ? 'ok' : 'no' ?>"></td>

    <td class="<?= $essai['naissance'] ?>">
        <?= $essai['naissance'] === 'up' ? '⬆️' : ($essai['naissance'] === 'down' ? '⬇️' : '✅') ?>
    </td>

    <td class="<?= $essai['taille'] ?>">
        <?= $essai['taille'] === 'up' ? '⬆️' : ($essai['taille'] === 'down' ? '⬇️' : '✅') ?>
    </td>

    <td class="<?= $essai['lunettes'] ? 'ok' : 'no' ?>"></td>
    <td class="<?= $essai['cheveux'] ? 'ok' : 'no' ?>"></td>
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
