<?php
session_start();
include("connexion.php");

unset($_SESSION['erreuredition']);
unset($_SESSION['id_tmp']);
$_SESSION['edition_morceau'] = false;

header('Location: morceau.php');
?> 
