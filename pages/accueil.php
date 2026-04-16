<!-- contenue principale -->
<main>
        <h2>Ajouter un utilisateur</h2>

    <form method="POST" action=""> <!--method="POST" envoie les données à PHP --> <!--action="" → envoie vers la même page -->
        <input type="text" name="pseudo" placeholder="Pseudo" required> <!-- tout les champ required doivent être rempli-->
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Envoyer</button>
    </form>
</main>