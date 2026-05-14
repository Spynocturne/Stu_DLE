<?php
/* CONFIGURATION */
/*Adresse lié a l'hébergement*/
$email_expediteur   = 'Mon@Hebergement.fr';
/*nom du site ou le mien*/
$nom_expediteur     = 'STU_dle';
$destinataire       = 'hugo.dolat@etu.univ-st-etienne.fr';
$copie              = 'oui'; // 'oui' ou 'non'
$form_action        = 'index.php?page=contact';

$message_envoye              = "Votre message nous est bien parvenu !";
$message_non_envoye          = "L'envoi du mail a échoué, veuillez réessayer SVP.";
$message_formulaire_invalide = "Vérifiez que tous les champs soient bien remplis et que l'email soit valide.";

 /* FONCTIONS */
/*Nettoie et enregistre un texte*/
function Rec($text)
{
	$text = htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8'); 
        /*htmlspecialchars(...) : C'est la sécurité principale. Elle transforme les caractères spéciaux (comme < ou >) en code HTML. Cela empêche quelqu'un d'injecter du code malveillant (JavaScript) dans votre page.*/ 
        /*trim($text) : Supprime les espaces inutiles au début et à la fin*/
return nl2br($text);
        /*nl2br($text) : Convertit les retours à la ligne du clavier en balises HTML <br>, pour que le texte reste bien structuré si vous l'affichez sur une page web.*/
}
 
/* Cette fonction sert à vérifier la syntaxe d'un email*/
function IsEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL); /*renvoie True si valide*/
}
 
/* TRAITEMENT */
$nom     = isset($_POST['nom'])     ? Rec($_POST['nom'])     : '';
$email   = isset($_POST['email'])   ? Rec($_POST['email'])   : '';
$objet   = isset($_POST['objet'])   ? Rec($_POST['objet'])   : '';
$message = isset($_POST['message']) ? Rec($_POST['message']) : '';
$err_formulaire = false; 
 
if (isset($_POST['envoi'])) {
    if ($nom != '' && IsEmail($email) && $objet != '' && $message != '') {
        
        // Nettoyage pour éviter les injections
        $objet_clean = str_replace(array("\r", "\n"), '', html_entity_decode($objet));
        $message_clean = html_entity_decode($message);
        
        $headers   = 'MIME-Version: 1.0' . "\r\n";
        $headers  .= 'From: '.$nom_expediteur.' <'.$email_expediteur.'>' . "\r\n";
        $headers  .= 'Reply-To: '.$email . "\r\n";
        $headers  .= 'Content-Type: text/plain; charset="utf-8"' . "\r\n";
        $headers  .= 'X-Mailer: PHP/' . phpversion();

        $cibles = ($copie == 'oui') ? [$destinataire, $email] : [$destinataire];
        $envois_reussis = 0;

        foreach($cibles as $cible) { /*fonction PHP qui envoie le mail*/
            if (mail($cible, $objet_clean, $message_clean, $headers)) {
                $envois_reussis++;
            }
        }

        if ($envois_reussis > 0) {
            echo '<p style="color:green;">' . $message_envoye . '</p>';
        } else {
            echo '<p style="color:red;">'   . $message_non_envoye . '</p>';
        }
    } else {
        echo '<p style="color:red;">'       . $message_formulaire_invalide . '</p>';
        $err_formulaire = true;
    }
}
 
/* AFFICHAGE DU FORMULAIRE */
if ($err_formulaire || !isset($_POST['envoi'])) {
?>
    <form id="contact" method="post" action="<?php echo $form_action; ?>">
        <fieldset>                                                                                                                      <!--L'élément HTML <fieldset> est utilisé afin de regrouper plusieurs contrôles interactifs ainsi que des étiquettes (<label>) dans un formulaire.-->
            <legend>Vos coordonnées</legend>                                                                                            <!--L'élément HTML <legend> représente une légende pour le contenu de son parent <fieldset>.-->
            <p><label for="nom">Nom :</label><input type="text" id="nom" name="nom" value="<?php echo stripslashes($nom); ?>" /></p>    <!--stripslashes($nom) est utilisée lors de l'affichage du formulaire.*/-->
            <p><label for="email">Email :</label><input type="email" id="email" name="email" value="<?php echo stripslashes($email); ?>" /></p>
        </fieldset>

        <fieldset>
            <legend>Votre message :</legend>
            <p><label for="objet">Objet :</label><input type="text" id="objet" name="objet" value="<?php echo stripslashes($objet); ?>" /></p>
            <p><label for="message">Message :</label><textarea id="message" name="message" cols="30" rows="8"><?php echo stripslashes($message); ?></textarea></p>
        </fieldset>

        <div style="text-align:center;"><input type="submit" name="envoi" value="Envoyer le formulaire !" /></div>
    </form>
<?php 
} 
?>