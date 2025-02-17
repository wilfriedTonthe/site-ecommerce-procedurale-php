<?php
session_start();
include 'head.php';

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    echo "Vous devez être connecté pour voir vos messages.";
    exit;
}

// Afficher les messages reçus par l'utilisateur connecté
$id_utilisateur = $_SESSION['utilisateur']['id_utilisateur'];
$messages = afficherMessagesRecus($id_utilisateur);

// Voir un message spécifique
if (isset($_POST['btn-ouvrir'])) {
    $message_id = $_POST['message_id'];
    $message_ouvert = voirMessage($message_id);
}

// Répondre à un message
if (isset($_POST['btn-repondre'])) {
    $id_expediteur = $_POST['id_expediteur'];
    $id_utilisateur = $_POST['id_utilisateur'];
    $message = $_POST['message'];
    repondreMessage($id_expediteur, $id_utilisateur, $message);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btn-repondre').click(function() {
            $('#reponse-form').toggle();
        });
    });
</script>

</body>
</html>
