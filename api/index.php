<?php
$servername = getenv('MYSQL_HOST');
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la table si elle n'existe pas
    $conn->exec("CREATE TABLE IF NOT EXISTS temperatures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        location VARCHAR(255) NOT NULL,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        temperature FLOAT NOT NULL
    )");

    // CRUD operations
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Create - Ajouter une mesure
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $conn->prepare("INSERT INTO temperatures (location, temperature) VALUES (:location, :temperature)");
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':temperature', $data['temperature']);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Temperature recorded"]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Read - Obtenir les mesures de température
        $stmt = $conn->prepare("SELECT * FROM temperatures ORDER BY date DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Update - Mettre à jour une mesure
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $conn->prepare("UPDATE temperatures SET location = :location, temperature = :temperature WHERE id = :id");
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':temperature', $data['temperature']);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Temperature updated"]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Delete - Supprimer une mesure
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $conn->prepare("DELETE FROM temperatures WHERE id = :id");
        $stmt->bindParam(':id', $data['id']);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Temperature deleted"]);
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
