<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'head.php';

$produits = afficherProduit();
$orderCount = getNombreDeCommandes();
$revenue = getMontantTotalDesCommandes();
$productCount = getNombreDeProduit();
$userCount = getNombreAdmin();
$users = getAllUsers();
$users1=getclient();
$promotions = getPromotions();
$clientCount=getNombreclient();

if (!isset($_SESSION['utilisateur'])) {
    echo "Vous devez être connecté pour voir vos messages.";
    exit;
}

if ($_SESSION['utilisateur']['role'] !== 'Admin') {
    echo "Accès refusé. Vous n'avez pas les permissions nécessaires pour accéder à cette page.";
    exit;
}

$id_utilisateur = $_SESSION['utilisateur']['id_utilisateur'];
$messages = afficherMessagesRecus($id_utilisateur);


if (isset($_POST['btn-ouvrir'])) {
    $message_id = $_POST['message_id'];
    $message_ouvert = voirMessage($message_id);
}


if (isset($_POST['btn-repondre'])) {
    $id_expediteur = $_POST['id_expediteur'];
    $id_utilisateur = $_POST['id_utilisateur'];
    $message = $_POST['message'];
    repondreMessage($id_expediteur, $id_utilisateur, $message);
}

if (isset($_POST['btn-activer'])) {
    changerStatutUtilisateur($_POST['id_utilisateur'], 'actif');
} elseif (isset($_POST['btn-desactiver'])) {
    changerStatutUtilisateur($_POST['id_utilisateur'], 'inactif');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_promotion'])) {
        
        $id_produit = $_POST['id_produit'];
        $pourcentage = $_POST['pourcentage'];
        $datedefin = $_POST['datedefin'];
        $conn = connexionDB();
        $sql = "REPLACE INTO promotion (id_produit, pourcentage, datedefin) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $id_produit, $pourcentage, $datedefin);
        
        if ($stmt->execute()) {
            $id_produit = $_POST['id_produit'];
        
        
            updateProductPrice($id_produit);
            echo "Promotion ajoutée avec succès.";
        } else {
            echo "Erreur lors de l'ajout de la promotion: " . $stmt->error;
        }
        
        $stmt->close();
        $conn->close();
    }
    
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AfriDelices - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px;
            text-align: left;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }

        .sidebar ul li a:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .card {
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            text-align: center;
            background-color: #f8f9fa;
        }

        .card h3 {
            font-size: 2em;
            margin: 0;
        }

        .card p {
            font-size: 1.2em;
            margin: 0;
        }
    </style>
