<?php
    session_start();
    include("connexion.php");
    
    // TODO Message d'erreur si pas de morceau sélectionné
    
    $reponse_eleves = $bdd->prepare('SELECT eleve_id FROM eleves WHERE morceau_id = ?');
    $reponse_eleves->execute(array($_POST['morceauchoisi']));
    while($eleve = $reponse_eleves->fetch()) {
        // On supprime le lien entre élève et morceau (l'élève n'apparaît plus dans le morceau)
        
        $req = $bdd->prepare('DELETE FROM eleves WHERE eleve_id = ? AND morceau_id = ?');
        $req->execute(array($eleve['eleve_id']), $_POST['morceauchoisi']);
    
        // On récupère le nombre de fois où cet élève apparaît ailleurs dans la bdd
        $reponse_touseleves = $bdd->prepare('SELECT COUNT(*) AS nb_morceaux FROM eleves WHERE eleve_id = ?');
        $reponse_touseleves->execute(array($eleve['eleve_id']));
        $donnees_touseleves = $reponse_touseleves->fetch();
        $nb_morceaux = $donnees_touseleves['nb_morceaux'];
        
    
        // Si l'élève n'est plus présent nulle part ailleurs dans la bdd, on le supprime aussi de la table baseeleve
        if($nb_morceaux == 0) {
            $req = $bdd->prepare('DELETE FROM baseeleve WHERE id = ?');
            $req->execute(array($eleve['eleve_id']));
        }
        $reponse_touseleves->closeCursor();
    }
    $reponse_eleves->closeCursor();
    
    $req = $bdd->prepare('DELETE FROM morceaux WHERE id = ?');
    $req->execute(array($_POST['morceauchoisi']));
    
    header('Location: audition.php');
?> 
