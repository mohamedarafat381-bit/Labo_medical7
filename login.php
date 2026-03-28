<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    if ($userType === 'medecin') {
        $stmt = $pdo->prepare("SELECT * FROM medecin WHERE email = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE email = ?");
    }

    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && verifyPassword($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user[$userType === 'medecin' ? 'id_medecin' : 'id_patient'];
        $_SESSION['user_type'] = $userType;
        $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];

        if ($userType === 'medecin') {
            header('Location: dashboard_medecin.php');
        } else {
            header('Location: dashboard_patient.php');
        }
        exit();
    } else {
        $message = 'Email ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Laboratoire Médical</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">🩺 Laboratoire Médical</div>
            <ul>
                <li><a href="index.php">Accueil</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="form-container">
            <h2>Connexion</h2>
            <?php if ($message): ?>
                <p style="color: red;"><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="user_type">Type d'utilisateur :</label>
                    <select name="user_type" id="user_type" required>
                        <option value="medecin">Médecin</option>
                        <option value="patient">Patient</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn-primary">Se connecter</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>