<?php
    session_start();
    // TODO : Si $_SESSION est vide, la session a expiré, on redirige vers l'accueil

    include("connexion.php");

    $_SESSION['morcceau_tmp']['titre'] = $_POST['titre'];
    $_SESSION['morcceau_tmp']['compositeur'] = $_POST['compositeur'];
    $_SESSION['morcceau_tmp']['minutes'] = $_POST['minutes'];
    $_SESSION['morcceau_tmp']['secondes'] = $_POST['secondes'];
    $_SESSION['morcceau_tmp']['chaises'] = $_POST['chaises'];
    $_SESSION['morcceau_tmp']['pupitres'] = $_POST['pupitres'];
    $_SESSION['morcceau_tmp']['materiel'] = $_POST['materiel'];

    header('Location: eleves.php');
?>
