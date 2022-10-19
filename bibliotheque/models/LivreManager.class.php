<?php
require_once "Model.class.php";
require_once "Livre.class.php";

//definition de la class LivreManager
class LivreManager extends Model

{
    private $livres; //tablaux de livre

    public function ajoutLivre($livre)
    {
        $this->livres[] = $livre;
    }

    public function getlivres()
    {
        return $this->livres;
    }

    public function chargementLivres()
    {
        //Requete pour recuperation des lignes de donnees de la BDD
        $req = $this->getBdd()->prepare("SELECT * FROM livres ORDER BY id DESC");
        //Execution
        $req->execute();
        //recuperation
        $meslivres = $req->fetchAll(PDO::FETCH_ASSOC);
        //Fermeture de la requete de
        $req->closeCursor();
        //pacourir le tableau des chargementLivres
        foreach ($meslivres as $livre) {
            $l = new Livre($livre['id'], $livre['titre'], $livre['nbPages'], $livre['image']);
            $this->ajoutLivre($l);
        }
    }

    public function getlivreById($id)
    {
        for ($i = 0; $i < count($this->livres); $i++) {
            if ($this->livres[$i]->getId() == $id) {
                return $this->livres[$i];
            }
        }
        throw new Exception("Le livre n'existe pas");
    }

    //fonction d'ajout de livre dans la BDD
    public function ajoutLivreBd($titre, $nbPages, $image)
    {
        $req = "
        INSERT INTO livres (titre, nbPages, image)
        values (:titre, :nbPages, :image)";
        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
        $stmt->bindValue(":nbPages", $nbPages, PDO::PARAM_INT);
        $stmt->bindValue(":image", $image, PDO::PARAM_STR);
        $resultat = $stmt->execute();
        $stmt->closeCursor();

        if ($resultat > 0) {
            $livre = new Livre($this->getBdd()->lastInsertId(), $titre, $nbPages, $image);
            $this->ajoutLivre($livre);
        }
    }

    //supression dans la base de donnees d'un livre
    public function suppressionLivreBD($id)
    {
        $req = "
        Delete from livres where id = :idLivre
        ";
        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindValue(":idLivre", $id, PDO::PARAM_INT);
        $resultat = $stmt->execute();
        $stmt->closeCursor();
        if ($resultat > 0) {
            $livre = $this->getLivreById($id);
            unset($livre);
        }
    }

    //Modification d'un livre dans la bdd
    public function modificationLivreBD($id, $titre, $nbPages, $image)
    {
        $req = "
        update livres 
        set titre = :titre, nbPages = :nbPages, image = :image
        where id = :id";
        $stmt = $this->getBdd()->prepare($req);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":titre", $titre, PDO::PARAM_STR);
        $stmt->bindValue(":nbPages", $nbPages, PDO::PARAM_INT);
        $stmt->bindValue(":image", $image, PDO::PARAM_STR);
        $resultat = $stmt->execute();
        $stmt->closeCursor();

        if ($resultat > 0) {
            $this->getLivreById($id)->setTitre($titre);
            $this->getLivreById($id)->setTitre($nbPages);
            $this->getLivreById($id)->setTitre($image);
        }
    }
}