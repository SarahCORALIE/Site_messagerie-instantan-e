<?php 
//fonction de débugage : debug() permet d'effectuer un print amelioré
function debug( $arg){

    echo '<div style="background:#fda500; z-index:1000; padding: 15px;">';

        $trace = debug_backtrace();
        //debug_backtrace() : fonction de php qui retourne un array contenant des infos elle permet ici de récupérer le document et la ligne où est executé le print_r
         echo "Debug demander dans le fichier : " . $trace[0]['file'] . " à la ligne " . $trace[0]['line'];

        print '<pre>';
            print_r( $arg);
        print '</pre>';


    echo '</div>';
}
//-------------------------------------------------------
// fonction execute_requete() : 
function execute_requete ( $req ){

    global $pdo;

    $pdostatement = $pdo->query( $req );

    return $pdostatement;
}
//execute_requete(" SELECT * FROM membre ");

//fonction userConnect : si l'internaute est connecté
function userConnect(){

    if( !isset( $_SESSION['membre'] ) ){//si la session membre n'existe pas => pas connecté ()

        return false;
    }
    else{//SINON : c que la $_session['membre] existe => on est connecté

        return true;
    }
}

//------------------------------------------------
//------------------------------------------------
function adminConnect(){ //SI l'internaute est connecté ET qu'il est administrateur

	if( userConnect() && $_SESSION['membre']['statut'] == 1 ){ //SI l'internaute est connecté ET qu'il est admin (dnc qu'il a un statut égal à 1 !)

		return true;
	}
	else{
		return false;
	}
}
