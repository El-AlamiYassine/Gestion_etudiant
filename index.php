<?php
// Connexion à la base de données
try {
    $conn = new PDO("mysql:host=localhost;dbname=etudiants_db;port=3306;charset=utf8", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo 'Erreur de connexion: ' . $e->getMessage();
    exit();
}

// Traitement de l'ajout ou de la modification d'un étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ajout d'un étudiant
    if (isset($_POST['ajouter'])) {
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $date_naissance = htmlspecialchars($_POST['date_naissance']);
        $genre = htmlspecialchars($_POST['genre']);
        $nationalite = htmlspecialchars($_POST['nationalite']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $email = htmlspecialchars($_POST['email']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $parent_nom = htmlspecialchars($_POST['parent_nom']);
        $parent_telephone = htmlspecialchars($_POST['parent_telephone']);
        $niveau_etudes = htmlspecialchars($_POST['niveau_etudes']);
        $identification_scolaire = htmlspecialchars($_POST['identification_scolaire']);
        $duree_programme = htmlspecialchars($_POST['duree_programme']);
        $mode_suivi = htmlspecialchars($_POST['mode_suivi']);

        if (!empty($nom) && !empty($prenom) && !empty($date_naissance) && !empty($genre) && !empty($nationalite) && !empty($adresse) && !empty($email) && !empty($telephone) && !empty($parent_nom) && !empty($parent_telephone) && !empty($niveau_etudes) && !empty($identification_scolaire) && !empty($duree_programme) && !empty($mode_suivi)) {
            $stmt = $conn->prepare('INSERT INTO etudiants (nom, prenom, date_naissance, genre, nationalite, adresse, email, telephone, parent_nom, parent_telephone, niveau_etudes, identification_scolaire, duree_programme, mode_suivi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$nom, $prenom, $date_naissance, $genre, $nationalite, $adresse, $email, $telephone, $parent_nom, $parent_telephone, $niveau_etudes, $identification_scolaire, $duree_programme, $mode_suivi]);
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();
        }
    }

}

// Récupération de tous les étudiants
$stmt = $conn->query('SELECT * FROM etudiants');
$etudiants = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des étudiants</title>
    <style>
    body{
        display: flex;
        gap: 30px;
    }
    body >div:first-child{
        width: 25%;
    }
    body >div:last-child{
        width: 75%;
    }
    form{
        background-color: #f7f7f7;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .formField input,
        .formField select {
            width: calc(100% - 12px);
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

    .formField select {
        width: 100%;
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
    .abs{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
    }
    .abs a{
        text-decoration: none;
        border: 1px solid #ccc;
        padding: 8px;
        border-radius: 8px;
        color: white;
        background-color: #000;
    }
    </style>
</head>

<body>
    <div class="form_1">
        <h2>Ajouter/Modifier un étudiant</h2>
        <?php if (isset($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="id" >
            <div class="formField">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="formField">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="formField">
                <label for="date_naissance">Date de naissance:</label>
                <input type="date" id="date_naissance" name="date_naissance" required>
            </div>
            <div class="formField">
                <label for="genre">Genre:</label>
                <input id="genre" name="genre" required>
            </div>
            <div class="formField">
                <label for="nationalite">Nationalité:</label>
                <input type="text" id="nationalite" name="nationalite" required>
            </div>
            <div class="formField">
                <label for="adresse">Adresse:</label>
                <input type="text" id="adresse" name="adresse"required>
            </div>
            <div class="formField">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="formField">
                <label for="telephone">Téléphone:</label>
                <input type="text" id="telephone" name="telephone"required>
            </div>
            <div class="formField">
                <label for="parent_nom">Nom du parent/tuteur:</label>
                <input type="text" id="parent_nom" name="parent_nom"equired>
            </div>
            <div class="formField">
                <label for="parent_telephone">Téléphone du parent/tuteur:</label>
                <input type="text" id="parent_telephone" name="parent_telephone" required>
            </div>
            <div class="formField">
                <label for="niveau_etudes">Niveau d'études:</label>
                <input type="text" id="niveau_etudes" name="niveau_etudes"  required>
            </div>
            <div class="formField">
                <label for="identification_scolaire">Identification scolaire:</label>
                <input type="text" id="identification_scolaire" name="identification_scolaire" required>
            </div>
            <div class="formField">
                <label for="duree_programme">Durée du programme:</label>
                <input type="text" id="duree_programme" name="duree_programme" required>
            </div>
            <div class="formField">
                <label for="mode_suivi">Mode de suivi:</label>
                <input type="text" id="mode_suivi" name="mode_suivi" required>
            </div>
            <button type="submit" name="ajouter" class="button">Ajouter</button>
        </form>
    </div>

    <div class="container-2">
        <div class="abs">
            <h2>Liste des étudiants</h2>
            <div>
                <a href="dashboard.php">pour gere les absence click ici</a>
                <a href="notes.php">pour geree les notes click ici</a>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Identification scolaire</th>
                        <th>Mode de suivi</th>
                        <th>Actions</th>
                        <th>absences</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $etudiant) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['identification_scolaire']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['mode_suivi']); ?></td>
                            <td class="ms">
                                <a href="edit.php?id=<?php echo $etudiant['id']; ?>">Modifier</a>
                                <a href="delete.php?id=<?php echo $etudiant['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">Supprimer</a>
                            </td>
                            <td>
                                <a href="etudiant_abs.php?id=<?php echo $etudiant['id']; ?>">absences</a>
                            </td>
                            <td>
                                <a href="etudiant_note.php?id=<?php echo $etudiant['id']; ?>">notes</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
