<?php
//Récuperation de la classe Livre

ob_start()

?>
Contenu de la page d'acueil
<?php
$content = ob_get_clean();
$titre = "La bibliothèque NASA";
require 'template.php';