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

$produits = affichermac();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Page d'Accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        #background-video {
   width: 100vw;
   height: 100vh;
   object-fit: cover;
   position: fixed;
   left: 0;
   right: 0;
   top: 0;
   bottom: 0;
   z-index: -1;
}
h1, h2 {
   color: white;
   font-family: Trebuchet MS;
   font-weight: bold; text-align: center;
}

h1 {
   font-size: 6rem;
   margin-top: 30vh;
}

h2 {
   font-size: 3rem;
}
@media (max-width: 750px) {
   #background-video {
      display: none;
   } 
   body {
      background: url("https://assets.codepen.io/6093409/river.jpg") no-repeat;
      background-size: cover;
   }
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
        h1{
            color: aqua;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>


    <section class="product-video-section">

            <h1>Découvrez nos nouveaux produits</h1>
            <h2 style="color: aqua">La technologie de demain, aujourd'hui.</h2>
        
            <video id="background-video" autoplay loop muted poster="https://assets.codepen.io/6093409/river.jpg">
            <source src="mac.mp4" type="video/mp4">
            </video>
            
    </section>

    <section class="product-cards-section">
        <div class="card">
            <img src="mac1.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Caméras avancées</h5>
                <p class="card-text">Photographes. Cinéastes. Visionnaires.</p>
            </div>
        </div>
        <div class="card">
            <img src="mac2.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Puces signées Apple</h5>
                <p class="card-text">Le genre de vitesse qui se sent.</p>
            </div>
        </div>
        <div class="card">
            <img src="mac3.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Autonomie</h5>
                <p class="card-text">Longue vie à la batterie.</p>
            </div>
        </div>
        <div class="card">
            <img src="mac4.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Innovation</h5>
                <p class="card-text">Irrésistiblement réinventé.</p>
            </div>
        </div>
        <div class="card">
            <img src="mac5.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Personnalisation</h5>
                <p class="card-text">Une touche de vous un peu partout.</p>
            </div>
        </div>
        <div class="card">
            <img src="mac7.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Personnalisation</h5>
                <p class="card-text">Une touche de vous un peu partout.</p>
            </div>
        </div>
        <div class="card">
            <img src="mac6.jpg" class="card-img-top" alt="...">
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
