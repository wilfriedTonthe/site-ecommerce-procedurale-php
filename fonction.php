<?php
include './config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function connexionDB()
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if (!$conn) {
        die('Erreur de connexion' . mysqli_connect_error());
    }
    return $conn;
}

//correct
function RECHERCHER($mot_clé) {
    $conn = connexionDB();
    $sql = 'SELECT p.*, i.chemin as image 
            FROM Produit p 
            LEFT JOIN image i ON p.id_produit = i.id_produit 
            WHERE p.nom LIKE ? OR p.serie LIKE ?';
    
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error: ' . mysqli_error($conn)); 
    }
    $mot_cle_param = "%$mot_clé%";
    mysqli_stmt_bind_param($stmt, 'ss', $mot_cle_param, $mot_cle_param);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $produits_trouves = [];
     
    if (mysqli_num_rows($result) > 0) {
        while ($trouver = mysqli_fetch_assoc($result)) {
            $produits_trouves[] = $trouver;
        }
    }
    return $produits_trouves;
}


//correct

function Connexion($data)
{
    $courriel = $data['courriel'];
    $mot_de_passe = $data['mot_de_passe'];
    $conn = connexionDB();

    $sql = "SELECT * FROM utilisateur WHERE courriel = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $courriel);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);

    if ($resultat && mysqli_num_rows($resultat) > 0) {
        $utilisateur = mysqli_fetch_assoc($resultat);
         
        if ($utilisateur['statut'] !== 'actif') {
            return "Votre compte n'est pas actif. Veuillez contacter l'administrateur.";
        }
        if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            $id_utilisateur = $utilisateur['id_utilisateur'];
            $sqlRole = "SELECT r.description FROM role_utilisateur ru JOIN role r ON ru.id_role = r.id_role WHERE ru.id_utilisateur = ?";
            $stmtRole = mysqli_prepare($conn, $sqlRole);
            mysqli_stmt_bind_param($stmtRole, 'i', $id_utilisateur);
            mysqli_stmt_execute($stmtRole);
            $resultatRole = mysqli_stmt_get_result($stmtRole);

            if ($resultatRole && mysqli_num_rows($resultatRole) > 0) {
                $role = mysqli_fetch_assoc($resultatRole)['description'];
                $utilisateur['role'] = $role; 
                $_SESSION['utilisateur'] = $utilisateur; 

                    $id_utilisateur = $_SESSION['utilisateur']['id_utilisateur'];
                    charger_panier($id_utilisateur);
                
                return $utilisateur;
            } else {
                return "Erreur lors de la récupération du rôle";
            }
        } else {
            return "Mot de passe incorrect";
        }
    } else {
        return "Utilisateur non trouvé";
    }
}



//correcte
function Inscriptionclient($data) {
    global $conn;
    
    if (empty($data['nom']) || empty($data['prenom']) || empty($data['date_naissance']) ||
        empty($data['courriel']) || empty($data['mot_de_passe']) || empty($data['confirm_password']) || empty($data['Telephone'])) {
        return "Tous les champs sont requis.";
    }

    $nom = $data['nom'];
    $prenom = $data['prenom'];
    $date_naissance = $data['date_naissance'];
    $courriel = $data['courriel']; 
    $mot_de_passe = $data['mot_de_passe'];
    $confirm_password = $data['confirm_password'];
    $telephone = $data['Telephone'];
    
    $conn = connexionDB();
    $sql = "SELECT * FROM utilisateur WHERE courriel = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $courriel);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);
  
    if (mysqli_num_rows($resultat) > 0 ) {
        return "L'adresse e-mail existe déjà.";   
    } elseif ($mot_de_passe != $confirm_password) {
        return "Les mots de passe ne correspondent pas.";
    } else {
        $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur (nom, prenom, date_naissance, mot_de_passe, courriel, telephone, statut) VALUES (?, ?, ?, ?, ?, ?, 'actif')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssss', $nom, $prenom, $date_naissance, $mot_de_passe, $courriel, $telephone);
        $resultat = mysqli_stmt_execute($stmt);

        if ($resultat) {
            
            $id_utilisateur = mysqli_insert_id($conn);

            $sqlRole = "INSERT INTO role_utilisateur(id_role, id_utilisateur) VALUES(2, ?)";
            $stmtRole = mysqli_prepare($conn, $sqlRole);
            mysqli_stmt_bind_param($stmtRole, 'i', $id_utilisateur);
            $resultatRole = mysqli_stmt_execute($stmtRole);

            if ($resultatRole) {
                return "Inscription réussie.";
            } else {
                return "Erreur lors de l'assignation du rôle.";
            }
        } 
        else 
        {
            return "Erreur lors de l'inscription.";
        }
    }
}

