<?php
include_once'./head.php';

$message = "";
if (isset($_POST['btn-ajout'])) {
    $message = Inscriptionadmin($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'un compte utilisateur Admin</title>
</head>
<body class="container mt-5">
    <h3>Création d'un compte utilisateur</h3>
    <form method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
        </div>
        <div class="mb-3">
            <label for="courriel" class="form-label">Courriel</label>
            <input type="email" class="form-control" placeholder="Entrez votre courriel" id="courriel" name="courriel" required>
        </div>
        <div class="mb-3">
            <label for="Telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" placeholder="Entrez votre téléphone" id="Telephone" name="Telephone" required>
        </div>
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmez votre mot de passe</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div> 
        <input type="submit" class="btn btn-primary" name="btn-ajout" value="Enregistrer">
    </form>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
