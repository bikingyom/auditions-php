<?php
session_start();
include("connexion.php");

if (isset($_POST['auditionchoisie'])) {
    $_SESSION['id_aud'] = $_POST['auditionchoisie'];
    header('Location: audition.php');

} else { // sinon c'est que l'utilisateur a oublié de sélectionner une audition, on lui dit et on revient à l'accueil
    $_SESSION['erreuredition'] = "Pour ouvrir une audition, vous devez la sélectionner dans la liste avant de cliquer sur le bouton \"Charger une audition\".";
    header('Location: index.php');
}

?> 
