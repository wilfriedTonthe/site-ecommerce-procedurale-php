<?php
include 'head.php';

$produits = afficherProduit();

if (isset($_POST['btn-ajout'])) {
    $produits = RECHERCHER($_POST['mot_cle']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Liste produit</title>
</head>

<body class="container">
    <h1 class="text-center text-primary mt-5">Liste produit</h1>
    <div class="d-flex justify-content-end mb-3">
        <a class="btn btn-success me-2" href="formulaire.php"><i class="bi bi-plus-circle"></i> Ajouter Produit</a>
        <a class="btn btn-secondary" href="creationAdmin.php"><i class="bi bi-person-plus"></i> Ajouter Utilisateur</a>
    </div>
    <form method="post" class="mb-3">
        <div class="form-group">
            <input type="text" class="form-control" id="mot_cle" name="mot_cle" placeholder="Rechercher un produit">
        </div>
        <input type="submit" class="btn btn-primary mt-2" name="btn-ajout" value="Rechercher">
    </form>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">Photo</th>
                <th scope="col">Quantité</th>
                <th scope="col">Prix unitaire</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cmp = 1;
            foreach ($produits as $produit) { 
                $imagePath = !empty($produit['image']) ? $produit['image'] : 'images/default.jpg'; 
            ?>
                <tr>
                    <th scope="row"><?php echo $cmp++; ?></th>
                    <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>" style="width: 100px; height: auto;"></td>
                    <td><?php echo htmlspecialchars($produit['quantite']); ?></td>
                    <td><?php echo htmlspecialchars($produit['prix_unitaire']); ?></td>
                    <td>
                        <a class="btn btn-info" href="voirproduit.php?id=<?php echo htmlspecialchars($produit['id_produit']); ?>"><i class="bi bi-eye"></i></a>
                        <a class="btn btn-primary" href="modifieriformationappareil.php?id=<?php echo htmlspecialchars($produit['id_produit']); ?>"><i class="bi bi-pencil-square"></i></a>
                        <a class="btn btn-danger" href="supprimerproduitAdmin.php?id=<?php echo htmlspecialchars($produit['id_produit']); ?>"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>
