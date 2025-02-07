<?php
include 'head.php';

if (isset($_GET['id'])) {
    $id_produit = $_GET['id'];
    if (is_numeric($id_produit)) {
        $produit = getProduitById($id_produit);  
        if (isset($_POST['btn-modif'])) {
            $_POST['id_produit'] = $id_produit;
            $resultat = updateProduit($_POST);
            if ($resultat) {
                ?>
                <script>
                    window.location.href = "tableau_de_bord.php";
                </script>
            <?php }
        }
    } else {
        ?>
        <script>
            window.location.href = "tableau_de_bord.php";
        </script>
        <?php
    }
} else {
    ?>
    <script>
        window.location.href = "tableau_de_bord.php";
    </script>
    <?php
}
?>

<body class="container mt-5">
    <form method="post" enctype="multipart/form-data">
        <h1>APPLE SITE</h1>
        <h1 class="text-center">Modifier le Produit</h1>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($produit['nom']); ?>">
        </div>
        <div class="mb-3">
            <label for="prix_unitaire" class="form-label">Prix Unitaire</label>
            <input type="text" class="form-control" id="prix_unitaire" name="prix_unitaire" value="<?= htmlspecialchars($produit['prix_unitaire']); ?>">
        </div>
        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" min="0" class="form-control" id="quantite" name="quantite" value="<?= htmlspecialchars($produit['quantite']); ?>">
        </div>
        <div class="form-floating mb-3">
            <textarea class="form-control" placeholder="Entrer le série de l'appareil" id="serie" name="serie"><?= htmlspecialchars($produit['serie']); ?></textarea>
            <label for="serie">Série de l'appareil</label>
        </div>
        <div class="form-floating mb-3">
            <textarea class="form-control" placeholder="Entrer une description" id="description" name="description"><?= htmlspecialchars($produit['descriptionp']); ?></textarea>
            <label for="description">Description</label>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image">
            <?php if (!empty($produit['image'])): ?>
                <p>Image actuelle : <img src="<?= htmlspecialchars($produit['image']); ?>" alt="Image du produit" style="max-width: 200px;"></p>
            <?php endif; ?>
        </div>
        <input type="submit" class="btn btn-primary" name="btn-modif" value="Modifier le produit">
    </form>
</body>
</html>
