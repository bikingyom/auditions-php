<?php
    session_start();
    include("connexion.php");
    
    // TODO Message d'erreur si pas d'élève sélectionné
    
    // On supprime le lien entre élève et morceau (l'élève n'apparaît plus dans le morceau)
    unset($_SESSION['eleves_tmp'][array_search($_POST['elevechoisi'], $_SESSION['eleves_tmp'])]);
    
    // On mémorise les élèves supprimés pour pouvoir faire le ménage dans la bdd lors de la validation du morceau
    $_SESSION['eleves_suppr'][] = $_POST['elevechoisi'];
    
    header('Location: morceau.php');
?>
