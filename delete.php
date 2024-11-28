<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=etudiants_db;port=3306;charset=utf8", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $stmt = $conn->query('SELECT * FROM etudiants');
    $etudiants = $stmt->fetchAll();
} catch (PDOException $e) {
    echo 'Erreur de connexion: ' . $e->getMessage();
    exit();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Suppression de l'étudiant
    $stmt = $conn->prepare('DELETE FROM etudiants WHERE id = ?');
    $stmt->execute([$id]);

    header("Location: index.php");
    exit();
}
?>
