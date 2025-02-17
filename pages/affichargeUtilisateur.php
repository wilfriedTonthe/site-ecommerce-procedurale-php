<?php
include 'head.php';

if (isset($_POST['btn-ajout'])) {
    $produits = RECHERCHER($_POST['mot_cle']);
}

if (isset($_GET['action']) && $_GET['action'] == 'ajouter' && isset($_GET['id'])) {
    $id_produit = $_GET['id'];
    if (isset($_SESSION['Paniers'][$id_produit])) {
        $_SESSION['Paniers'][$id_produit]++;
    } else {
        $_SESSION['Paniers'][$id_produit] = 1;
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$produits = afficherProduit();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Page d'Accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .icons-section {
            display: flex;
            justify-content: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        .icons-section .icon {
            display: flex;
            align-items: center;
            margin: 0 15px;
            font-size: 1.2rem;
        }
        .icons-section .icon i {
            font-size: 24px;
            margin-right: 10px;
        }
        .hero-section {
            position: relative;
            height: 100vh;
            background: url('images/hero-background.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .hero-section h1 {
            font-size: 4rem;
            margin-bottom: 0.5rem;
        }
        .hero-section p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
        .product-video-section {
            text-align: center;
            margin: 40px 0;
        }
        .product-video-section video {
            max-width: 100%;
            height: auto;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }
        .section-title {
            text-align: center;
            margin: 40px 0;
        }
        .product-cards-section {
            display: flex;
            justify-content: space-around;
            align-items: center;
            overflow-x: auto;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
        .product-cards-section .card {
            flex: 0 0 auto;
            width: 20%;
            margin: 10px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="icons-section">
        <div class="icon">
            <i class="fas fa-comments"></i>
            <span>Messagerie</span>
        </div>
        
        <div class="icon">
            <i class="fas fa-list"></i>
            <span>Mes Commandes</span>
        </div>
    </section>

    <section class="hero-section">
        <div class="container">
            <h1>Découvrez nos nouveaux produits</h1>
            <p>La technologie de demain, aujourd'hui.</p>
        </div>
    </section>

    <section class="product-video-section">
        <video autoplay muted loop>
            <source src="xlarge_2x.mp4" type="video/mp4">
            Votre navigateur ne supporte pas la balise vidéo.
        </video>
    </section>

    <section class="product-cards-section">
        <div class="card">
            <img src="image1.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Caméras avancées</h5>
                <p class="card-text">Photographes. Cinéastes. Visionnaires.</p>
            </div>
        </div>
        <div class="card">
            <img src="image2.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Puces signées Apple</h5>
                <p class="card-text">Le genre de vitesse qui se sent.</p>
            </div>
        </div>
        <div class="card">
            <img src="image3.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Autonomie</h5>
                <p class="card-text">Longue vie à la batterie.</p>
            </div>
        </div>
        <div class="card">
            <img src="image4.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Innovation</h5>
                <p class="card-text">Irrésistiblement réinventé.</p>
            </div>
        </div>
        <div class="card">
            <img src="image5.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Personnalisation</h5>
                <p class="card-text">Une touche de vous un peu partout.</p>
            </div>
        </div>
    </section>

    <div class="container">
        <h2 class="section-title" id="products">Nos Produits</h2>
        <div class="row">
            <?php foreach ($produits as $produit) {
                $imagePath = !empty($produit['image']) ? $produit['image'] : 'images/default.jpg';
            ?>
            <div class="col-md-4">
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                    <h5><?php echo htmlspecialchars($produit['nom']); ?></h5>
                    <p><?php echo htmlspecialchars($produit['serie']); ?></p>
                    <p><?php echo htmlspecialchars($produit['descriptionp']); ?></p>
                    <p><strong><?php echo htmlspecialchars($produit['prix_unitaire']); ?> $</strong></p>
                    <a href="?action=ajouter&id=<?php echo $produit['id_produit']; ?>" class="btn btn-primary"><i class="bi bi-cart4"></i> Ajouter</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
