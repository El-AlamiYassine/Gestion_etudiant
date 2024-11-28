<?php
// Connexion à la base de données
    try {
        $conn = new PDO("mysql:host=localhost;dbname=etudiants_db;port=3306;charset=utf8", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) {
        echo 'Erreur de connexion: ' . $e->getMessage();
        exit();
    }

    // Récupération de l'étudiant
    if (!isset($_GET['id'])) {
        echo 'ID étudiant non fourni';
        exit();
    }
    $etudiant_id = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM etudiants WHERE id = ?');
    $stmt->execute([$etudiant_id]);
    $etudiant = $stmt->fetch();

    if (!$etudiant) {
        echo 'Étudiant non trouvé';
        exit();
    }

    // Ajout d'une absence
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $date_absence = htmlspecialchars($_POST['date_absence']);
        $justification = htmlspecialchars($_POST['preuve']);

        if (!empty($date_absence)) {
            $stmt = $conn->prepare('INSERT INTO absences (id_etudiant, date_absence, preuve) VALUES (?, ?, ?)');
            $stmt->execute([$etudiant_id, $date_absence, $justification]);
            header("Location: absences.php?id=$etudiant_id");
            exit();
        }
    }

    // Récupération des absences de l'étudiant
    $stmt = $conn->prepare('SELECT * FROM absences WHERE id_etudiant = ?');
    $stmt->execute(array($etudiant_id));
    $absences = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absences de <?php echo htmlspecialchars($etudiant['prenom']) . ' ' . htmlspecialchars($etudiant['nom']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .formField {
            margin-bottom: 15px;
        }
        .formField label {
            display: block;
            margin-bottom: 5px;
        }
        .formField input, .formField textarea {
            width: calc(100% - 12px);
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Absences de <?php echo htmlspecialchars($etudiant['prenom']) . ' ' . htmlspecialchars($etudiant['nom']); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>heure d'entre</th>
                    <th>heure de sortie</th>
                    <th>Justification</th>
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
        <a href="index.php">la listes des etudiants</a>
    </div>
</body>
</html>
