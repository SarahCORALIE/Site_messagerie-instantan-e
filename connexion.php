<?php require_once 'inc/header.inc.php'; ?>

<?php 
//DECONNEXION :(AVANT LA REDIRECTION SINON script de la deconnexion ne sera pas lu par l'interpreteur php)

if(isset($_GET['action']) && $_GET['action'] == 'deconnection'){//si il existe une 'action' dans l'url et que cette 'action' = 'deconnexion', alors on détruit la session
    session_destroy();//destruction du fichier de session
}
//restriction si l'internaute est connecté
if(userConnect()){
    header('location:profil.php');//redirection vers la page de profil
    exit();
} 

if($_POST){

    debug($_POST);
    //comparaison du MDP
    $r = execute_requete( " SELECT * FROM membre WHERE email = '$_POST[email]' " );
    
    if( $r->rowCount() >= 1 ){// si il y a correspondance dans la table 'membre' c'est donc que le pseudo existe
        
        
        //on récupère les données pour les exploiter
        $membre = $r->fetch( PDO::FETCH_ASSOC );
       debug($membre);

        //Vérificaion du mdp
        if(password_verify($_POST['mdp'], $membre['mdp'] ) ){
            //oui le mdp correspond
            //password_verify( arg1, arg2 ) : permet de comparer une chaîne de caractere a une cha^ne crypter
                //arg1 : le mot de passe (ici posté par l'internaute)
                //arg2 : la chaine cryptés (par la fontion password_hash()), ici enregistrée en BDD

                //echo 'MDP OK connexion réussie!';
          
            // Ici on va renseigner les infos de l'internaute dans le fichier session

            foreach( $membre as $index => $valeur){

                $_SESSION['membre'][$index] = $valeur;   
            }
            //debug($_SESSION);
            
            //REDIRECTION VERS LA PAGES PROFIL
            header('location:profil.php');
        }
        else{//sinon c que le mdp ne correspond pas
        $error .= '<div class="alert alert-danger">Erreur mot de passe </div>';            
        }
    }
    else{
        $error .= '<div class="alert alert-danger">Erreur email </div>';    
    }
}
//----------------------------------------
?>

<h1>CONNEXION</h1>
<?php echo $error; //affichage du message d'erreur ?>


<form action="" method="post">

    <label for="">Email</label><br>
    <input type="text" name="email" id="" class="form-control" placeholder="Votre pseudo"><br>

    <label for="">Mot de passe</label><br>
    <input type="password" name="mdp" id="" class="form-control" placeholder="Votre mot de passe"><br>

    <input type="submit" value="Connexion" class="btn btn-secondary">

</form>

<?php require_once 'inc/footer.inc.php'; ?>