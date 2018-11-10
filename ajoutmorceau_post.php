<?php
session_start();
include("connexion.php");

$_SESSION['morcceau_tmp']['titre'] = '';
$_SESSION['morcceau_tmp']['compositeur'] = '';
$_SESSION['morcceau_tmp']['minutes'] = 0;
$_SESSION['morcceau_tmp']['secondes'] = 0;
$_SESSION['morcceau_tmp']['chaises'] = 0;
$_SESSION['morcceau_tmp']['pupitres'] = 0;
$_SESSION['morcceau_tmp']['materiel'] = '';
$_SESSION['edition_morceau'] = false;
$_SESSION['eleves_tmp'] = [];
$_SESSION['edition_morceau'] = false;

header('Location: morceau.php');
?> 
