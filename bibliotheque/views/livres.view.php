<?php
ob_start();

if (!empty($_SESSION['alert'])) :

?>
<div class="alert alert-<?= $_SESSION['alert']['type'] ?>" role="alert">
    <?= $_SESSION['alert']['msg'] ?>
</div>
<?php
    unset($_SESSION['alert']);
endif;
?>

<table class="table text-center">

    <tr class="table-light">
        <th>Image</th>
        <th>Titre</th>
        <th>Nombre de pages</th>
        <th colspan="2">Actions</th>
    </tr>


    <?php
    //Affichage de la liste des livres

    for ($i = 0; $i < count($livres); $i++) :
    ?>
    <tr>
        <td class="align-middle"><img src="public/images/<?= $livres[$i]->getImage() ?>" alt="Cahier d'algorytmique "
                width="90px" height="90px">
        </td>
        <td class="align-middle"><a
                href="<?= URL ?>livres/lire/<?= $livres[$i]->getId(); ?>"><?= $livres[$i]->getTitre(); ?></a></td>
        <td class="align-middle"><?= $livres[$i]->getNbPages() ?></td>
        <td class="align-middle"><a href="<?= URL ?>livres/modifier/<?= $livres[$i]->getId(); ?>"
                class="btn btn-warning">Modifier</a></td>
        <td class="align-middle">
            <form method="POST" action="<?= URL ?>livres/supprimer/<?= $livres[$i]->getId(); ?>"
                onSubmit="return confirm('Voulez-vous vraiment supprimer le livre ?');">
                <button class="btn btn-outline-warning" type="submit">Supprimer</button>
            </form>
        </td>
    </tr>
    <?php endfor; ?>
</table>
<a href="<?= URL ?>livres/ajout" class="btn btn-outline-success d-block">Ajouter</a>

<?php
$content = ob_get_clean();
$titre = "Un regard sur nos livres";
require 'template.php';