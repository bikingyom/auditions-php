<?php
session_start();
include("connexion.php");

unset($_SESSION['eleves_suppr']);
unset($_SESSION['eleves_tmp']);
unset($_SESSION['erreuredition']);
unset($_SESSION['morceau_tmp']);

if (isset($_POST['morceauchoisi'])) { // si on est en mode édition avec un morceau sélectionné
    $_SESSION['morceau_tmp']['id'] = $_POST['morceauchoisi'];
    $_SESSION['edition_morceau'] = true;
    
    // pour pouvoir annuler, on va faire toutes les modifs dans des variables temporaires
    
    $req = $bdd->prepare('SELECT eleve_id FROM eleves WHERE morceau_id = ?');
    $req->execute(array($_SESSION['morceau_tmp']['id']));
    $i = 0;
    while($id = $req->fetch()) {
        $i++;
        $_SESSION['eleves_tmp'][$i] = $id['eleve_id'];
    }
    $req->closeCursor();
    
    $reponse_morceau = $bdd->prepare('SELECT titre, compositeur, MINUTE(duree) AS minutes, SECOND(duree) AS secondes, chaises, pupitres, materiel FROM morceaux WHERE id = ?');
    $reponse_morceau->execute(array($_SESSION['morceau_tmp']['id']));
    $donnees_morceau = $reponse_morceau->fetch();
    $_SESSION['morcceau_tmp']['titre'] = $donnees_morceau['titre'];
    $_SESSION['morcceau_tmp']['compositeur'] = $donnees_morceau['compositeur'];
    $_SESSION['morcceau_tmp']['minutes'] = $donnees_morceau['minutes'];
    $_SESSION['morcceau_tmp']['secondes'] = $donnees_morceau['secondes'];
    $_SESSION['morcceau_tmp']['chaises'] = $donnees_morceau['chaises'];
    $_SESSION['morcceau_tmp']['pupitres'] = $donnees_morceau['pupitres'];
    $_SESSION['morcceau_tmp']['materiel'] = $donnees_morceau['materiel'];
    $reponse_morceau->closeCursor();
    
/*    print_r($_SESSION['eleves_tmp']);
    print_r($_SESSION['morceau_tmp']);*/
    
} else { // sinon c'est que l'utilisateur a oublié de sélectionner un morceau, on lui dit et lui propose d'ajouter un nouveau morceau à la place
    $_SESSION['erreuredition'] = "Pour modifier un morceau, vous devez le sélectionner dans la liste avant de cliquer sur le bouton \"Editer un morceau\". Vous pouvez cliquer sur \"Annuler\" pour revenir à la page précédente et effectuer votre sélection, ou si vous vous ravisez, vous pouvez profiter de ce formulaire pour saisir un nouveau morceau !";
    $_SESSION['morcceau_tmp']['titre'] = '';
    $_SESSION['morcceau_tmp']['compositeur'] = '';
    $_SESSION['morcceau_tmp']['minutes'] = 0;
    $_SESSION['morcceau_tmp']['secondes'] = 0;
    $_SESSION['morcceau_tmp']['chaises'] = 0;
    $_SESSION['morcceau_tmp']['pupitres'] = 0;
    $_SESSION['morcceau_tmp']['materiel'] = '';
    $_SESSION['edition_morceau'] = false;
}

header('Location: morceau.php');
?>
