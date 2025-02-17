<?php
include 'head.php';

if (isset($_GET['id'])) {
    $id_produit = $_GET['id'];
    if (is_numeric($id_produit)) {
        $resultat = supprimerProduit($id_produit);
        if ($resultat) {
            ?>
            <script>
                window.location.href = "afficherproduitAdmin.php";
            </script>

        <?php }

    }
} else {
    ?>
    <script>
        window.location.href = "afficherproduitAdmin.php";
    </script>

    <?php
}


?>