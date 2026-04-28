 <!-- Affichage de tout ce qui est en haut des pages à chaque fois -->

 <!DOCTYPE html>
 <html lang="fr">
     <head>
         <meta charset="UTF-8">
         <title>Studle</title>
         <link rel="stylesheet" href="/STU_DLE/assets/css/style.css"> <!-- liaison au css -->
     </head>
     <body>

     
     
     <header>
         <img src="/STU_DLE/assets/images/Logo.png" width="160" height="95" alt="Studle">
         <h1>Studle</h1>
    
        <nav>                                                  <!-- Fait comprendre au programme que cette section permet de voyager entre les pages -->
            <a href="index.php">Accueil</a>                    <!-- Lien Hypertexte vers index.php -->
            

             <!--Lien vers le pannel admin-->
             <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="index.php?page=admin">Admin</a>   
            <?php endif; ?>

             <!-- Si connecter -->
             <?php if (isset($_SESSION['user'])): ?>        
                <p>Bienvenue <?= $_SESSION['user'] ?></p>
             <a href="?logout=1">Déconnexion</a>
        
             <!--Si pas connecter-->
             <?php else: ?>                                 
             <p>Non connecté</p>
            
             <?php endif; ?>

            <a href="index.php?page=jeu">Jeu</a>
         </nav>
     </header>

     <!--Verification de la connexion avec PHP-->
     <?php if (isset($_SESSION['success'])): ?>
         <div id="success-message">
             <?= $_SESSION['success']; ?>
         </div>
         <?php unset($_SESSION['success']); ?> <!--S'affiche une fois même si on refresh la page-->
    <?php endif; ?>

     <main>