function Inscriptionadmin($data) {

    if (empty($data['nom']) || empty($data['prenom']) || empty($data['date_naissance']) ||
        empty($data['courriel']) || empty($data['mot_de_passe']) || empty($data['confirm_password']) || empty($data['Telephone'])) {
        return "Tous les champs sont requis.";
    }
    $nom = $data['nom'];
    $prenom = $data['prenom'];
    $date_naissance = $data['date_naissance'];
    $courriel = $data['courriel']; 
    $mot_de_passe = $data['mot_de_passe'];
    $confirm_password = $data['confirm_password'];
    $telephone = $data['Telephone'];

    $conn = connexionDB();
    $sql = "SELECT * FROM utilisateur WHERE courriel = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $courriel);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultat) > 0 ) {
        return "L'adresse e-mail existe déjà.";   
    } elseif ($mot_de_passe != $confirm_password) {
        return "Les mots de passe ne correspondent pas.";
    } else {
        $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur (nom, prenom, date_naissance, mot_de_passe, courriel, telephone, statut) VALUES (?, ?, ?, ?, ?, ?, 'actif')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssss', $nom, $prenom, $date_naissance, $mot_de_passe, $courriel, $telephone);
        $resultat = mysqli_stmt_execute($stmt);

        if ($resultat) {
            $id_utilisateur = mysqli_insert_id($conn);
            $sqlRole = "INSERT INTO role_utilisateur (id_role, id_utilisateur) VALUES (1, ?)";
            $stmtRole = mysqli_prepare($conn, $sqlRole);
            mysqli_stmt_bind_param($stmtRole, 'i', $id_utilisateur);
            $resultatRole = mysqli_stmt_execute($stmtRole);

            if ($resultatRole) {
                return "Inscription réussie.";
            } else {
                return "Erreur lors de l'assignation du rôle.";
            }
        } else {
            return "Erreur lors de l'inscription.";
        }
    }
}


function Inscriptionsuperadmin($data) {
    global $conn;
    
    if (empty($data['nom']) || empty($data['prenom']) || empty($data['date_naissance']) ||
        empty($data['courriel']) || empty($data['mot_de_passe']) || empty($data['confirm_password']) || empty($data['Telephone'])) {
        return "Tous les champs sont requis.";
    }
    $nom = $data['nom'];
    $prenom = $data['prenom'];
    $date_naissance = $data['date_naissance'];
    $courriel = $data['courriel']; 
    $mot_de_passe = $data['mot_de_passe'];
    $confirm_password = $data['confirm_password'];
    $telephone = $data['Telephone'];
    $conn = connexionDB();
    $sql = "SELECT * FROM utilisateur WHERE courriel = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $courriel);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);
  
    if (mysqli_num_rows($resultat) > 0 ) {
        return "L'adresse e-mail existe déjà.";   
    } elseif ($mot_de_passe != $confirm_password) {
        return "Les mots de passe ne correspondent pas.";
    } else {
        $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateur (nom, prenom, date_naissance, mot_de_passe, courriel, telephone, statut) VALUES (?, ?, ?, ?, ?, ?, 'actif')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssss', $nom, $prenom, $date_naissance, $mot_de_passe, $courriel, $telephone);
        $resultat = mysqli_stmt_execute($stmt);
        if ($resultat) {
            
            $id_utilisateur = mysqli_insert_id($conn);
            $sqlRole = "INSERT INTO role_utilisateur(id_role, id_utilisateur) VALUES(3, ?)";
            $stmtRole = mysqli_prepare($conn, $sqlRole);
            mysqli_stmt_bind_param($stmtRole, 'i', $id_utilisateur);
            $resultatRole = mysqli_stmt_execute($stmtRole);

            if ($resultatRole) {
                return "Inscription réussie.";
            } else {
                return "Erreur lors de l'assignation du rôle.";
            }
        } else {
            return "Erreur lors de l'inscription.";
        }
    }
}


