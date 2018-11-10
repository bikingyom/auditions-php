<?php

function cleanBaseEleve() {
    include("connexion.php");
    if(isset($_SESSION['eleves_suppr'])) {
        foreach ($_SESSION['eleves_suppr'] as $eleve) {
            // On récupère le nombre de fois où chaque élève apparaît ailleurs dans la bdd
            $reponse_touseleves = $bdd->prepare('SELECT COUNT(*) AS nb_appearances FROM eleves WHERE eleve_id = ?');
            $reponse_touseleves->execute(array($eleve));
            $donnees_touseleves = $reponse_touseleves->fetch();
            $nb_appearances = $donnees_touseleves['nb_appearances'];
        
            // Si l'élève n'est plus présent nulle part ailleurs dans la bdd, on le supprime aussi de la table baseeleve
            if($nb_appearances == 0) {
                $req = $bdd->prepare('DELETE FROM baseeleve WHERE id = ?');
                $req->execute(array($eleve));
            }
            $reponse_touseleves->closeCursor();
        }
    }
}
?>