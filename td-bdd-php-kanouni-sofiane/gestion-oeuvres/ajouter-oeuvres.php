
<?php
// Inclure le fichier de configuration de la base de données
require_once '../database.php';


// Initialiser une variable pour afficher les messages
$message = '';


// Vérifiez si la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyer et valider les données de formulaire
    $titre_oeuvre = htmlspecialchars($_POST['titre_oeuvre'], ENT_QUOTES, 'UTF-8');
    $id_auteur = htmlspecialchars($_POST['id_auteur'], ENT_QUOTES, 'UTF-8');

    // Vérifier si l'auteur existe déjà en fonction du prénom et du nom
    $oeuvreExiste = false;
    try {
        // Requête SQL pour vérifier l'existence de l'auteur
        $sql = "SELECT titre_oeuvre FROM OEUVRE WHERE titre_oeuvre = :titre_oeuvre";
        $stmt = $db->prepare($sql);


        // Lier les paramètres avec les valeurs nettoyées
        $stmt->bindParam(':titre_oeuvre', $titre_oeuvre, PDO::PARAM_STR);
        $stmt->execute();


        // Si l'auteur existe déjà
        if ($stmt->rowCount() > 0) {
            $oeuvreExiste = true;
        }
    } catch (PDOException $e) {
        // Gérer les erreurs de base de données et sécuriser le message d'erreur
        $message = "Erreur lors de la vérification de l'oeuvre : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }


    if (!$oeuvreExiste) {
        // L'auteur n'existe pas, donc on peut l'ajouter
        try {
            // Préparez la requête d'insertion avec des paramètres liés
            $insertOeuvreSql = "INSERT INTO OEUVRE (titre_oeuvre, id_auteur) VALUES (:titre_oeuvre, :id_auteur)";
            
            $stmt = $db->prepare($insertOeuvreSql);


            // Lier les paramètres
            $stmt->bindParam(':titre_oeuvre', $titre_oeuvre, PDO::PARAM_STR);
            $stmt->bindParam(':id_auteur', $id_auteur, PDO::PARAM_INT);
            $stmt->execute();

            // Message de succès
            $message = "Nouvelle oeuvre ajoutée avec succès !";
            // Actualiser la liste des auteurs dans la fenêtre parente
            echo "<script>window.opener.location.reload();</script>";
        } catch (PDOException $e) {
            // Gérer les erreurs d'insertion et sécuriser le message d'erreur
            $message = "Erreur lors de l'ajout de l'oeuvre : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    } else {
        // L'auteur existe déjà
        $message = "Cet oeuvre existe déjà en base de données.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une nouvelle oeuvre</title>>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <h1>Ajouter une nouvelle oeuvre</h1>
    <!-- Affichage du message d'état -->
    <p><?php echo $message; ?></p>


    <!-- Formulaire d'ajout d'un nouvel auteur -->
    <form method="POST" action="ajouter-oeuvres.php">
        <label for="titre_oeuvre">Titre de l'oeuvre:</label>
        <input type="text" id="titre_oeuvre" name="titre_oeuvre" required><br><br>
        <label for="id_auteur">Nom de l'auteur:</label>
        <select class="form-select-sm btn btn-primary btn-sm " type="text" id="id_auteur" name="id_auteur" required><br>
        <option>Sélectionner l'auteur</option>
            <?php 
                $query = "SELECT id_auteur, nom_auteur FROM AUTEUR";
                $all_list = getList($query);


                // Boucle pour mettre autant d'option que de valeur en stock
                foreach($all_list as $output) {  
                    echo '<option value="'.$output['id_auteur'].'">'.$output['nom_auteur'].'</option>';
                }
                ?>
        </select>
        <!-- Bouton pour soumettre le formulaire -->
        <br>
        <br>
        <input class="btn btn-primary btn-sm" class="button" type="submit" value="Ajouter">
    </form>
</body>
</html>