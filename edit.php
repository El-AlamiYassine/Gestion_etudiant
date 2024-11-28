<?php
// Connexion à la base de données
try {
    $conn = new PDO("mysql:host=localhost;dbname=etudiants_db;port=3306;charset=utf8", 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo 'Erreur de connexion: ' . $e->getMessage();
    exit();
}

// Traitement de la modification d'un étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = htmlspecialchars($_POST['id']);
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

    if (!empty($id) && !empty($nom) && !empty($prenom) && !empty($date_naissance) && !empty($genre) && !empty($nationalite) && !empty($adresse) && !empty($email) && !empty($telephone) && !empty($parent_nom) && !empty($parent_telephone) && !empty($niveau_etudes) && !empty($identification_scolaire) && !empty($duree_programme) && !empty($mode_suivi)) {
        $stmt = $conn->prepare('UPDATE etudiants SET nom = ?, prenom = ?, date_naissance = ?, genre = ?, nationalite = ?, adresse = ?, email = ?, telephone = ?, parent_nom = ?, parent_telephone = ?, niveau_etudes = ?, identification_scolaire = ?, duree_programme = ?, mode_suivi = ? WHERE id = ?');
        $stmt->execute([$nom, $prenom, $date_naissance, $genre, $nationalite, $adresse, $email, $telephone, $parent_nom, $parent_telephone, $niveau_etudes, $identification_scolaire, $duree_programme, $mode_suivi, $id]);
        header("Location: index.php?success=1");
        exit();
    }
}

// Récupération de l'étudiant à modifier
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM etudiants WHERE id = ?');
    $stmt->execute([$id]);
    $etudiant = $stmt->fetch();
    if (!$etudiant) {
        echo 'Étudiant non trouvé';
        exit();
    }
} else {
    echo 'ID de l\'étudiant non spécifié';
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un étudiant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        button {
            width: calc(100% - 10px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Modifier un étudiant</h2>
    <form method="post" action="edit.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($etudiant['id']); ?>">
        <div>
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($etudiant['nom']); ?>" required>
        </div>
        <div>
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($etudiant['prenom']); ?>"
                required>
        </div>
        <div>
            <label for="date_naissance">Date de naissance:</label>
            <input type="date" id="date_naissance" name="date_naissance"
                value="<?php echo htmlspecialchars($etudiant['date_naissance']); ?>" required>
        </div>
        <div>
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($etudiant['genre']); ?>"
                required>
        </div>
        <div>
            <label for="nationalite">Nationalité:</label>
            <input type="text" id="nationalite" name="nationalite"
                value="<?php echo htmlspecialchars($etudiant['nationalite']); ?>" required>
        </div>
        <div>
            <label for="adresse">Adresse:</label>
            <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($etudiant['adresse']); ?>"
                required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($etudiant['email']); ?>"
                required>
        </div>
        <div>
            <label for="telephone">Téléphone:</label>
            <input type="text" id="telephone" name="telephone"
                value="<?php echo htmlspecialchars($etudiant['telephone']); ?>" required>
        </div>
        <div>
            <label for="parent_nom">Nom du parent/tuteur:</label>
            <input type="text" id="parent_nom" name="parent_nom"
                value="<?php echo htmlspecialchars($etudiant['parent_nom']); ?>" required>
        </div>
        <div>
            <label for="parent_telephone">Téléphone du parent/tuteur:</label>
            <input type="text" id="parent_telephone" name="parent_telephone"
                value="<?php echo htmlspecialchars($etudiant['parent_telephone']); ?>" required>
        </div>
        <div>
            <label for="niveau_etudes">Niveau d'études:</label>
            <input type="text" id="niveau_etudes" name="niveau_etudes"
                value="<?php echo htmlspecialchars($etudiant['niveau_etudes']); ?>" required>
        </div>
        <div>
            <label for="identification_scolaire">Identification scolaire:</label>
            <input type="text" id="identification_scolaire" name="identification_scolaire"
                value="<?php echo htmlspecialchars($etudiant['identification_scolaire']); ?>" required>
        </div>
        <div>
            <label for="duree_programme">Durée du programme:</label>
            <input type="text" id="duree_programme" name="duree_programme"
                value="<?php echo htmlspecialchars($etudiant['duree_programme']); ?>" required>
        </div>
        <div>
            <label for="mode_suivi">Mode de suivi:</label>
            <input type="text" id="mode_suivi" name="mode_suivi"
                value="<?php echo htmlspecialchars($etudiant['mode_suivi']); ?>" required>
        </div>
        <button type="submit">Enregistrer</button>
    </form>
</body>

</html>