<?php
// Inclusion du fichier de configuration de la base de données si nécessaire
// include 'database.php'; // Décommenter si besoin d'accès à la DB
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="icon" type="image/png" href="../img/book-icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet"> <!-- Si vous avez un fichier CSS -->
</head>
<body>
    <header>
        <h1>Bienvenue dans le Gestionnaire d'Œuvres</h1>
    </header>
    
    <nav>
        <ul>
            <li><a class="btn btn-primary" href="./gestion-auteurs/afficher-auteurs.php">Voir les Auteurs</a></li><br>
            <li><a class="btn btn-primary" href="./gestion-oeuvres/afficher-oeuvres.php">Voir les Œuvres</a></li>
        </ul>
    </nav>

    <main>
        <h2>Navigation</h2>
        <p>Utilisez les liens ci-dessus pour naviguer entre les auteurs et les œuvres.</p>
    </main>

    <footer>
        <p>&copy; 2024 Gestionnaire d'Œuvres</p>
    </footer>
</body>
</html>