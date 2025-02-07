<?php
include 'head.php';

if (isset($_POST['btn-ajout'])) {
    $produits = RECHERCHER($_POST['mot_cle']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary mb-5" style="position:sticky;top:0;width:100%;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="vitesse" height="30">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="acceuil.php">Home</a>
                </li>
                <?php if (!isset($_SESSION['utilisateur'])) { ?>
                    <li class="nav-item"><a href="compteUtilisateur.php" class="nav-link">Connexion</a></li>
                    <li class="nav-item"><a href="Creation_utilisateur.php" class="nav-link">Inscription</a></li>
                    <li class="nav-item"><a href="iphone.php" class="nav-link">Iphone</a></li>
                    <li class="nav-item"><a href="ipad.php" class="nav-link">Ipad</a></li>
                    <li class="nav-item"><a href="mac.php" class="nav-link">Mac</a></li>
                    <li class="nav-item"><a href="airpod.php" class="nav-link">Airpods</a></li>
                <?php } else { ?>
                    <li class="nav-item"><a class="nav-link" href="./profil.php">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="deconnection.php">Déconnexion</a></li>
                    <li class="nav-item"><a href="iphone.php" class="nav-link">Iphone</a></li>
                    <li class="nav-item"><a href="ipad.php" class="nav-link">Ipad</a></li>
                    <li class="nav-item"><a href="mac.php" class="nav-link">Mac</a></li>
                    <li class="nav-item"><a href="airpod.php" class="nav-link">Airpods</a></li>
                <?php } ?>
                <li class="nav-item">
                    <a href="panier.php" class="btn btn-primary" aria-disabled="true">
                        <i class="bi bi-cart4"></i>
                        <span id="cart-count">
                            <?php echo (isset($_SESSION['Paniers'])) ? count($_SESSION['Paniers']) : 0; ?>
                        </span>
                    </a>
                    <a href="messageri.php" class="nav-link">
                        <i class="fas fa-comments"></i>
                        <span>Messagerie</span>
                    </a>
                    
                </li>
            </ul>
            <form class="d-flex" role="search" method="POST">
                <input class="form-control me-2" type="search" placeholder="Search" id="mot_cle" name="mot_cle" aria-label="Search">
                <input type="submit" class="btn btn-outline-success" name="btn-ajout" value="Rechercher">
            </form>
            <select id="lang">
                <option value="fr">Français</option>
                <option value="en">English</option>
                <option value="es">Español</option>
            </select>
        </div>
    </div>
</nav>

<script>
    async function translateText(text, targetLanguage) {
        try {
            console.log("Translating text:", text, "to language:", targetLanguage);
            const response = await fetch("https://libretranslate.com/translate", {
                method: "POST",
                body: JSON.stringify({
                    q: text,
                    source: "auto",
                    target: targetLanguage,
                    format: "text"
                }),
                headers: { "Content-Type": "application/json" }
            });
            const result = await response.json();
            console.log("Translation result:", result);
            return result;
        } catch (error) {
            console.error("Error translating text:", error);
            return null;
        }
    }

    $(document).ready(function() {
        $('#lang').change(async function() {
            var lang = $(this).val();
            console.log("Language changed to:", lang);
            $('#content').children().each(async function() {
                var element = $(this);
                var text = element.html();
                console.log("Original text:", text);
                const translatedText = await translateText(text, lang);
                if (translatedText && translatedText.translatedText) {
                    console.log("Translated text:", translatedText.translatedText);
                    element.html(translatedText.translatedText);
                } else {
                    console.error("Translation failed for text:", text);
                }
            });
        });
    });
</script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>
</html>