//correct
function ajouteriphone($data)
{
    $nom = $data['nom'];
    $prix_unitaire = $data['prix_unitaire'];
    $quantite = $data['quantite'];
    $serie = $data['serie'];
    $description = $data['description'];
    $categorie = "iphone";
    $conn = connexionDB();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO produit (nom, prix_unitaire, serie, descriptionp, quantite, categorie) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'sdssis', $nom, $prix_unitaire, $serie, $description, $quantite, $categorie);
    $resultat = mysqli_stmt_execute($stmt);

    if ($resultat === false) {
        die('Error executing statement: ' . mysqli_stmt_error($stmt));
    }
    $id_produit = mysqli_insert_id($conn);
    if ($id_produit) {
        if (uplaodImage($_FILES, $id_produit, $conn)) {
            $message = "Produit ajouté avec succès.";
        } else {
            $message = "Produit ajouté, mais l'image n'a pas pu être téléchargée.";
        }
    } else {
        $message = "Erreur lors de l'ajout du produit.";
    }
    mysqli_close($conn);
    return $message;
}


function ajouteripad($data)
{
    $nom = $data['nom'];
    $prix_unitaire = $data['prix_unitaire'];
    $quantite = $data['quantite'];
    $serie = $data['serie'];
    $description = $data['description'];
    $categorie="ipade";
    $conn = connexionDB();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO produit (nom, prix_unitaire, serie, descriptionp, quantite, categorie) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'sdssis', $nom, $prix_unitaire, $serie, $description, $quantite, $categorie);
    $resultat = mysqli_stmt_execute($stmt);

    if ($resultat === false) {
        die('Error executing statement: ' . mysqli_stmt_error($stmt));
    }

    $id_produit = mysqli_insert_id($conn);

    if ($id_produit) {
        if (uplaodImage($_FILES, $id_produit, $conn)) {
            $message = "Produit ajouté avec succès.";
        } else {
            $message = "Produit ajouté, mais l'image n'a pas pu être téléchargée.";
        }
    } else {
        $message = "Erreur lors de l'ajout du produit.";
    }

    mysqli_close($conn);
    return $message;
}

function ajoutermac($data)
{
    $nom = $data['nom'];
    $prix_unitaire = $data['prix_unitaire'];
    $quantite = $data['quantite'];
    $serie = $data['serie'];
    $description = $data['description'];
    $categorie="iphone";
    $conn = connexionDB();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "INSERT INTO produit (nom, prix_unitaire, serie, descriptionp, quantite, categorie) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'sdssis', $nom, $prix_unitaire, $serie, $description, $quantite, $categorie);
    $resultat = mysqli_stmt_execute($stmt);
    if ($resultat === false) {
        die('Error executing statement: ' . mysqli_stmt_error($stmt));
    }

    $id_produit = mysqli_insert_id($conn);

    if ($id_produit) {
        if (uplaodImage($_FILES, $id_produit, $conn)) {
            $message = "Produit ajouté avec succès.";
        } else {
            $message = "Produit ajouté, mais l'image n'a pas pu être téléchargée.";
        }
    } else {
        $message = "Erreur lors de l'ajout du produit.";
    }

    mysqli_close($conn);
    return $message;
}

