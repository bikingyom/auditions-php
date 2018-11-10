<?php
session_start();
include("connexion.php");

function memMorceauChoisi() {
    if(isset($_POST['morceauchoisi'])) {
        $_SESSION['morceauchoisi'] = $_POST['morceauchoisi'];
    }
}

function unsetValeursOrdre() {
    unset($_SESSION['ordre_mem']);
    unset($_SESSION['ordre']);
    unset($_SESSION['morceauchoisi']);
    unset($_SESSION['id_ancre']);
}

function getOrdre($bdd) {
    $reponse_ordre = $bdd->prepare('SELECT ordre FROM morceaux WHERE id = ?');
    $reponse_ordre->execute(array($_POST['morceauchoisi']));
    $ordre = $reponse_ordre->fetch()['ordre'];
    $reponse_ordre->closeCursor();
    return $ordre;
}

function getIdWithOrdre($bdd, $ordre) {
    $reponse_id = $bdd->prepare('SELECT id FROM morceaux WHERE ordre = ?');
    $reponse_id->execute(array($ordre));
    $id = $reponse_id->fetch()['id'];
    $reponse_id->closeCursor();
    return $id;
}

function getOrdreAutre($bdd, $requete, $ordre) {
    $reponse_ordre_prec = $bdd->prepare($requete);
    $reponse_ordre_prec->execute(array($ordre, $_SESSION['id_aud']));
    $ordre = $reponse_ordre_prec->fetch()['ordre_autre'];
    $reponse_ordre_prec->closeCursor();
    return $ordre;
}

function switchOrdre($bdd, $ordre, $id) {
    $reponse_ordre = $bdd->prepare('UPDATE morceaux SET ordre = ? WHERE id = ?');
    $reponse_ordre->execute(array($ordre, $id));
}

unset($_SESSION['displaySaveOk']);
$_SESSION['ordre'] = true;

if($_POST['bouton'] == "Changer l'ordre") {
    // on mémorise l'ordre actuel des morceaux de l'audition pour pouvoir le restaurer si l'utilisateur annule
    $reponse_ordre_mem = $bdd->prepare('SELECT id, ordre FROM morceaux WHERE audition_id = ?');
    $reponse_ordre_mem->execute(array($_SESSION['id_aud']));
    while($morceau = $reponse_ordre_mem->fetch()) {
        $_SESSION['ordre_mem'][$morceau['id']] = $morceau['ordre'];
    }
    if(isset($_POST['morceauchoisi'])) {
        memMorceauChoisi(); // on le mémorise pour garder la sélection tant qu'on n'a pas validé ou annulé
        
        // on récupère le numéro d'ordre du morceau précédent
        $ordre_ancre = getOrdreAutre($bdd, 'SELECT MAX(ordre) AS ordre_autre FROM morceaux WHERE ordre < ? AND audition_id = ?', getOrdre($bdd));
        
        if(isset($ordre_ancre)) { // on récupère l'id du morceau où l'ancre sera positionnée (sinon, pas besoin d'ancre puisque c'est qu'on est tout en haut)           
            $_SESSION['id_ancre'] = getIdWithOrdre($bdd, $ordre_ancre);
        } else {
            unset($_SESSION['id_ancre']);
        }
    }
}
elseif ($_POST['bouton'] == 'Annuler') {
    // en cas d'annulation, on restaure l'ordre initial des morceaux
    foreach ($_SESSION['ordre_mem'] as $mem_id => $mem_ordre) {
        $req = $bdd->prepare('UPDATE morceaux SET ordre = ? WHERE id = ?');
        $req->execute(array($mem_ordre,$mem_id));
    }
    unsetValeursOrdre();
}
elseif ($_POST['bouton'] == "Valider l'ordre") {
    // on sort juste du mode édition de l'ordre, toutes les modifications sont déjà enregistrées
    unsetValeursOrdre();
    $_SESSION['displaySaveOk'] = true;
}
elseif($_POST['bouton'] == 'haut' || $_POST['bouton'] == 'bas') {
    // on récupère le numéro d'ordre du morceau choisi
    $ordre = getOrdre($bdd);
    
    // on récupère le numéro d'ordre du morceau précédent-suivant
    if($_POST['bouton'] == 'haut') {
        $requete = 'SELECT MAX(ordre) AS ordre_autre FROM morceaux WHERE ordre < ? AND audition_id = ?';
    } elseif ($_POST['bouton'] == 'bas') {
        $requete = 'SELECT MIN(ordre) AS ordre_autre FROM morceaux WHERE ordre > ? AND audition_id = ?';
    }
    $ordre_autre = getOrdreAutre($bdd, $requete, $ordre);
        
    if(isset($ordre_autre)) { // s'il y a un précédent ou un suivant, c'est-à-dire si on n'essaye pas de remonter plus haut que le début ou de descendre plus bas que la fin
        // on récupère l'id du morceau précédent-suivant
        $id_autre = getIdWithOrdre($bdd, $ordre_autre);
    
        // on remplace le numéro d'ordre du morceau choisi par le numéro d'ordre du morceau précédent-suivant
        switchOrdre($bdd, $ordre_autre, $_POST['morceauchoisi']);
 
        // on remplace le numéro d'ordre du morceau précédent-suivant par le numéro d'ordre du morceau choisi
        switchOrdre($bdd, $ordre, $id_autre);
    } else {
        $ordre_autre = $ordre; // nécéssaire pour trouver le précédent quand même
    }
    
    // on récupère le numéro d'ordre du morceau précédant le morceau à sa nouvelle place
    $ordre_ancre = getOrdreAutre($bdd, 'SELECT MAX(ordre) AS ordre_autre FROM morceaux WHERE ordre < ? AND audition_id = ?', $ordre_autre);
        
    if(isset($ordre_ancre)) { // on récupère l'id du morceau où l'ancre sera positionnée (sinon, pas besoin d'ancre puisque c'est qu'on est tout en haut)
        $_SESSION['id_ancre'] = getIdWithOrdre($bdd, $ordre_ancre);
    } else {
        unset($_SESSION['id_ancre']);
    }
    
    memMorceauChoisi();
}

header('Location: audition.php#ancre');
?>
