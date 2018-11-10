 <?php
    session_start();
    include("connexion.php");
    
    // on récupère les id des élèves correspondants au le morceau sélectionné
    $reponse_eleves = $bdd->prepare('SELECT eleve_id FROM eleves WHERE morceau_id = ?');
    $reponse_eleves->execute(array($_POST['morceauchoisi']));
    while ($eleves = $reponse_eleves->fetch()) { // et s'ils ne sont pas déjà dans les eleves_tmp, on les ajoute
        if(!in_array($eleves['eleve_id'], $_SESSION['eleves_tmp'])) {
            $_SESSION['eleves_tmp'][] = $eleves['eleve_id'];
        }
    }
    
    header('Location: morceau.php');
?>
