<?php
session_start();
try {
    $conn = new PDO("mysql:host=localhost;dbname=etudiants_db;port=3306;charset=utf8", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $stmt = $conn->query('SELECT * FROM etudiants');
    $etudiants = $stmt->fetchAll();
} catch (PDOException $e) {
    echo 'Erreur de connexion: ' . $e->getMessage();
    exit();
}

// Vérifiez si l'étudiant est connecté
if (!isset($_SESSION['etudiant_id'])) {
    header("Location: student_login.php");
    exit();
}

$etudiant_id = $_SESSION['etudiant_id'];

// Récupération des informations de l'étudiant
$stmt = $conn->prepare('SELECT * FROM etudiants WHERE id = ?');
$stmt->execute([$etudiant_id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    echo "Étudiant introuvable.";
    exit();
}

// Récupération des notes
$notes_stmt = $conn->prepare('SELECT * FROM notes WHERE student_id = ?'); // Assurez-vous que le nom de colonne est correct
$notes_stmt->execute([$etudiant_id]);
$notes = $notes_stmt->fetchAll();

// Récupération des absences
$absences_stmt = $conn->prepare('SELECT * FROM absences WHERE id_etudiant = ?'); // Assurez-vous que le nom de colonne est correct
$absences_stmt->execute([$etudiant_id]);
$absences = $absences_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Étudiant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue, <?php echo htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?></h1>
        
        <h2>Notes</h2>
        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Enseignement</th>
                    <th>note</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($note['subject']); ?></td>
                        <td><?php echo htmlspecialchars($note['teaching']); ?></td>
                        <td><?php echo htmlspecialchars($note['grade']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Absences</h2>
        <table>
            <thead>
                <tr>
                    <th>date_absence</th>
                    <th>heure_entree</th>
                    <th>heure_sortie</th>
                    <th>preuve</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absences as $absence) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($absence['date_absence']); ?></td>
                        <td><?php echo htmlspecialchars($absence['heure_entree']); ?></td>
                        <td><?php echo htmlspecialchars($absence['heure_sortie']); ?></td>
                        <td><?php echo htmlspecialchars($absence['preuve']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="student_login.php" class="button">Déconnexion</a>
    </div>
    
</body>
</html>
