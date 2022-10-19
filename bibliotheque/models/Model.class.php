<?php

//class abstract qui ne peut tre instancier
abstract class Model
{   //accessiblepar toutes classes heritant de la classe Model
    private static $pdo;

    //accessible par l'intermédiare de la function setBdd()
    private static function setBdd()
    {   //connexion a la base de donnees
        self::$pdo = new PDO("mysql:host=localhost;dbname=bd_biblio_php;charset=utf8", "root", "");
        //Gestion des erreurs
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    //accessible uniquement par les classes filles
    protected function getBdd()
    {
        if (self::$pdo === null) {
            self::setBdd();
        }
        return self::$pdo;
    }
}