 <?php
    session_start();
    include("connexion.php");

    foreach($_POST['listeeleves'] as $eleve_id) {
        if(!in_array($eleve_id, $_SESSION['eleves_tmp'])) {
            $_SESSION['eleves_tmp'][] = $eleve_id;
        }
    }
    
    header('Location: morceau.php');
?> 
