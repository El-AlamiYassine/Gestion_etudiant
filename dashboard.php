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
    $date_absence = $_POST['date_scinece'];
    $heure_entree = $_POST['entre'];
    $heure_sortie = $_POST['sortir'];

    // Parcourir les absences de chaque étudiant
    foreach ($etudiants as $etudiant) {
        $id_etudiant = $etudiant['id'];
        $absence_key = 'absence_' . $id_etudiant;
        if (isset($_POST[$absence_key])) {
            $type_absence = $_POST[$absence_key];

            // Vérifier si l'étudiant est absent (A)
            if ($type_absence == 'A') {
                // Préparer et exécuter la requête d'insertion
                $stmt = $conn->prepare("INSERT INTO absences (id_etudiant, date_absence, heure_entree, heure_sortie, type_absence) VALUES (:id_etudiant, :date_absence, :heure_entree, :heure_sortie, :type_absence)");
                $stmt->execute(array(
                    ':id_etudiant' => $id_etudiant,
                    ':date_absence' => $date_absence,
                    ':heure_entree' => $heure_entree,
                    ':heure_sortie' => $heure_sortie,
                    ':type_absence' => $type_absence
                ));
            }
        }
    }

    // Redirection après l'insertion
    header("Location: ".$_SERVER['PHP_SELF']);
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
    .divform{
        display: flex;
        gap: 20px;
    }
    .divform input{
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
    <h2>Mes Absences</h2>
    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de naissance</th>
                    <th>Identification scolaire</th>
                    <th>Mode de suivi</th>
                    <th>Absence (P/A)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants as $etudiant) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                        <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($etudiant['date_naissance']); ?></td>
                        <td><?php echo htmlspecialchars($etudiant['identification_scolaire']); ?></td>
                        <td><?php echo htmlspecialchars($etudiant['mode_suivi']); ?></td>
                        <td>
                            <form method="post">
                                <input type="radio" name="absence_<?php echo $etudiant['id']; ?>" value="P"> P
                                <input type="radio" name="absence_<?php echo $etudiant['id']; ?>" value="A"> A
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="divform">
        <div>
            <label for="date_scinece">Date :</label>
            <input type="date" name="date_scinece" id="date_scinece">
        </div>
        <div>
            <label for="entre">Heure d'entrée :</label>
            <input type="time" name="entre" id="entre">
        </div>
        <div>
            <label for="sortir">Heure de sortie :</label>
            <input type="time" name="sortir" id="sortir">
        </div>
        <button type="submit">Envoyer</button>
    </div>
    
    </form>
    <a href="index.php">la listes des etudaints</a>
    <a href="liste_absences.php">la listes des absences</a>
</body>

</html>
