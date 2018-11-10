<?php
session_start();
include("connexion.php");

/* A VOIR POUR PROTECTION DATE CONTRE INJECTION SQL

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}*/

$nvdate = empty($_POST['date']) ? '' : $_POST['date'];
$nvheure = empty($_POST['heure']) ? '' : $_POST['heure'];
$nvdateheure = $nvdate . ' ' . $nvheure;
$nvlieu = empty($_POST['lieu']) ? '' : $_POST['lieu'];

$req = $bdd->prepare('INSERT INTO auditions (date_audition, lieu) VALUES(:nvdateheure, :nvlieu)');
$req->execute(array(
    'nvdateheure' => $nvdateheure,
    'nvlieu' => $nvlieu
));

$req = $bdd->query('SELECT MAX(id) AS id_max FROM auditions');
$donnees = $req->fetch();
$_SESSION['id_aud'] = $donnees['id_max'];
$req->closeCursor();

header('Location: audition.php');
?> 
 
