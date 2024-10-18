<!DOCTYPE html>
<html>
<head>
    <title>Liste des Oeuvres</title>
    <link rel="icon" type="image/png" href="../img/book-icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet">

</head>
<body>

    <?php
        require_once '../database.php'; // Inclure le fichier de connexion à la base de données
        
        // Fait la limite de 10 du tableau
        $limit = 10;

        // Obtenir la page actuelle à partir du paramètre GET, ou 1 si non défini
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Décale l'élément vers la deuxieme page
        $offset = ($page - 1) * $limit;

        // Requête pour récupérer le nombre total d'œuvres
        $countQuery = "SELECT COUNT(*) AS total FROM oeuvre";
        $countResult = $db->query($countQuery);
        $totalRows = $countResult->fetch(PDO::FETCH_ASSOC)['total'];

        // Calcule les totals de ligne par page arrondi supérieur
        $totalPages = ceil($totalRows / $limit);

        try {
            $requete = "SELECT * FROM oeuvre o
                        INNER JOIN AUTEUR a ON a.id_auteur = o.id_auteur 
                        ORDER BY titre_oeuvre ASC 
                        LIMIT $limit OFFSET $offset"; // Requête SQL pour récupérer les champs de la table 'pays'
            $resultat = $db->query($requete); // Exécution de la requête SQL

            echo "<h1>Liste des oeuvres</h1><br>";
            echo "<form action = 'afficher-oeuvres.php' method = 'GET'>
                    <input type = 'search' name = 'terme' placeholder='Rechercher'>
                    <input type = 'submit' name = 's' value = 'Rechercher'>
                </form>";
            echo "<br><br>";
            echo "<table class='table table-light table-striped' border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>TITRE</th><th>AUTEUR</th><th></th><th></th></tr>";
            

            while ($oeuvre = $resultat->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$oeuvre['id_oeuvre']}</td>";
                echo "<td>{$oeuvre['titre_oeuvre']}</td>";
                echo "<td>{$oeuvre['prenom_auteur']} {$oeuvre['nom_auteur']} </td>";
                // Bouton "Modifier" qui ouvre une fenêtre pop-up pour modifier l'auteur
                echo "<td><button class='btn btn-primary btn-sm ' onclick='modifierOeuvre({$oeuvre['id_oeuvre']})'>Modifier</button></td>";
                // Bouton "Supprimer" avec confirmation
                echo "<td><button class='btn btn-primary btn-sm' onclick='confirmationSuppOeuvre({$oeuvre['id_oeuvre']})'>Supprimer</button></td>";
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
                    echo "ID de l'oeuvre : " . $row['id_oeuvre'] . "<br>";
                    echo "Titre de l'oeuvre : " . $row['titre_oeuvre'] . "<br>";
                    echo "Auteur : " . $row['nom_auteur'] . ' ' . $row['prenom_auteur'] ."<br>";
                    }
                } else {
                    echo "La requête a échoué.";
                }
            
                $resultat->closeCursor();  // Fermer le curseur
                $connexion = null;  // Fermer la connexion à la base de données


        }    catch (PDOException $e) {
                die("Erreur lors de la récupération des données : " . $e->getMessage());

        }
        ?>

    <!-- Bouton pour ajouter un auteur dans une petite fenêtre -->
    <br>
    <button class="btn btn-primary btn-sm" onclick="ajouterOeuvre()">Ajouter une oeuvre</button>

    <!-- Bouton pour renvoyer vers la liste des œuvres -->
    <button  class="btn btn-primary btn-sm" onclick="window.location.href='../gestion-auteurs/afficher-auteurs.php'">Afficher les auteurs</button>

    <!-- Scripts JavaScript pour les fenêtres pop-up et la suppression -->
    <script>
        function modifierOeuvre(id_oeuvre) {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification de l'auteur
            var popupWindow = window.open('modifier-oeuvres.php?id=' + id_oeuvre, 'Modifier Oeuvre', 'width=600,height=500');
        }

        function confirmationSuppOeuvre(id_oeuvre) {
            // Demander une confirmation avant de supprimer un auteur et toutes ses œuvres associées
            if (confirm("Êtes-vous sûr de vouloir supprimer cette oeuvre et tous les auteurs associés ?")) {
                // Rediriger vers la page de suppression avec l'ID de l'auteur
                window.location.href = 'supprimer-oeuvres.php?id_oeuvre=' + id_oeuvre;
            }
        }

        function ajouterOeuvre() {
            // Ouvrir une fenêtre pop-up pour ajouter un auteur
            var popupWindow = window.open('ajouter-oeuvres.php', 'Ajouter Oeuvre', 'width=600,height=500');
        }
    </script>
</body>
</html>

