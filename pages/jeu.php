<h2>🎯 Trouve l'élève</h2>

<form method="POST">
    <input type="text" name="prenom" placeholder="Entrez un prénom" required>
    <button type="submit" name="guess">Tester</button>
</form>

<?php if (isset($_SESSION['resultat'])): ?>
    <div class="result">
        <?= $_SESSION['resultat']; ?>
    </div>
    <?php unset($_SESSION['resultat']); ?>
<?php endif; ?>