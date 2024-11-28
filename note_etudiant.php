<?php
// Connexion à la base de données
try {
    $conn = new PDO("mysql:host=localhost;dbname=etudiants_db;port=3306;charset=utf8", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo 'Erreur de connexion: ' . $e->getMessage();
    exit();
}

// Récupérer l'ID de l'étudiant depuis l'URL
$id_etudiant = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_etudiant == 0) {
    echo "Identifiant de l'étudiant manquant ou incorrect.";
    exit();
}

// Récupération des informations de l'étudiant
$stmt = $conn->prepare('SELECT * FROM etudiants WHERE id = ?');
$stmt->execute([$id_etudiant]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    echo "Étudiant non trouvé.";
    exit();
}

// Récupération des notes de l'étudiant
$stmt = $conn->prepare('SELECT * FROM notes WHERE student_id = ?');
$stmt->execute([$id_etudiant]);
$notes = $stmt->fetchAll();

// Traitement de l'ajout ou de la modification d'une note
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = htmlspecialchars($_POST['subject']);
    $teaching = htmlspecialchars($_POST['teaching']);
    $grade = htmlspecialchars($_POST['grade']);
    $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;

    if (!empty($subject) && !empty($teaching) && !empty($grade)) {
        if ($note_id > 0) {
            // Modification d'une note existante
            $stmt = $conn->prepare('UPDATE notes SET subject = ?, teaching = ?, grade = ? WHERE id = ? AND student_id = ?');
            $stmt->execute([$subject, $teaching, $grade, $note_id, $id_etudiant]);
        } else {
            // Ajout d'une nouvelle note
            $stmt = $conn->prepare('INSERT INTO notes (student_id, subject, teaching, grade) VALUES (?, ?, ?, ?)');
            $stmt->execute([$id_etudiant, $subject, $teaching, $grade]);
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_etudiant);
        exit();
    }
}

// Récupérer les détails de la note à modifier si applicable
$note_to_edit = null;
if (isset($_GET['edit_note_id'])) {
    $edit_note_id = intval($_GET['edit_note_id']);
    $stmt = $conn->prepare('SELECT * FROM notes WHERE id = ? AND student_id = ?');
    $stmt->execute([$edit_note_id, $id_etudiant]);
    $note_to_edit = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes de l'étudiant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
        }

        .form-container,
        .table-wrapper {
            width: 80%;
            margin: auto;
        }

        .form-container {
            background-color: #f7f7f7;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
        }

        .formField {
            margin-bottom: 15px;
        }

        .formField label {
            display: block;
            margin-bottom: 5px;
        }

        .formField input,
        .formField select {
            width: 100%;
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

        .table-wrapper {
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

        .fl-table thead th {
            color: #ffffff;
            background: #4FC3A1;
        }

        .fl-table tr:nth-child(even) {
            background: #F8F8F8;
        }
    </style>
</head>

<body>
    <h2>Notes de l'étudiant : <?php echo htmlspecialchars($etudiant['nom']) . " " . htmlspecialchars($etudiant['prenom']); ?></h2>
    
    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Enseignement</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($note['subject']); ?></td>
                        <td><?php echo htmlspecialchars($note['teaching']); ?></td>
                        <td><?php echo htmlspecialchars($note['grade']); ?></td>
                        <td>
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id_etudiant . '&edit_note_id=' . $note['id']; ?>">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="form-container">
        <h3><?php echo $note_to_edit ? 'Modifier la note' : 'Ajouter une nouvelle note'; ?></h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id_etudiant; ?>">
            <input type="hidden" name="note_id" value="<?php echo $note_to_edit ? htmlspecialchars($note_to_edit['id']) : ''; ?>">
            <div class="formField">
                <label for="subject">Matière:</label>
                <input type="text" id="subject" name="subject" value="<?php echo $note_to_edit ? htmlspecialchars($note_to_edit['subject']) : ''; ?>" required>
            </div>
            <div class="formField">
                <label for="teaching">Enseignement:</label>
                <input type="text" id="teaching" name="teaching" value="<?php echo $note_to_edit ? htmlspecialchars($note_to_edit['teaching']) : ''; ?>" required>
            </div>
            <div class="formField">
                <label for="grade">Note:</label>
                <input type="number" id="grade" name="grade" step="0.01" value="<?php echo $note_to_edit ? htmlspecialchars($note_to_edit['grade']) : ''; ?>" required>
            </div>
            <button type="submit" class="button"><?php echo $note_to_edit ? 'Modifier' : 'Ajouter'; ?></button>
        </form>
    </div>
    <a href="index.php">liste des etudiants</a>
</body>

</html>