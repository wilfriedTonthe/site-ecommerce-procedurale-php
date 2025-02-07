
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Messagerie</title>
    <style>
        .messagerie-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .btn-icon {
            display: inline-flex;
            align-items: center;
        }
        .btn-icon i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="messagerie-container">
            <h2>Envoyer un message</h2>
            <?php
            include 'head.php';
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['envoyer'])) {
                $id_utilisateur = $_POST['corriel'];
                $message = $_POST['message'];
                Messagerie($id_utilisateur, $message);
            }
            ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="id_utilisateur" class="form-label">ID Utilisateur</label>
                    <input type="text" class="form-control" id="id_utilisateur" name="corriel" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" name="envoyer" class="btn btn-primary btn-icon">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
                <button type="reset" class="btn btn-secondary btn-icon">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </form>
        </div>
    </div>
</body>
</html>
