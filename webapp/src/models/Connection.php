<?php

class Connexion
{
    protected function getConnexion()
    {
        $db = new \PDO('mysql:host=localhost;dbname=tempopourprojet;charset=utf8', 'root', '',array( 
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ,
        PDO::ATTR_EMULATE_PREPARES=>false)); // contre l'injection de 2ème niveau
        return $db;
    }
}



?>