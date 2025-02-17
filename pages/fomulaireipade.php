<?php
include_once'./head.php';

$message = "";
if (isset($_POST['btn-ajout'])) {
    $message = ajouteripad($_POST);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body class="container mt-5">
<form method="post" enctype="multipart/form-data">
    <h1>APPLE SITE</h1>
    <h1 class="text-center">Ajouter Un Ipad</h1>
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom">
    </div>
    <div class="mb-3">
        <label for="prix_unitaire" class="form-label">Prix Unitaire</label>
        <input type="text" class="form-control" id="prix_unitaire" name="prix_unitaire">
    </div>
    <div class="mb-3">
        <label for="quantite" class="form-label">Quantite</label>
        <input type="number" min="0" class="form-control" id="quantite" name="quantite">
    </div>
    <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Entrer le série de l'appareille" id="serie" name="serie"></textarea>
        <label for="serie">Série de l'appareille</label>
    </div>
    <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Entrer une description" id="description" name="description"></textarea>
        <label for="description">Description</label>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control" id="image" name="image">
    </div>
    <input type="submit" class="btn btn-primary" name="btn-ajout" value="Ajouter un produit">
    <a href="tableau_de_bord.php" class="btn btn-primary">retour</a>
</form>
   
</body>
</html>