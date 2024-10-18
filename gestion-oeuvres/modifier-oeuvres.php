<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<?php
// Inclure le fichier de configuration de la base de données
require_once '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérez les données du formulaire et nettoyez les entrées
        $id_oeuvre = htmlspecialchars($_POST['id_oeuvre'], ENT_QUOTES, 'UTF-8');
        $nouveau_titre = htmlspecialchars($_POST['nouveau_titre'], ENT_QUOTES, 'UTF-8');


        // Vérifiez que l'ID de l'oeuvre est un entier
        if (!filter_var($id_oeuvre, FILTER_VALIDATE_INT)) {
            throw new Exception("ID de l'oeuvre invalide.");
        }

        // Requête SQL pour mettre à jour le nom et le prénom de l'auteur
        $requete = "UPDATE OEUVRE SET titre_oeuvre = :titre_oeuvre WHERE id_oeuvre = :id";
        $stmt = $db->prepare($requete);
        $stmt->bindParam(':id', $id_oeuvre, PDO::PARAM_INT);
        $stmt->bindParam(':titre_oeuvre', $nouveau_titre, PDO::PARAM_STR);

        // Exécutez la requête
        $stmt->execute();

        // Fermez la fenêtre pop-up et actualisez la liste des auteurs dans la fenêtre parente
        echo "<script>window.close(); window.opener.location.reload();</script>";
    } catch (PDOException $e) {
        // Gérer les erreurs de mise à jour de manière sécurisée
        die("Erreur lors de la mise à jour de l'oeuvre : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    } catch (Exception $e) {
        // Gérer les autres erreurs
        die("Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}   else {
    // Récupérez l'ID de l'auteur à modifier depuis l'URL et nettoyez l'entrée
    $id_oeuvre = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');

    // Vérifiez que l'ID de l'auteur est un entier
    if (!filter_var($id_oeuvre, FILTER_VALIDATE_INT)) {
        die("ID de l'oeuvre invalide.");
    }

    // Récupérez les primaryrmations actuelles de l'oeuvre
    try {
        $requete_primary_oeuvre = "SELECT titre_oeuvre FROM OEUVRE WHERE id_oeuvre = :id";
        $stmt_primary_oeuvre = $db->prepare($requete_primary_oeuvre);
        $stmt_primary_oeuvre->bindParam(':id', $id_oeuvre, PDO::PARAM_INT);
        $stmt_primary_oeuvre->execute();
        $primary_oeuvre = $stmt_primary_oeuvre->fetch(PDO::FETCH_ASSOC);

        // Vérifiez si l'oeuvre existe
        if (!$primary_oeuvre) {
            die("Aucun oeuvre trouvé avec cet ID.");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des primaryrmations de l'oeuvre : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Oeuvre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <h1>Modifier l'oeuvre</h1>
    <form method="post" action="modifier-oeuvres.php">
        <!-- Champ caché pour l'ID de l'auteur -->
        <input type="hidden" name="id_oeuvre" value="<?= htmlspecialchars($id_oeuvre, ENT_QUOTES, 'UTF-8') ?>">
        
        <label for="nouveau_titre">Nouveau titre :</label>
        <input type="text" name="nouveau_titre" id="nouveau_titre" value="<?= htmlspecialchars($primary_oeuvre['titre_oeuvre'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>
        <label for="id_auteur">Nom de l'auteur :</label>
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
        <br>
        <br>
        <input class="btn btn-primary btn-sm" type="submit" value="Modifier">
    </form>
</body>
</html>