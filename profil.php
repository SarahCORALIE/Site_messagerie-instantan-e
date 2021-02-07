<?php require_once 'inc/header.inc.php'; ?>

<?php
//RESTRICTION D'ACCES A LA PAGE PREOFIL
    if( !userConnect() ){//si l'internaute n'est pas connecté
        //redirection vers la page connexion.php
        header('location:connexion.php');
        exit(); //exit() : permet de terminer la lecture du script courant a cette endroitprécis, on quitte la page
    }
//debug($_SESSION);
//--------------------------------------------
//Création de variable nulle pour préremplir le formulaire message
$value_expe ='';
$value_dest ='';
$value_contenu ='';

//debug($_GET);
//on récupère les infos de l'url pour les intégrer au formulaire message
if(isset( $_GET['saisie']) && $_GET['saisie'] == 'destinataire'){
    $id_destinataire = $_GET['id_destinataire'];
 
//------------------------------------------------------
//RECUPERATION DES INFOS DESTINATAIRE

    $r = execute_requete( "SELECT * FROM membre WHERE id_membre = $id_destinataire");
    $destinataire = $r->fetch( PDO::FETCH_ASSOC );
    //debug($destinataire);
    
    //Préparation des infos pour affichage
    $prenom_destinataire = ucfirst( $destinataire['prenom'] );
    $nom_destinataire =ucfirst( $destinataire['nom'] );
}
if($_POST){
    //debug($_POST);
    if(isset( $_POST['id_expediteur']) && isset($_POST['id_expediteur'] ) ){

        //on verifie que le destinataire est un membre
        $r = execute_requete( "SELECT * FROM membre WHERE id_membre = $_POST[id_destinataire]");
        if($r->rowCount()<1){
            
            $error .= "<br><div class='alert alert-danger'>Ce destinataire n'est pas membre !</div><br>";
        }
        if( empty( $_POST['contenu'] ) ){
            $error .= "<br><div class='alert alert-danger'>Votre message est vide</div><br>";
        }
        else{
            //traitement du contenu 
            $contenu =  htmlentities( addslashes($_POST['contenu'] ) );

            // //INSERTION EN BDD
            // execute_requete(" INSERT INTO messages (id_expediteur, id_destinataire, contenu, date_envoie) VALUES (
            //                     '$_POST[id_expediteur]',
            //                     '$_POST[id_destinataire]',
            //                     '$_POST[contenu]',
            //                     NOW()
            //                 )"
            //             );
        } 
    }
}
//------------------------------------------------
//AFFICHAGE PROFIL :    
//ici on récupère le mail de la personne connectée 
$prenom = ucfirst( $_SESSION['membre']['prenom'] );
$photo_profil = $_SESSION['membre']['photo'];
$id_profil = $_SESSION['membre']['id_membre'];

//affichage des infos profil
$content .= "<h3>Bienvenue $prenom !</h3>";
$content .= "<div class='row justify-content-around'>";
    $content .= "<div class='col-md-6'>";
        $content .= "<img src='$photo_profil' width='80'>";
    $content .= "</div>";

    $content .= "<div class='col-md-6'>";   
        
        $content .= '<p>Votre nom : ' . $_SESSION['membre']['nom'] . '</p>';
        $content .= '<p>Votre email : ' . $_SESSION['membre']['email'] . '</p>';
    $content .= "</div>";
$content .= "</div>";

//AFFICHAGE DES CONTACTS
//on recupère toutes les infos de la table 'membre'
$r = execute_requete( "SELECT * FROM membre");
$membre_all = $r->fetchAll( PDO::FETCH_ASSOC );
//debug($membre_all);
$content2 .= " <h3>Vos Contacts</h3>";
$content2 .= '<div class="list-group">';
    foreach( $membre_all as $membre ){
      //debug($membre);
        foreach($membre as $index => $valeur){
            if( $index == 'nom'){
                $nom_contact = $valeur;
            }
            if( $index == 'prenom'){
                $prenom_contact = $valeur;
            }
            if($index == 'id_membre'){
                $id_contact = $valeur;
            } 
            if ($index == 'photo'){
                $photo_contact = $valeur;
            }                   
        }  
        $contact = $prenom_contact . ' ' . $nom_contact; 

        $content2 .= "<a href='?saisie=destinataire&id_destinataire=$id_contact' class='list-group-item list-group-item-action'>
                    <img src='$photo_contact' width='20'>
                    $contact
                </a>";                
    }
$content2 .= "</div>";
    //debug($contact);

//  AFFICHAGE DES MESSAGES RECUS / CONVERSATIONS

