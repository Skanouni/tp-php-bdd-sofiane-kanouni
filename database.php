<?php
$servername = "localhost"; // Adresse du serveur MySQL
$username = "root"; // Nom d'utilisateur MySQL (en local : root)
$password = "root"; // Mot de passe MySQL (en local pour PC : vide / Mac : root)
$dbname = "mangaworldo_kanouni_sofiane"; // Nom de la base de données
try {
    // Créer une nouvelle instance PDO pour se connecter à la base de données
    // Le premier argument est la chaîne de connexion (dsn) qui spécifie le type de base de données, l'hôte et le nom de la base de données
    // Le deuxième argument est le nom d'utilisateur
    // Le troisième argument est le mot de passe
    $db = new PDO(
        "mysql:host=$servername;dbname=$dbname",
        $username,
        $password
    );
    // Décommenter la ligne ci-dessous pour afficher un message confirmant que la connexion a réussi
    echo "Connexion réussie";
    // Définir le mode d'erreur pour PDO à "EXCEPTION" afin que les erreurs soient levées sous forme d'exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si une exception est levée, cela signifie qu'il y a eu un problème de connexion
    // Afficher le message d'erreur renvoyé par PDO
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    // Le script s'arrête ici en cas d'erreur, évitant tout comportement imprévu
}


// Va chercher les données dans les tables pour les afficher
function getList($query){
    global $db;
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement->fetchAll();
}

?>