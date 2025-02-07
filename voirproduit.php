<?php
include_once'./head.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Produit non trouvé.";
    exit;
}

$id_produit = $_GET['id'];
$produit = getProduitById($id_produit);

if (!$produit) {
    echo "Produit non trouvé.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            width: 100%;
            max-width: 400px; 
            height: auto;
            margin: 0 auto; 
            display: block;
        }
        .card {
            max-width: 800px; 
            margin: 0 auto;
        }
        .card-body {
            text-align: center;
        }
    </style>
</head>
<body class="container mt-5">
    <h1 class="text-center">Détails du produit</h1>
    <div class="card mb-4">
    <img src="<?= htmlspecialchars($produit['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($produit['nom']); ?>">

        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($produit['nom']); ?></h5>
            <p class="card-text"><strong>Prix:</strong> <?= htmlspecialchars($produit['prix_unitaire']); ?>€</p>
            <p class="card-text"><strong>Quantité:</strong> <?= htmlspecialchars($produit['quantite']); ?></p>
            
            <a href="modifier_produit.php?id=<?= $produit['id_produit']; ?>" class="btn btn-primary">Modifier</a>
            <form method="post" class="d-inline-block" action="produit.php">
                <input type="hidden" name="id_produit" value="<?= htmlspecialchars($produit['id_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="submit" name="supprimer" class="btn btn-danger" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
            </form>
        </div>
    </div>
    <a href="tableau_de_bord.php" class="btn btn-secondary">Retour à la gestion des produits</a>
</body>
</html>