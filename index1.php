<?php

var_dump($_FILES);
if (isset($_FILES['image']) && $_FILES['image']['error'] ===
UPLOAD_ERR_OK) {
    $image_name = $_FILES['image']['name'];
    $from = $_FILES['image']['tmp_name'];

    // Construit le chemin de destination
    $image_destination = 'images/' . basename($image_name);
    // Vérifie le type de fichier
    $image_type = strtolower(pathinfo($image_destination,
    PATHINFO_EXTENSION));
    if (in_array($image_type, ['jpg', 'jpeg', 'png', 'gif'])) {

        move_uploaded_file($from,$image_destination);
    } else {
   echo "erreur du type de fichier ($image_type)";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <form method="post" enctype="multipart/form-data">
        <input type="file" name="image"><br>
        <input type="submit" value="Télecharger image" name="btn-image">

   </form>
</body>
</html>