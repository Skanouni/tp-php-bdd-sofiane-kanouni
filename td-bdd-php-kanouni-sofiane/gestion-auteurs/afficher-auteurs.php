<!DOCTYPE html>
<html>
<head>
    <title>Liste des Auteurs</title>
    <link rel="icon" type="image/png" href="../img/book-icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php
    // Inclure le fichier de configuration de la base de données
    require_once '../database.php';

            // Définir la limite d'éléments par page
    $limit = 10;

        // Obtenir la page actuelle à partir du paramètre GET, ou 1 si non défini
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calculer l'offset (décalage)
    $offset = ($page - 1) * $limit;

        // Requête pour récupérer le nombre total d'œuvres
    $countQuery = "SELECT COUNT(*) AS total FROM auteur";
    $countResult = $db->query($countQuery);
    $totalRows = $countResult->fetch(PDO::FETCH_ASSOC)['total'];

        // Calculer le nombre total de pages
    $totalPages = ceil($totalRows / $limit);


    try {
        // Requête SQL pour récupérer la liste des auteurs
        $requete = "SELECT id_auteur, prenom_auteur, nom_auteur 
                    FROM AUTEUR
                    LIMIT $limit OFFSET $offset";
        $resultat = $db->query($requete);

        // Affichage de la liste des auteurs avec les boutons "Modifier" et "Supprimer"
        echo "<h1>Liste des auteurs</h1><br>";
        echo "<form action = 'afficher-auteurs.php' method = 'GET'>
                <input type = 'search' name = 'terme' placeholder='Rechercher'>
                <input type = 'submit' name = 's' value = 'Rechercher'>
            </form>";
        echo "<br><br>";
        echo "<table class='table table-light table-striped' border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID</th><th>PRENOM</th><th>NOM</th><th></th><th></th></tr>";

        while ($auteur = $resultat->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$auteur['id_auteur']}</td>";
            echo "<td>{$auteur['prenom_auteur']}</td>";
            echo "<td>{$auteur['nom_auteur']}</td>";
            // Bouton "Modifier" qui ouvre une fenêtre pop-up pour modifier l'auteur
            echo "<td><button class='btn btn-primary btn-sm' onclick='modifierAuteur({$auteur['id_auteur']})'>Modifier</button></td>";
            // Bouton "Supprimer" avec confirmation
            echo "<td><button class='btn btn-primary btn-sm' onclick='confirmationSuppAuteur({$auteur['id_auteur']})'>Supprimer</button></td>";
            echo "</tr>";
        }

            echo "</table>";

            echo '<nav aria-label="Page navigation">';
            echo '<ul class="pagination justify-content-center">';
            
            // Lien vers la page précédente
            if ($page > 1) {
                $prevPage = $page - 1;
                echo "<li class='page-item'><a class='page-link' href='?page=$prevPage'>Précédent</a></li>";
            }
    
            // Afficher les numéros de page
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($i == $page) ? 'active' : '';
                echo "<li class='page-item $activeClass'><a class='page-link' href='?page=$i'>$i</a></li>";
            }
    
            // Lien vers la page suivante
            if ($page < $totalPages) {
                $nextPage = $page + 1;
                echo "<li class='page-item'><a class='page-link' href='?page=$nextPage'>Suivant</a></li>";
            }
    
            echo '</ul>';
            echo '</nav>';
    
            $resultat->closeCursor();  // Fermer le curseur
            $db = null;  // Fermer la connexion à la base de données

            
            if ($resultat) {  

                // Boucle pour afficher les données
                while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {  
                echo "ID de l'auteur: " . $row['id_auteur'] . "<br>";
                echo "Prenom de l'auteur: " . $row['prenom_auteur'] . "<br>";
                echo "Nom de l'auteur : " . $row['nom_auteur']."<br>";
                }
            } else {
                echo "La requête a échoué.";
            }


        } catch (PDOException $e) {
            die("Erreur lors de la récupération des auteurs : " . $e->getMessage());
        }
        ?>

    <!-- Bouton pour ajouter un auteur dans une petite fenêtre -->
    <br>
    <button class='btn btn-primary btn-sm' onclick="ajouterAuteur()">Ajouter un auteur</button>

    <!-- Bouton pour renvoyer vers la liste des œuvres -->
    <button class='btn btn-primary btn-sm' onclick="window.location.href='../gestion-oeuvres/afficher-oeuvres.php'">Afficher les œuvres</button>

    <!-- Scripts JavaScript pour les fenêtres pop-up et la suppression -->
    <script>
        function modifierAuteur(id_auteur) {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification de l'auteur
            var popupWindow = window.open('modifier-auteur.php?id=' + id_auteur, 'Modifier Auteur', 'width=600,height=400');
        }

        function confirmationSuppAuteur(id_auteur) {
            // Demander une confirmation avant de supprimer un auteur et toutes ses œuvres associées
            if (confirm("Êtes-vous sûr de vouloir supprimer cet auteur et toutes ses œuvres associées ?")) {
                // Rediriger vers la page de suppression avec l'ID de l'auteur
                window.location.href = 'supprimer-auteur.php?id_auteur=' + id_auteur;
            }
        }

        function ajouterAuteur() {
            // Ouvrir une fenêtre pop-up pour ajouter un auteur
            var popupWindow = window.open('ajouter-auteur.php', 'Ajouter Auteur', 'width=600,height=400');
        }
    </script>
</body>
</html>
