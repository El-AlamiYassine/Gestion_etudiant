<?php
session_start();

// Connexion à la base de données et récupération des étudiants
try {
    $conn = new PDO("mysql:host=localhost;dbname=etudiants_db;port=3306;charset=utf8", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $stmt = $conn->query('SELECT * FROM etudiants');
    $etudiants = $stmt->fetchAll();
} catch (PDOException $e) {
    echo 'Erreur de connexion: ' . $e->getMessage();
    exit();
}

// Traitement de l'envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification et récupération des données du formulaire
    $matier = $_POST['matier'];
    $enseignement = $_POST['enseignement'];

    // Parcourir les étudiants
    foreach ($etudiants as $etudiant) {
        $id_etudiant = $etudiant['id'];

        // Gestion des notes
        $note_key = 'note_' . $id_etudiant;
        if (isset($_POST[$note_key])) {
            $note = $_POST[$note_key];

            if (!empty($note)) {
                $stmt = $conn->prepare("INSERT INTO notes (student_id, subject, teaching, grade) VALUES (:student_id, :subject, :teaching, :grade)");
                $stmt->execute(array(
                    ':student_id' => $id_etudiant,
                    ':subject' => $matier,
                    ':teaching' => $enseignement,
                    ':grade' => $note
                ));
            }
        }
    }

    // Redirection après l'insertion
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .table-wrapper {
            margin: 25px 0;
            box-shadow: 0px 35px 50px rgba(0, 0, 0, 0.2);
        }

        .fl-table {
            border-radius: 5px;
            font-size: 12px;
            font-weight: normal;
            border: none;
            border-collapse: collapse;
            width: 100%;
            max-width: 100%;
            white-space: nowrap;
            background-color: white;
        }

        .fl-table td,
        .fl-table th {
            text-align: center;
            padding: 8px;
            font-size: 16px;
        }

        .fl-table td {
            border-right: 1px solid #f8f8f8;
            font-size: 16px;
        }

        .fl-table thead th {
            color: #ffffff;
            background: #4FC3A1;
        }

        .fl-table thead th:nth-child(odd) {
            color: #ffffff;
            background: #324960;
        }

        .fl-table tr:nth-child(even) {
            background: #F8F8F8;
        }

        .divform {
            display: flex;
            gap: 20px;
        }

        .divform input {
            width: 300px;
            border: 0;
            height: 35px;
            padding-left: 10px;
            box-shadow: 0 0 4px #000;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <h2>Mes Notes</h2>
    <form method="post">
        <div class="table-wrapper">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>Identification scolaire</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $etudiant) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($etudiant['identification_scolaire']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                            <td>
                                <input type="number" name="note_<?php echo $etudiant['id']; ?>" step="0.01">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="divform">
            <div>
                <label for="matier">Matière :</label>
                <input type="text" name="matier" id="matier" required>
            </div>
            <div>
                <label for="enseignement">Enseignement :</label>
                <input type="text" name="enseignement" id="enseignement" required>
            </div>
            <button type="submit">Envoyer</button>
        </div>
    </form>
    <a href="index.php">La liste des étudiants</a>
    <a href="liste_absences.php">La liste des absences</a>
</body>

</html>
