<?php
session_start();
include_once'./head.php';


if (isset($_SESSION['utilisateur'])) {
    $id_utilisateur = $_SESSION['utilisateur']['id_utilisateur'];
    if (isset($_SESSION['Paniers']) && !empty($_SESSION['Paniers'])) {
        sauvegarder_panier($id_utilisateur, $_SESSION['Paniers']);
    }
}

session_destroy();
header("Location: acceuil.php");
?>
