<?php
//Création ou ouverture de sesssion
session_start();
//PREMIERE LIGNE DE CODE , se positionne en haut et en premier avnat tout traitement php

//-------------------------------------------------------
//connection à la base de donnée BDD :
$pdo = new PDO('mysql:host=localhost;dbname=whatsapp', 'root', '',
                array(  PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING,
                PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES UTF8"
                ) );

//var_dump( $pdo );

//--------------------------------------------------------
//Définition d'une constante : 
define('URL','http://localhost/PHP/projet_whatsapp/').

//--------------------------------------------------------
//Definition de variable : 
$content = '';
$error = '';
$content2 = '';
$content3= '';
$content4= '';


//--------------------------------------------------------
//inclusion des fonctions via fonction.inc.php 
require_once 'fonction.inc.php';