$r2 = execute_requete(" SELECT m.nom, m.prenom, mes.contenu, mes.id_expediteur, mes.id_destinataire, mes.date_envoie FROM messages as mes, membre as m  
            WHERE id_destinataire = $id_profil 
            AND mes.id_destinataire = m.id_membre  
            ORDER BY date_envoie DESC ");
$messages_recus = $r2->fetchAll( PDO::FETCH_ASSOC );
//debug($messages_recus);
    $prenom_expediteur ='';
    $nom_expediteur ='';
    $message_recu='';
    $date_envoie ='';

$content3 .= "<h3>Messages reçus</h3><br>";
$content3 .= "<table class='table table-bordered'>";

    $content3 .= "<tr>";
        $content3 .= "<th>EXPEDITEUR</th>";
        $content3 .= "<th>MESSAGE</th>";
        $content3 .= "<th>DATE DE RECEPTION</th>";
   $content3 .= "</tr>";
foreach( $messages_recus as $message){
      
   foreach( $message as $index => $valeur){
        if($index == 'prenom'){
            $prenom_expediteur = $valeur;
        }
        if($index =='nom'){
            $nom_expediteur = $valeur;
        }
        if($index =='contenu'){
            $message_recu =$valeur;
        }
        if($index =='date_envoie'){
            $date_envoie = $valeur;
        }
    }
    $content3 .= "<tr>";
        $content3 .= "<td> $prenom_expediteur       $nom_expediteur </td>";
        $content3 .= "<td> $message_recu </td>";
        $content3 .= "<td> $date_envoie </td>";
    $content3 .= "</tr>";    
}
$content3 .= "</table>";

//  AFFICHAGE DES MESSAGES envoyé / CONVERSATIONS

$r2 = execute_requete(" SELECT m.nom, m.prenom, mes.contenu, mes.id_expediteur, mes.id_destinataire, mes.date_envoie FROM messages as mes, membre as m  
            WHERE id_expediteur = $id_profil 
            AND mes.id_destinataire = m.id_membre  
            ORDER BY date_envoie DESC ");
$messages_recus = $r2->fetchAll( PDO::FETCH_ASSOC );
    //debug($messages_recus);
    $prenom_expediteur ='';
    $nom_expediteur ='';
    $message_recu='';
    $date_envoie ='';

$content3 .= "<h3>Messages envoyé</h3><br>";
$content3 .= "<table class='table table-bordered'>";

    $content3 .= "<tr>";
        $content3 .= "<th>DESTINATAIRE</th>";
        $content3 .= "<th>MESSAGE</th>";
        $content3 .= "<th>DATE DE RECEPTION</th>";
   $content3 .= "</tr>";
foreach( $messages_recus as $message){
    
    
   foreach( $message as $index => $valeur){
        if($index == 'prenom'){
            $prenom_expediteur = $valeur;
        }
        if($index =='nom'){
            $nom_expediteur = $valeur;
        }
        if($index =='contenu'){
            $message_recu =$valeur;
        }
        if($index =='date_envoie'){
            $date_envoie = $valeur;
        }
    }
    $content3 .= "<tr>";
        $content3 .= "<td> $prenom_expediteur       $nom_expediteur </td>";
        $content3 .= "<td> $message_recu </td>";
        $content3 .= "<td> $date_envoie </td>";
    $content3 .= "</tr>";    
}
$content3 .= "</table>";

//AFFICHACHE SOUS FORME DE DISCUTIONS
// $r3 = execute_requete(" SELECT m.nom, m.prenom, mes.contenu, mes.id_expediteur, mes.id_destinataire, mes.date_envoie FROM messages as mes, membre as m  
//             WHERE id_expediteur = $id_profil 
//             AND id_destinataire = $id_profil
//             OR mes.id_destinataire = m.id_membre  
//             ORDER BY date_envoie DESC ");
// $discutions = $r3->fetchAll( PDO::FETCH_ASSOC );
// debug($discutions);

// $recive_messages = ''; 
// $send_messages='';
// foreach( $discutions as $envoie){
//     foreach($envoie as $index => $valeur){
//         if($index == 'id_expediteur'){
//             if($valeur == $_GET['id_destinataire']){
//                 $recive_messages .= $envoie['contenu'];
//                 $content4 .= "<div class='justi> $recive_messages </div>" 
//             }
//             if( $valeur == $_SESSION['membre']['id_membre']){
//                 $send_messages .= $envoie['contenu'];
//             }
//         }        
//     }   
// }
// debug($recive_messages);
//    debug($send_messages);



//------------------------------------------------------
?>
<h1>PROFIL</h1>
<?= $content; ?>
<?= $error; ?>

<div class="row align-items-center">
    <div class="col-md-6">
       <?= $content2; ?>
    </div>

<?php if(isset( $_GET['saisie']) && $_GET['saisie'] == 'destinataire') : ?>

    <div class="col-md-6">
    <h3>Envoyer un message à : <?= $prenom_destinataire .' '. $nom_destinataire; ?></h3>
        <form action="" method="post">

            <input type="hidden" name="id_expediteur" value="<?= $id_profil; ?>" class="form-control"><br>

            <input type="hidden" name="id_destinataire" value="<?= $id_destinataire;?>" class="form-control"><br>

            <label for="">Mon message</label><br>
            <textarea name="contenu" id="" cols="30" rows="10"class="form-control" value="<?= $value_contenu;?>"></textarea><br>

            <input type="submit" value="Envoyer"class="form-control"><br><br>
 
        </form>
    </div>
<?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-10">

            <?= $content3; ?>

        </div>
    
    </div>
</div>


<?= $content4; ?>


<?php require_once 'inc/footer.inc.php'; ?>