//correct
function supprimerProduit($idProduit)
{
    $conn = connexionDB();
    $sql = "DELETE FROM Produit WHERE id_produit = ? "; 

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error: ' . mysqli_error($conn)); 
    }
    mysqli_stmt_bind_param($stmt,'i',$idProduit);
    return mysqli_stmt_execute($stmt);
}
//correct
function updateProduit($produit)
{
    $nom = $produit['nom'];
    $quantite = $produit['quantite'];
    $prix_unitaire = $produit['prix_unitaire'];
    $serie= $produit['serie'];
    $description = $produit['description'];
    $id_produit = $produit['id_produit'];

    $sql = "update Produit set nom = ?, prix_unitaire = ?, serie= ?, quantite = ?,descriptionp = ? where id_produit = ?";
    $conn = connexionDB();

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error: ' . mysqli_error($conn)); 
    }
    mysqli_stmt_bind_param($stmt, 'ssdisi', $nom, $serie, $prix_unitaire, $quantite, $description, $id_produit);
    return mysqli_stmt_execute($stmt);
}

//correct
function afficherProduit() {
    $conn = connexionDB(); 
    $sql = 'SELECT p.*, i.chemin as image 
            FROM Produit p 
            LEFT JOIN image i ON p.id_produit = i.id_produit';
    $resultats = mysqli_query($conn, $sql);
    $produits = []; 
    if (mysqli_num_rows($resultats) > 0) {
        while ($produit = mysqli_fetch_assoc($resultats)) { 
            $produits[] = $produit;
        }
    }
    return $produits; 
}

function afficheripad() {
    $conn = connexionDB(); 
    $sql = 'SELECT p.*, i.chemin as image 
            FROM Produit p 
            LEFT JOIN image i ON p.id_produit = i.id_produit where categorie="ipade"';
    $resultats = mysqli_query($conn, $sql);
    $produits = []; 
    if (mysqli_num_rows($resultats) > 0) {
        while ($produit = mysqli_fetch_assoc($resultats)) { 
            $produits[] = $produit;
        }
    }
    return $produits; 
}

function afficheriphone() {
    $conn = connexionDB(); 
    $sql = 'SELECT p.*, i.chemin as image 
            FROM Produit p 
            LEFT JOIN image i ON p.id_produit = i.id_produit where categorie="iphone"';
    $resultats = mysqli_query($conn, $sql);
    $produits = []; 
    if (mysqli_num_rows($resultats) > 0) {
        while ($produit = mysqli_fetch_assoc($resultats)) { 
            $produits[] = $produit;
        }
    }
    return $produits; 
}

function affichermac() {
    $conn = connexionDB(); 
    $sql = 'SELECT p.*, i.chemin as image 
            FROM Produit p 
            LEFT JOIN image i ON p.id_produit = i.id_produit where categorie="mac"';
    $resultats = mysqli_query($conn, $sql);
    $produits = []; 
    if (mysqli_num_rows($resultats) > 0) {
        while ($produit = mysqli_fetch_assoc($resultats)) { 
            $produits[] = $produit;
        }
    }
    return $produits; 
}


function afficherairpod() {
    $conn = connexionDB(); 
    $sql = 'SELECT p.*, i.chemin as image 
            FROM Produit p 
            LEFT JOIN image i ON p.id_produit = i.id_produit where categorie="airpod"';
    $resultats = mysqli_query($conn, $sql);
    $produits = []; 
    if (mysqli_num_rows($resultats) > 0) {
        while ($produit = mysqli_fetch_assoc($resultats)) { 
            $produits[] = $produit;
        }
    }
    return $produits; 
}
//coorrect 
function getProduitById($id_produit)
{
    $sql = 'SELECT p.*, i.chemin as image 
            FROM Produit p 
            LEFT JOIN image i ON p.id_produit = i.id_produit where p.id_produit=?';
    $conn = connexionDB();
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_produit);
    $resultat = mysqli_stmt_execute($stmt);
    if ($resultat) {
        $resultat = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($resultat) > 0) {
            $produit = mysqli_fetch_assoc($resultat);
            return $produit;
        }
        return false;
    } else {
        return false;
    }
}
//correct
function uplaodImage($data, $id_produit, $conn) 
{ 
    if (isset($data['image']) && $data['image']['error'] === UPLOAD_ERR_OK) { 
        $image_name = $data['image']['name']; 
        $image_destination = 'images/' . basename($image_name); 
        $from = $data['image']['tmp_name']; 
        $image_type = strtolower(pathinfo($image_destination, PATHINFO_EXTENSION)); 

        if (in_array($image_type, ['jpg', 'jpeg', 'png', 'gif'])) { 
            if (move_uploaded_file($from, $image_destination)) { 
                $image = ['chemin' => $image_destination, 'id_produit' => $id_produit]; 
                if (ajoutImage($image, $conn)) {
                    return true; 
                } else {
                    echo "Error inserting image path into the database.";
                    return false;
                }
            } else { 
                echo "Impossible de télécharger le fichier"; 
                return false; 
            } 
        } else { 
            echo "Impossible de télécharger le fichier avec l'extension $image_type"; 
            return false; 
        } 
    } 

    return false; 
} 

