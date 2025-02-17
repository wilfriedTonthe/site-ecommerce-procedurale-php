<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    background:#e8ebed;
}
 
.form-control:focus {
    box-shadow: none;
    border-color: #BA68C8
}
 
.profile-button {
    background: rgb(99, 39, 120);
    box-shadow: none;
    border: none
}
 
.profile-button:hover {
    background: #682773
}
 
.profile-button:focus {
    background: #682773;
    box-shadow: none
}
 
.profile-button:active {
    background: #682773;
    box-shadow: none
}
 
.back:hover {
    color: #682773;
    cursor: pointer
}
 
.labels {
    font-size: 11px
}
 
.add-experience:hover {
    background: #BA68C8;
    color: #fff;
    cursor: pointer;
    border: solid 1px #BA68C8
}
    </style>
    <form method="post">
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
           
        </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right"> information sur Profil</h4>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">Nom</label><input type="text" class="form-control" placeholder="nom" name="nom" value="<?=$user['nom'];?>"></div>
                    <div class="col-md-6"><label class="labels">Prenom</label><input type="text" class="form-control" value="<?=$user['prenom'];?>" name="prenom" placeholder="prenom"></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Telephone</label><input type="text" class="form-control" name="nom" name="telephone" placeholder="telephone" value="<?=$user['telephone'];?>"></div>
                    <div class="col-md-12"><label class="labels">Date de naissance</label><input type="date" class="form-control" name="date_naissance" placeholder="Date de naissance" value="<?=$user['date_naissance'];?>"></div>
                    <div class="col-md-12"><label class="labels">E-mail</label><input type="courriel" class="form-control" placeholder="E-mail" name="courriel" value="<?=$user['courriel'];?>"></div>
                   
                </div>
                <div class="mt-5 text-center">
                <input type="submit" class="btn btn-danger profile-button" name="btn_enregister" value="enregistrer">
                </div>
 
            </div>
        </div>
    </div>
</div>
</form>
 
</body>
</html>