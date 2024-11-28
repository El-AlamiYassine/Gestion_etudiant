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


// Traitement de la connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $identification_scolaire = htmlspecialchars($_POST['identification_scolaire']);

    // Rechercher l'étudiant dans la base de données
    $stmt = $conn->prepare('SELECT id FROM etudiants WHERE email = ? AND identification_scolaire = ?');
    $stmt->execute([$email, $identification_scolaire]);
    $etudiant = $stmt->fetch();

    if ($etudiant) {
        $_SESSION['etudiant_id'] = $etudiant['id'];
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Email ou identification scolaire incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Étudiant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .formField {
            margin-bottom: 15px;
            text-align: left;
        }

        .formField label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .formField input {
            width: calc(100% - 10px);
            padding: 10px;
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
            width: 100%;
        }

        .button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion Étudiant</h2>
        <?php if (isset($error)) : ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="formField">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="formField">
                <label for="identification_scolaire">Identification scolaire:</label>
                <input type="text" id="identification_scolaire" name="identification_scolaire" required>
            </div>
            <button type="submit" class="button">Connexion</button>
        </form>
    </div>
</body>
</html>