function ajoutImage($image, $conn)
{
    $sql = "INSERT INTO image (chemin, id_produit) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'si', $image['chemin'], $image['id_produit']);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return true;
    } else {
        return false;
    }
}



//correct
function AJOUTER_PANIER($id_produit, $quantite) {
    if (!isset($_SESSION['Paniers'])) {
        $_SESSION['Paniers'] = array();
    }

    if (isset($_SESSION['Paniers'][$id_produit])) {
        $_SESSION['Paniers'][$id_produit] +=$quantite;
    } else {
        $_SESSION['Paniers'][$id_produit] = $quantite;
    }
}

//correct
function AJOUTER_COMMANDE() {
    if (isset($_SESSION['utilisateur'])) {

        if (!isset($_SESSION['Paniers']) || empty($_SESSION['Paniers'])) {
            return "Le panier est vide.";
        }

        $id_utilisateur = $_SESSION['utilisateur']['id_utilisateur']; 
        $conn = connexionDB();
        $prix_total = CALCULER_PRIX_TOTAL_PANIER();
        $quantite_total = array_sum($_SESSION['Paniers']);
        $date_commande = date('Y-m-d H:i:s');

        $sql_insert_commande = "INSERT INTO commande (date_commande, id_utilisateur, quantite_total, prix_total) VALUES (?, ?, ?, ?)";
        $stmt_insert_commande = mysqli_prepare($conn, $sql_insert_commande);
        if (!$stmt_insert_commande) {
            die('Erreur de préparation de la requête : ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_insert_commande, 'sidd', $date_commande, $id_utilisateur, $quantite_total, $prix_total);
        mysqli_stmt_execute($stmt_insert_commande);

        $id_commande = mysqli_insert_id($conn);

        foreach ($_SESSION['Paniers'] as $id_produit => $quantite) {
            $sql_insert_produit_commande = "INSERT INTO produit_commande (id_produit, id_commande, nombre_article) VALUES (?, ?, ?)";
            $stmt_insert_produit_commande = mysqli_prepare($conn, $sql_insert_produit_commande);
            if (!$stmt_insert_produit_commande) {
                die('Erreur de préparation de la requête : ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_insert_produit_commande, 'iii', $id_produit, $id_commande, $quantite);
            mysqli_stmt_execute($stmt_insert_produit_commande);
            mysqli_stmt_close($stmt_insert_produit_commande);

            $sql_update_produit = "UPDATE produit SET quantite = quantite - ? WHERE id_produit = ?";
            $stmt_update_produit = mysqli_prepare($conn, $sql_update_produit);
            if (!$stmt_update_produit) {
                die('Erreur de préparation de la requête : ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_update_produit, 'ii', $quantite, $id_produit);
            mysqli_stmt_execute($stmt_update_produit);
            mysqli_stmt_close($stmt_update_produit);
        }
        unset($_SESSION['Paniers']);
        $sql_delete_panier = "DELETE FROM panier_sauvegarde WHERE id_utilisateur = ?";
        $stmt_delete_panier = mysqli_prepare($conn, $sql_delete_panier);
        if (!$stmt_delete_panier) {
            die('Erreur de préparation de la requête : ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_delete_panier, 'i', $id_utilisateur);
        mysqli_stmt_execute($stmt_delete_panier);
        mysqli_stmt_close($stmt_delete_panier);

        mysqli_close($conn);

        return "Commande ajoutée avec succès";
    } else {
        return "L'utilisateur n'est pas connecté";
    }
}


//correct

function CALCULER_PRIX_TOTAL_PANIER() {
    if (!isset($_SESSION['Paniers']) || empty($_SESSION['Paniers'])) {
        return 0;
    }

    $conn = connexionDB();
    $prix_total = 0;

    foreach ($_SESSION['Paniers'] as $id_produit => $quantite) {
        $sql = "SELECT prix_unitaire FROM produit WHERE id_produit = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            die('Erreur de préparation de la requête : ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, 'i', $id_produit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $prix_total += $row['prix_unitaire'] * $quantite;
    }

    return $prix_total;
}

//correcte
function charger_panier($id_utilisateur) {
    $conn = connexionDB();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT id_produit, quantite FROM panier_sauvegarde WHERE id_utilisateur = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_utilisateur);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);
    $_SESSION['Paniers'] = array();

    while ($row = mysqli_fetch_assoc($resultat)) {
        $id_produit = $row['id_produit'];
        $quantite = $row['quantite'];
        $_SESSION['Paniers'][$id_produit] = $quantite;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

//correct
function sauvegarder_panier($id_utilisateur, $panier) {
    $conn = connexionDB();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    foreach ($panier as $id_produit => $quantite) {
        $sql = "REPLACE INTO panier_sauvegarde (id_utilisateur, id_produit, quantite) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iii', $id_utilisateur, $id_produit, $quantite);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}

//correct
function Messagerie($email, $message) {
    if (isset($_SESSION['utilisateur'])) {
        $id_expediteur = $_SESSION['utilisateur']['id_utilisateur']; 
        $conn = connexionDB();
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        } 

        $sql = "SELECT id_utilisateur FROM utilisateur WHERE courriel = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            die("Erreur de préparation de la requête: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($resultat);
        $id_utilisateur = $row['id_utilisateur'];
        mysqli_stmt_close($stmt);

        $sql = "INSERT INTO messagerie (id_utilisateur, message, date_envoi, id_expediteur) VALUES (?, ?, NOW(), ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            die("Erreur de préparation de la requête: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, 'isi', $id_utilisateur, $message, $id_expediteur);

        if (mysqli_stmt_execute($stmt)) {
            echo "Message envoyé avec succès";
        } else {
            echo "Erreur: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo "Utilisateur non connecté.";
    }
}

//correct
function afficherMessagesRecus($id_utilisateur) {
    if (isset($_SESSION['utilisateur'])) {
        $conn = connexionDB();
        if (!$conn) {
            die("Erreur de connexion à la base de données : " . mysqli_connect_error());
        }
        
        $sql = "
            SELECT m.*, u.courriel AS expediteur_courriel
            FROM messagerie m
            JOIN utilisateur u ON m.id_expediteur = u.id_utilisateur
            WHERE m.id_utilisateur = ?
        ";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            die("Erreur lors de la préparation de la requête : " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $id_utilisateur);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $messages;
    } else {
        return [];
    }
}



//correct
function voirMessage($message_id) {
    if (isset($_SESSION['utilisateur'])) {
        $conn = connexionDB();
        
        $sql = "
            SELECT m.*, u.courriel 
            FROM messagerie m
            JOIN utilisateur u ON m.id_expediteur = u.id_utilisateur
            WHERE m.id_messagerie = ?
        ";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            die("Erreur lors de la préparation de la requête : " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, "i", $message_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $message = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $message;
    } else {
        return "vous n'êtes pas connecté";
    }
}


//correct
function repondreMessage($id_expediteur, $id_utilisateur, $message) {
    if (isset($_SESSION['utilisateur'])) {
        $id_expediteur = $_SESSION['utilisateur']['id_utilisateur']; 
        $conn = connexionDB(); 
        
        $sql = "INSERT INTO messagerie (id_utilisateur, message, date_envoi, id_expediteur) VALUES (?, ?, NOW(), ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            die("Erreur lors de la préparation de la requête : " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, "isi", $id_utilisateur, $message, $id_expediteur);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}



//commande
function getNombreDeCommandes() {
    $conn =connexionDB();
    $sql = "SELECT COUNT(*) as nombre_commandes FROM commande";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['nombre_commandes'];
    } else {
        return "Erreur: " . mysqli_error($conn);
    }
}


//correct
function getNombreDeProduit() {
    $conn =connexionDB();
    $sql = "SELECT COUNT(*) as nombre_produit FROM produit where quantite >=1";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['nombre_produit'];
    } else {
        return "Erreur: " . mysqli_error($conn);
    }
}

//correct
function getMontantTotalDesCommandes() {
    $conn =connexionDB();
    $sql = "SELECT SUM(prix_total) as montant_total FROM commande";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['montant_total'];
    } else {
        return "Erreur: " . mysqli_error($conn);
    }
}

//correct
function getNombreAdmin() {
 
    $conn = connexionDB();
    $sql = " SELECT COUNT(*) as nombre_admin FROM utilisateur u 
    JOIN role_utilisateur ru 
    ON u.id_utilisateur = ru.id_utilisateur
     JOIN role r ON ru.id_role = r.id_role
    WHERE r.description = 'Admin'
    ";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn); 
        return $row['nombre_admin'];
    } else {
        $error = "Erreur: " . mysqli_error($conn);
        mysqli_close($conn); 
        return $error;
    }
}

//correct
function getAllUsers(){

    $conn = connexionDB();
      $sql = "SELECT utilisateur.id_utilisateur, utilisateur.nom, utilisateur.prenom, utilisateur.courriel,utilisateur.statut, role.description
              FROM utilisateur
              JOIN role_utilisateur ON utilisateur.id_utilisateur = role_utilisateur.id_utilisateur
              JOIN role ON role_utilisateur.id_role = role.id_role
              WHERE role.description = 'Admin'";  

  $result = mysqli_query($conn, $sql);    
      if (mysqli_num_rows($result) > 0) {
          
          $admins = [];
          while($row = mysqli_fetch_assoc($result)) {
              $admins[] = $row;
          }
          return $admins;
        } else {
          return [];
        }
      
    }
    function getclient(){

        $conn = connexionDB();
          $sql = "SELECT utilisateur.id_utilisateur, utilisateur.nom, utilisateur.prenom, utilisateur.courriel,utilisateur.statut, role.description
                  FROM utilisateur
                  JOIN role_utilisateur ON utilisateur.id_utilisateur = role_utilisateur.id_utilisateur
                  JOIN role ON role_utilisateur.id_role = role.id_role
                  WHERE role.description = 'client'";  
    
      $result = mysqli_query($conn, $sql);    
          if (mysqli_num_rows($result) > 0) {
              
              $admins = [];
              while($row = mysqli_fetch_assoc($result)) {
                  $admins[] = $row;
              }
              return $admins;
            } else {
              return [];
            }
          
    }

    function getNombreclient() {
 
        $conn = connexionDB();
        $sql = " SELECT COUNT(*) as nombre_client FROM utilisateur u 
        JOIN role_utilisateur ru 
        ON u.id_utilisateur = ru.id_utilisateur
         JOIN role r ON ru.id_role = r.id_role
        WHERE r.description = 'client'
        ";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            mysqli_close($conn); 
            return $row['nombre_client'];
        } else {
            $error = "Erreur: " . mysqli_error($conn);
            mysqli_close($conn); 
            return $error;
        }
    }

    function changerStatutUtilisateur($id_utilisateur, $nouveau_statut) {
        $conn = connexionDB();
        if (!$conn) {
            die("Erreur de connexion à la base de données : " . mysqli_connect_error());
        }
        
        $sql = "UPDATE utilisateur SET statut = ? WHERE id_utilisateur = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            die("Erreur lors de la préparation de la requête : " . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, "si", $nouveau_statut, $id_utilisateur);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
    
    function getPromotions() {
        $conn = connexionDB();
        $sql = "SELECT p.nom, pr.pourcentage, pr.datedefin 
                FROM promotion pr
                JOIN produit p ON pr.id_produit = p.id_produit";
        
        $result = $conn->query($sql);
        if (!$result) {
            die("Query failed: " . $conn->error);
        }
    
        $promotions = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $promotions[] = $row;
            }
        }
        $conn->close();
        return $promotions;
    }


    function updateProductPrice($id_produit) {

        $conn = connexionDB();
        $sql = "SELECT p.prix_unitaire, pr.pourcentage, pr.datedefin 
        FROM produit p
        LEFT JOIN promotion pr ON p.id_produit = pr.id_produit
        WHERE p.id_produit = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_produit);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $prix_unitaire = $row['prix_unitaire'];
        $pourcentage = $row['pourcentage'];
        $datedefin = $row['datedefin'];
    } else {
            die("Produit non trouvé.");
        }
    
        mysqli_stmt_close($stmt);
    
        $sql = "SELECT pourcentage, datedefin, prix_initial FROM promotion WHERE id_produit = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            die("Failed to prepare statement: " . $conn->error);
        }
        
        mysqli_stmt_bind_param($stmt, "i", $id_produit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $pourcentage = $row['pourcentage'];
            $datedefin = $row['datedefin'];
            $prix_initial = $row['prix_initial'];
    
            if ($prix_initial === null) {
               
                $prix_initial = $prix_unitaire;
                $sql = "UPDATE promotion SET prix_initial = ? WHERE id_produit = ?";
                $update_stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($update_stmt, "di", $prix_initial, $id_produit);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }
            $current_date = date("Y-m-d");
            if ($current_date > $datedefin) {
                
                $new_price = $prix_initial;
            } else {
                
                $new_price = $prix_unitaire * (1 - $pourcentage / 100);
            }
    
            $update_sql = "UPDATE produit SET prix_unitaire = ? WHERE id_produit = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            if (!$update_stmt) {
                die("Failed to prepare update statement: " . $conn->error);
            }
            
            mysqli_stmt_bind_param($update_stmt, "di", $new_price, $id_produit);
            if (mysqli_stmt_execute($update_stmt)) {
                echo "Le prix du produit a été mis à jour avec succès.";
            } else {
                echo "Failed to update the product price: " . $conn->error;
            }
    
            mysqli_stmt_close($update_stmt);
        } else {
            echo "Promotion non trouvée pour ce produit.";
        }
    
        mysqli_stmt_close($stmt);
        $conn->close();
    }
    
    
    function ExpirPromotions() {
        $conn = connexionDB();
        $sql = "SELECT p.id_produit, p.prix_initial
                FROM promotion p
                WHERE p.datedefin < CURDATE() AND p.prix_initial IS NOT NULL";
        
        $result = $conn->query($sql);
        if (!$result) {
            die("Failed to execute query: " . $conn->error);
        }

        while ($row =mysqli_fetch_assoc($result)) {
            $id_produit = $row['id_produit'];
            $prix_initial = $row['prix_initial'];
    
            $update_sql = "UPDATE produit SET prix_unitaire = ? WHERE id_produit = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            if (!$update_stmt) {
                die("Failed to prepare update statement: " . $conn->error);
            }
    
            mysqli_stmt_bind_param($update_stmt, "di", $prix_initial, $id_produit);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
        }
    
        $conn->close();
    }
    
    function getRemainingTime($datedefin) {
        $now = new DateTime();
        $endDate = new DateTime($datedefin);
        if ($endDate > $now) {
            $interval = $now->diff($endDate);
            return $interval->format('%d jours %h heures %i minutes');
        } else {
            return "Promotion terminée";
        }
    }

?>
