<?php
include_once './head.php';

$message = "";
if (isset($_POST['btn-ajout'])) {
    $resultat = Connexion($_POST);
    if (is_array($resultat)) {
        $message = "Connexion réussie ! Bienvenue " . $resultat['nom'];
        
        if ($resultat['role'] == 'Admin' && $resultat['statut'] == 'actif' ) {
            header("Location: afficherproduitAdmin.php");
            exit();
        } if ($resultat['role'] == 'SuperAdmin' && $resultat['statut'] == 'actif' ) {
            header("Location: tableau_de_bord.php");
            exit();
        }
        elseif ($resultat['role'] == 'client' && $resultat['statut'] == 'actif') {
            header("Location: affichargeUtilisateur.php");
            exit();
        }
        
    } else {
        $message = $resultat;
    }
}
?>
<body class="container mt-5">
<h3>Compte Utilisateur</h3>

<?php if (!empty($message)) : ?>
    <div class="alert alert-info">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<form method="post">
 
  <div class="mb-3">
    <label for="courriel">Courriel</label>
    <input type="mail" class="form-control" placeholder="Entrez votre courriel" id="courriel" name="courriel"> 
  </div>

<div class="mb-3">
    <label for="mot_de_passe" class="form-label">Mot de passe</label>
    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe">
</div>

  <input type="submit" class="btn btn-primary" name="btn-ajout" value="Connexion">

</form>

</body>
</html>
