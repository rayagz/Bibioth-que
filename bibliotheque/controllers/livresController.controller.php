<?php

require_once "models/LivreManager.class.php";

class livresController
{
    private $livreManager;

    public function __construct()
    {
        //instanciation de l'objet LivreManager
        $this->livreManager = new LivreManager();
        //Ajout de livre
        $this->livreManager->chargementLivres();
    }

    //affichage des livres
    public function afficherLivres()
    {
        $livres = $this->livreManager->getLivres();
        require "views/livres.view.php";
    }
    //creation fonction affichage d'un livre 
    public function afficherLivre($id)
    {
        $livre = $this->livreManager->getLivreById($id);
        require "views/afficherLivre.view.php";
    }
    //creation fonction affichage d'un livre 
    public function ajoutLivre()
    {
        require "views/ajoutLivre.view.php";
    }

    public function ajoutLivreValidation()
    {
        //fonction de recuperation des information de l'image
        $file = $_FILES['image'];
        //Lieu d'hebergement des images
        $repertoire = "public/images/";
        $nomImageAjoute = $this->ajoutImage($file, $repertoire);
        $this->livreManager->ajoutLivreBd($_POST['titre'], $_POST['nbPages'], $nomImageAjoute);

        $_SESSION['alert'] = [
            "type" => "success",
            "msg" => "Ajout Réalisé avec succès"
        ];

        header('Location: ' . URL . "livres");
    }

    //fonction de suppression d'un livre
    public function suppressionLivre($id)
    {
        $nomImage = $this->livreManager->getLivreById($id)->getImage();
        unlink("public/images/" . $nomImage);
        $this->livreManager->suppressionLivreBD($id);

        $_SESSION['alert'] = [
            "type" => "success",
            "msg" => "Supression effectuée avec succès"
        ];

        header('Location: ' . URL . "livres");
    }
    //fonction de modification d'un livre
    public function modificationLivre($id)
    {
        $livre = $this->livreManager->getLivreById($id);
        require "views/modifierLivre.view.php";
    }

    //fonction de validation de la modification des donnes d'un livre
    public function modificationLivreValidation()
    {
        $imageActuelle = $this->livreManager->getLivreById($_POST['identifiant'])->getImage();
        $file = $_FILES['image'];

        if ($file['size'] > 0) {
            unlink("public/images/" . $imageActuelle);
            $repertoire = "public/images/";
            $nomImageToAdd = $this->ajoutImage($file, $repertoire);
        } else {
            $nomImageToAdd = $imageActuelle;
        }
        $this->livreManager->modificationLivreBD($_POST['identifiant'], $_POST['titre'], $_POST['nbPages'], $nomImageToAdd);

        $_SESSION['alert'] = [
            "type" => "success",
            "msg" => "Modification Réalisée avec succès"
        ];
        header('Location: ' . URL . "livres");
    }

    //fonction d'ajout de l'image d'un livre
    private function ajoutImage($file, $dir)
    {
        if (!isset($file['name']) || empty($file['name']))
            throw new Exception("Vous devez indiquer une image");

        if (!file_exists($dir)) mkdir($dir, 0777);

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $random = rand(0, 99999);
        $target_file = $dir . $random . "_" . $file['name'];

        if (!getimagesize($file["tmp_name"]))
            throw new Exception("Le fichier n'est pas une image");
        if ($extension !== "jpg" && $extension !== "jpeg" && $extension !== "png" && $extension !== "gif")
            throw new Exception("L'extension du fichier n'est pas reconnu");
        if (file_exists($target_file))
            throw new Exception("Le fichier existe déjà");
        if ($file['size'] > 500000)
            throw new Exception("Le fichier est trop gros");
        if (!move_uploaded_file($file['tmp_name'], $target_file))
            throw new Exception("l'ajout de l'image n'a pas fonctionné");
        else return ($random . "_" . $file['name']);
    }
}