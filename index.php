<?php require_once 'inc/header.inc.php'; ?>
<?php 

if($_POST){
    debug($_POST);
    //quelques contrôles sur le formulaire
    if( empty($_POST['nom']) || empty($_POST['prenom']) ){
        $error .= "<div class='alert alert-danger'> Vous devez renseigner un Nom ET un Prénom !</div>";
    }
    $r = execute_requete( " SELECT email FROM membre WHERE email = '$_POST[email]' " );
    //ici $r est un objet (PDOStatement)

    if( $r->rowCount() >= 1){//si le resultat est supérieur ou égal a 1 c'est que le pseudo est déja attribué car on aura trouvé une correspondance dans la table membre et renverra donc une ligne de résultat a notre requete

        $error .= '<div class="alert alert-danger">Cette email fait déjà parti de nos membre ! </div>';
    }
    if( empty( $_POST['mdp'])){
        $error .= "<div class='alert alert-danger'> Vous devez renseigner un Mot de passe !</div>";
    }
    if( empty( $_POST['telephone'])){
        $error .= "<div class='alert alert-danger'> Vous devez renseigner un numéro de Téléphone !</div>";
    }

    else{//ENCODAGE DES VALEURS INPUT

        foreach( $_POST as $indice => $valeur ){

            $_POST[$indice] = htmlentities( addslashes( $valeur ) );
        }
        //cryptage du mot de passe
        $_POST['mdp'] = password_hash($_POST['mdp'],PASSWORD_DEFAULT);// !!!ATTENYION!!! lors du cryptage la string du mdp est plus grande, dans la structure de la table 'membre', il faut veiller a donner un nombre de caractère max conséquent (255 caractère recommandé par la doc php)
    }
    //gestion du fichier photo
    debug($_FILES);
    if( !empty( $_FILES['photo']['name']) ){
        
        $photo_name = $_FILES['photo']['name'];
        debug($photo_name);
        debug( pathinfo($photo_name));
        $photo_extention = pathinfo($photo_name)['extension'];
        debug( $photo_extention );
        
        if( $photo_extention == 'jpeg' || $photo_extention == 'jpg' || $photo_extention == 'png'){
            //ici, renomme la photo
            $nom_photo = $_POST['prenom'] . ' - ' . $_POST['nom'] . ' - ' . $_FILES['photo']['name'];
                //debug($nom_photo);

            // récuperation du chemin pour récupérer la photo (0 inserer en BDD)
            $photo_bdd = URL . "photos/$nom_photo";
                //debug($photo_bdd);

            //Définition du lieu où l'on souhaite enregistrer le fichier 'physique' de la photo
            $photo_dossier = "$_SERVER[DOCUMENT_ROOT]/PHP/projet_whatsapp/photos/$nom_photo";
            //$_SERVER[DOCUMENT_ROOT] <=> C:/xampp/htdocs
            //$_SERVER[DOCUMENT_ROOT] <=> retourne la racine sur le serveur
                // debug($photo_dossier);

            //Enregistrement de la photo au bon endroit, ici dans le dossier photo de notre server
            copy( $_FILES['photo']['tmp_name'], $photo_dossier );
            // copy(arg1, arg2 ):
                //arg1 : chemin du fichier source
                //arg2 : adresse de destination
        }
        else{ 
            $error .= "<div>Veuillez choisir un fichier au format jpeg ou png</div>";
        }
    }
    //INSERTION MEMBRE BDD
    if( empty( $error ) ){
        execute_requete(  "INSERT INTO membre ( nom,prenom, email, mdp, telephone, sexe, photo) VALUES(
                        '$_POST[nom]',
                        '$_POST[prenom]',
                        '$_POST[email]',
                        '$_POST[mdp]',
                        '$_POST[telephone]',
                        '$_POST[sexe]',
                        '$photo_bdd'
                        ) 
        ");
    }
}

//----------------------------------------------------
?>

<h1>Inscription</h1>
<h4>Déjà inscrit? <a href="connexion.php">Connecte toi!</a></h4>

<?= $error; ?>
<form action="" method="post" enctype="multipart/form-data">

    <label for="">Nom *</label><br>
    <input type="text" name="nom"class="form-control"><br><br>

    <label for="">Prénom *</label><br>
    <input type="text" name="prenom"class="form-control"><br><br>

    <label for="">email</label><br>
    <input type="email" name="email"class="form-control"><br><br>

    <label for="">Mot de passe *</label><br>
    <input type="password" name="mdp"class="form-control"><br><br>

    <label for="">Téléphone *</label><br>
    <input type="text" name="telephone"class="form-control"><br><br>

    <label for="">Civilité</label><br>
    <input type="radio" name="sexe" value="f">Femme<br>
    <input type="radio" name="sexe" value="m">Homme<br>
    <input type="radio" name="sexe" value="w">Whatever<br><br>

    <label for="">Photo</label><br>
    <input type="file" name="photo"class="form-control"><br><br>

    <input type="submit" value="Valider"class="form-control"><br>
</form>

<?php require_once 'inc/footer.inc.php'; ?>
