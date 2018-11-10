<?php
    session_start();
    include("connexion.php");
    include("fonctions.php");
    
    // on vérifie si l'élève est déjà dans la base
    $reponse_existe = $bdd->prepare('SELECT * FROM baseeleve WHERE nom = :nvnom AND prenom = :nvprenom');
    $reponse_existe->execute(array(
        'nvnom' => $_POST['nom'],
        'nvprenom' => $_POST['prenom'],
    ));
    $donnees_existe = $reponse_existe->fetch();

    if(empty($donnees_existe)) { // s'il n'existe pas encore...
    
        // on crée le nouvel élève
        $req = $bdd->prepare('INSERT INTO baseeleve (nom, prenom, instrument) VALUES(:nvnom, :nvprenom, :nvinstrument)');
        $req->execute(array(
            'nvnom' => $_POST['nom'],
            'nvprenom' => $_POST['prenom'],
            'nvinstrument' => $_POST['instrument']
        ));
    
        // on récupère l'id de l'élève ajouté
        $req = $bdd->query('SELECT MAX(id) AS max_id FROM baseeleve');
        $donnees = $req->fetch();
        $id_nveleve = $donnees['max_id'];
        $req->closeCursor();
    } else { // s'il existe déjà...
    
        // on récupère son id dans la table baseeleve
        $id_nveleve = $donnees_existe['id'];
    }

    $reponse_existe->closeCursor();
    
    if(!in_array($id_nveleve, $_SESSION['eleves_tmp'])) {// si l'élève n'est pas déjà associé, on associe l'élève au morceau
        $_SESSION['eleves_tmp'][] = $id_nveleve;
    }

    header('Location: morceau.php');
?>