</head>
<body>
    <?php
    
    if (isset($_SESSION['messages'])) {
        foreach ($_SESSION['messages'] as $type => $messages) {
            foreach ($messages as $message) {
                echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                        $message
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
        }
        unset($_SESSION['messages']);
    }
    ?>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column" id="adminMenu">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (!isset($_GET['section']) || $_GET['section'] == 'dashboard') ? 'active' : ''; ?>" href="?section=dashboard">
                                <i class="fas fa-tachometer-alt"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['section']) && $_GET['section'] == 'users') ? 'active' : ''; ?>" href="?section=users">
                                <i class="fas fa-users"></i> Utilisateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['section']) && $_GET['section'] == 'products') ? 'active' : ''; ?>" href="?section=products">
                                <i class="fas fa-list"></i> Produits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['section']) && $_GET['section'] == 'orders') ? 'active' : ''; ?>" href="?section=orders">
                                <i class="fas fa-comments"></i> Messagerie
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['section']) && $_GET['section'] == 'promotions') ? 'active' : ''; ?>" href="?section=promotions">
                                <i class="fas fa-percent"></i> Promotions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($_GET['section']) && $_GET['section'] == 'reviews') ? 'active' : ''; ?>" href="?section=reviews">
                            <i class="fas fa-users"></i> client
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="deconnection.php">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div id="dashboard" style="display: <?php echo (!isset($_GET['section']) || $_GET['section'] == 'dashboard') ? 'block' : 'none'; ?>;">
                    <h1 class="h2 mb-4">Tableau de bord</h1>
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Utilisateurs</h5>
                                    <p class="card-text display-4"><?php echo $userCount; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Produits</h5>
                                    <p class="card-text display-4"><?php echo $productCount; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Commandes</h5>
                                    <p class="card-text display-4"><?php echo $orderCount; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Revenus</h5>
                                    <p class="card-text display-4"><?php echo $revenue; ?>$</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Clients</h5>
                                    <p class="card-text display-4"><?php echo $clientCount; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="products" style="display: <?php echo (isset($_GET['section']) && $_GET['section'] == 'products') ? 'block' : 'none'; ?>;">
                    <h2 class="mb-4">Gestion des Produits</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <a class="btn btn-success me-2" href="formulaire.php"><i class="bi bi-plus-circle"></i> Ajouter Produit</a>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Liste des Produits</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
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
                                <tbody id="productsTableBody">
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
                        </div>
                    </div>
                </div>
                <div id="users" style="display: <?php echo (isset($_GET['section']) && $_GET['section'] == 'users') ? 'block' : 'none'; ?>;">
                    <h2 class="mb-4">Gestion des Utilisateurs</h2>
                   
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Liste des Utilisateurs</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                   
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($users as $user) {
                                        echo "<tr>
                                                <td>{$user['id_utilisateur']}</td>
                                                <td>{$user['nom']}</td>
                                                <td>{$user['prenom']}</td>
                                                <td>{$user['courriel']}</td>
                                                <td>{$user['description']}</td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="orders" style="display: <?php echo (isset($_GET['section']) && $_GET['section'] == 'orders') ? 'block' : 'none'; ?>;">
                    <h2 class="mb-4">Messagerie</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <a class="btn btn-primary" href="messageri.php"><i class="bi bi-envelope"></i> Envoyer un message</a>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h2>Messages reçus</h2>
                            <ul class="list-group">
                                <?php
                                if (is_array($messages) && !empty($messages)) {
                                    foreach ($messages as $message) {
                                        ?>
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span><?php echo htmlspecialchars($message['message']); ?></span>
                                                <span><?php echo htmlspecialchars($message['expediteur_courriel']); ?></span>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="message_id" value="<?php echo htmlspecialchars($message['id_messagerie']); ?>">
                                                    <button type="submit" name="btn-ouvrir" class="btn btn-primary">Ouvrir</button>
                                                </form>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                } else {
                                    echo "<p>Aucun message trouvé.</p>";
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="col-md-8">
                            <?php if (isset($message_ouvert)) { ?>
                                <div class="mt-5">
                                    <h3>Message de <?php echo htmlspecialchars($message_ouvert['courriel']); ?></h3>
                                    <p><?php echo htmlspecialchars($message_ouvert['message']); ?></p>
                                    <button class="btn btn-success" id="btn-repondre">Répondre</button>
                                </div>
                            <?php } ?>

                            <div id="reponse-form" class="mt-5" style="display: none;">
                                <h3>Répondre au message</h3>
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="reponse">Votre réponse</label>
                                        <textarea class="form-control" id="reponse" name="message" rows="3"></textarea>
                                    </div>
                                    <input type="hidden" name="id_expediteur" value="<?php echo isset($message_ouvert) ? htmlspecialchars($message_ouvert['id_expediteur']) : ''; ?>">
                                    <input type="hidden" name="id_utilisateur" value="<?php echo $id_utilisateur; ?>">
                                    <button type="submit" name="btn-repondre" class="btn btn-primary">Envoyer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="promotions" style="display: <?php echo (isset($_GET['section']) && $_GET['section'] == 'promotions') ? 'block' : 'none'; ?>;">
                    <h2 class="mb-4">Promotions en cours</h2>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success" id="btn-nouvelle-promotion"><i class="bi bi-plus-circle"></i> Nouvelle Promotion</button>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Liste des Promotions</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom du Produit</th>
                                        <th>Pourcentage</th>
                                        <th>Date de Fin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($promotions as $promotion) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($promotion['nom']) . "</td>
                                                <td>" . htmlspecialchars($promotion['pourcentage']) . "%</td>
                                                <td>" . htmlspecialchars($promotion['datedefin']) . "</td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="nouvelle-promotion" style="display: none;">
                    <h2 class="mb-4">Nouvelle Promotion</h2>
                    <form method="post">
                        <div class="mb-3">
                            <label for="id_produit" class="form-label">Produit</label>
                            <select class="form-select" id="id_produit" name="id_produit" required>
                                <?php
                                foreach ($produits as $produit) {
                                    echo "<option value='" . htmlspecialchars($produit['id_produit']) . "'>" . htmlspecialchars($produit['nom']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pourcentage" class="form-label">Pourcentage</label>
                            <input type="number" class="form-control" id="pourcentage" name="pourcentage" min="0" max="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="datedefin" class="form-label">Date de Fin</label>
                            <input type="date" class="form-control" id="datedefin" name="datedefin" required>
                        </div>
                        <button type="submit" name="add_promotion" class="btn btn-primary">Ajouter Promotion</button>
                    </form>
                </div>

                <div id="reviews" style="display: <?php echo (isset($_GET['section']) && $_GET['section'] == 'reviews') ? 'block' : 'none'; ?>;">
                    <h2 class="mb-4">Gestion des Utilisateurs</h2>
                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Liste des Utilisateurs</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Statut</th>
                                        <th>Rôle</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($users1 as $user) { 
                                        echo "<tr>
                                                <td>{$user['id_utilisateur']}</td>
                                                <td>{$user['nom']}</td>
                                                <td>{$user['prenom']}</td>
                                                <td>{$user['courriel']}</td>
                                                <td>{$user['statut']}</td>
                                                <td>{$user['description']}</td>
                                                <td>
                                                    <form method='POST' class='d-inline'>
                                                        <input type='hidden' name='id_utilisateur' value='{$user['id_utilisateur']}'>
                                                        <button type='submit' name='btn-activer' class='btn btn-sm btn-primary'>Activer</button>
                                                    </form>
                                                    <form method='POST' class='d-inline'>
                                                        <input type='hidden' name='id_utilisateur' value='{$user['id_utilisateur']}'>
                                                        <button type='submit' name='btn-desactiver' class='btn btn-sm btn-danger'>Désactiver</button>
                                                    </form>
                                                </td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#btn-nouvelle-promotion').click(function() {
                $('#promotions').hide();
                $('#modifier-prix').hide();
                $('#nouvelle-promotion').show();
            });
            $('#btn-repondre').click(function() {
                $('#reponse-form').toggle();
            });
           
        });
       
    </script>
</body>
</html>
