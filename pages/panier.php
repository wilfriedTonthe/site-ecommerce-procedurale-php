<?php
session_start();

if (!isset($_SESSION['Paniers'])) {
    $_SESSION['Paniers'] = array();
}

include 'head.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_produit = $_GET['id'];

    if ($_GET['action'] == 'ajouter') {
        if (isset($_SESSION['Paniers'][$id_produit])) {
            $_SESSION['Paniers'][$id_produit]++;
        } else {
            $_SESSION['Paniers'][$id_produit] = 1;
        }
    } elseif ($_GET['action'] == 'diminuer') {
        if (isset($_SESSION['Paniers'][$id_produit])) {
            $_SESSION['Paniers'][$id_produit]--;
            if ($_SESSION['Paniers'][$id_produit] <= 0) {
                unset($_SESSION['Paniers'][$id_produit]);
            }
        }
    }

    header("Location: panier.php");
    exit;
}

$produits = afficherProduit();
$panier = isset($_SESSION['Paniers']) ? $_SESSION['Paniers'] : [];

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commde'])) {
    $message = AJOUTER_COMMANDE();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <?php include 'navbar.php'; ?>

    <h1 class="text-center text-primary mt-5">Votre Panier</h1>
    <?php if (empty($panier)) { ?>
        <p class="text-center">Votre panier est vide.</p>
    <?php } else { ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Photo</th>
                    <th scope="col">Produit</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Total</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalGeneral = 0;
                foreach ($panier as $id => $quantite) {
                    foreach ($produits as $produit) {
                        if ($produit['id_produit'] == $id) {
                            $total = $produit['prix_unitaire'] * $quantite;
                            $totalGeneral += $total;
                            $imagePath = !empty($produit['image']) ? $produit['image'] : 'images/default.jpg'; 
                            ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>" style="width: 100px; height: auto;"></td>
                                <td><?= htmlspecialchars($produit['nom']); ?></td>
                                <td><?= $quantite; ?></td>
                                <td><?= htmlspecialchars($produit['prix_unitaire']); ?>€</td>
                                <td><?= $total; ?>€</td>
                                <td>
                                    <a href="panier.php?action=ajouter&id=<?= $produit['id_produit']; ?>" class="btn btn-success btn-sm">+</a>
                                    <a href="panier.php?action=diminuer&id=<?= $produit['id_produit']; ?>" class="btn btn-danger btn-sm">-</a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total Général:</strong></td>
                    <td colspan="2"><?= $totalGeneral; ?>€</td>
                </tr>
            </tbody>
        </table>
    <?php } ?>
    <div class="text-center">
        <a href="acceuil.php" class="btn btn-primary">Continuer vos achats</a>
        <?php if (isset($_SESSION['utilisateur'])) { ?>
            <form method="post" id="commande-form">
                <input type="hidden" name="commde" value="1">
                <div id="paypal-button-container"></div>
            </form>
        <?php } else { ?>
            <a href="compteUtilisateur.php" class="btn btn-success">Connectez-vous pour passer la commande</a>
        <?php } ?>
    </div>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
<script src="https://www.paypal.com/sdk/js?client-id=AQjuMtcddFQZfU-TMcb5kTId3uu_vAJWlEXtpG3U94kTUoiFR85LI8pIO8jWeWjjlng70i3dewPVK7_e&components=buttons"></script>
<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?= $totalGeneral; ?>' 
                }
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            alert('Transaction completed');
            document.getElementById('commande-form').submit(); 
        });
    }
}).render('#paypal-button-container'); 
</script>
