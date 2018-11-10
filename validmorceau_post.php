<?php
session_start();
// TODO : Si $_SESSION est vide, la session a expiré, on redirige vers l'accueil

include("connexion.php");
include("fonctions.php");

$nvtitre = empty($_POST['titre']) ? '' : $_POST['titre'];
$nvcompositeur = empty($_POST['compositeur']) ? '' : $_POST['compositeur'];
$nvminutes = empty($_POST['minutes']) ? 0 : $_POST['minutes'];
$nvsecondes = empty($_POST['secondes']) ? 0 : $_POST['secondes'];
$nvchaises = empty($_POST['chaises']) ? 0 : $_POST['chaises'];
$nvpupitres = empty($_POST['pupitres']) ? 0 : $_POST['pupitres'];
$nvmateriel = empty($_POST['materiel']) ? '' : $_POST['materiel'];

// Mise à jour du morceau édité ou ajout du morceau créé dans la bdd
if($_SESSION['edition_morceau']) { // si on est en mode édition, c'est-à-dire si un morceau a bien été sélectionné
    // on met à jour la table morceaux
    $req = $bdd->prepare('UPDATE morceaux SET titre = :nvtitre, compositeur = :nvcompositeur, duree = MAKETIME(0, :nvminutes, :nvsecondes), chaises = :nvchaises, pupitres = :nvpupitres, materiel = :nvmateriel WHERE id = :idmorceau');
    $req->execute(array(
        'nvtitre' => $nvtitre,
        'nvcompositeur' => $nvcompositeur,
        'nvminutes' => $nvminutes,
        'nvsecondes' => $nvsecondes,
        'nvchaises' => $nvchaises,
        'nvpupitres' => $nvpupitres,
        'nvmateriel' => $nvmateriel,
        'idmorceau' => $_SESSION['morceau_tmp']['id']
    ));
    
    // on efface les élèves de ce morceau, pour pouvoir ajouter proprement les élèves_tmp ensuite
    $req = $bdd->prepare('DELETE FROM eleves WHERE morceau_id = ?');
    $req->execute(array($_SESSION['morceau_tmp']['id']));
}
else
{ // si c'est un nouveau morceau
    $req = $bdd->prepare('SELECT MAX(ordre) AS ordre_max FROM morceaux');
    $req->execute();
    $nvordre = 1 + $req->fetch()['ordre_max'];
    $req->closeCursor();
    
    $req = $bdd->prepare('INSERT INTO morceaux (ordre, audition_id, titre, compositeur, duree, chaises, pupitres, materiel) VALUES(:nvordre, :audid, :nvtitre, :nvcompositeur, MAKETIME(0, :nvminutes, :nvsecondes), :nvchaises, :nvpupitres, :nvmateriel)');
    $req->execute(array(
        'nvordre' => $nvordre,
        'audid' => $_SESSION['id_aud'],
        'nvtitre' => $nvtitre,
        'nvcompositeur' => $nvcompositeur,
        'nvminutes' => $nvminutes,
        'nvsecondes' => $nvsecondes,
        'nvchaises' => $nvchaises,
        'nvpupitres' => $nvpupitres,
        'nvmateriel' => $nvmateriel
    ));
    
    $req = $bdd->query('SELECT MAX(id) AS id_max FROM morceaux');
    $donnees = $req->fetch();
    $_SESSION['morceau_tmp']['id'] = $donnees['id_max'];
    $req->closeCursor();
}

// on (re)met les élèves dans la table eleves pour le morceau créé ou édité
foreach ($_SESSION['eleves_tmp'] as $eleve) {
    $req = $bdd->prepare('INSERT INTO eleves (morceau_id, eleve_id) VALUES(:idmo, :idel)');
    $req->execute(array(
        'idmo' => $_SESSION['morceau_tmp']['id'],
        'idel' => $eleve
    ));
}

// on fait le ménage dans la bdd s'il y a eu des élèves supprimés
cleanBaseEleve();

$_SESSION['displaySaveOk'] = true;
unset($_SESSION['edition_morceau']);
unset($_SESSION['morceau_tmp']);
unset($_SESSION['eleves_tmp']);
unset($_SESSION['eleves_suppr']);

header('Location: audition.php');
?> 
