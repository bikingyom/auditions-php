 <?php
    session_start();
    include("fonctions.php");
    cleanBaseEleve();
    unset($_SESSION['erreuredition']);
    unset($_SESSION['morceau_tmp']);
    unset($_SESSION['eleves_tmp']);
    unset($_SESSION['eleves_suppr']);
    header('Location: audition.php');
?>  