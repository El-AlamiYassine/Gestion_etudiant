<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=etudiants_db;port=3306;charset=utf8", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $stmt = $conn->query('SELECT absences.*, etudiants.nom, etudiants.prenom FROM absences JOIN etudiants ON absences.id_etudiant = etudiants.id ORDER BY absences.date_absence DESC');
    $absences = $stmt->fetchAll();
} catch (PDOException $e) {
    echo 'Erreur de connexion: ' . $e->getMessage();
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des absences</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: #333;
            margin-top: 20px;
        }

        .table-wrapper {
            margin: 25px 0;
            box-shadow: 0px 35px 50px rgba(0, 0, 0, 0.2);
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            width: 80%;
        }

        .fl-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 16px;
            text-align: left;
        }

        .fl-table th,
        .fl-table td {
            padding: 12px 15px;
        }

        .fl-table thead th {
            background-color: #4FC3A1;
            color: #ffffff;
        }

        .fl-table tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .fl-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .fl-table tbody tr td {
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>Liste des absences</h2>
    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>Date d'absence</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Heure d'entrée</th>
                    <th>Heure de sortie</th>
                    <th>justification</th>
                    <th>Éditer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absences as $absence): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($absence['date_absence']); ?></td>
                        <td><?php echo htmlspecialchars($absence['nom']); ?></td>
                        <td><?php echo htmlspecialchars($absence['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($absence['heure_entree']); ?></td>
                        <td><?php echo htmlspecialchars($absence['heure_sortie']); ?></td>
                        <td><?php echo htmlspecialchars($absence['preuve']); ?></td>
                        <td><a href="edit_absence.php?id=<?php echo htmlspecialchars($absence['id']); ?>">Éditer</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <a href="index.php">la listes des etudaints</a>
    <a href="liste_absences.php">la listes des absences</a>
</body>
</html>
