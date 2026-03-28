<?php
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratoire Médical - Accueil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">🩺 Laboratoire Médical</div>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="login.php">Connexion Médecin</a></li>
                <li><a href="login.php">Connexion Patient</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Bienvenue au Laboratoire Médical</h1>
            <p>Vos analyses médicales en toute sécurité et confidentialité</p>
            <a href="login.php" class="btn">Connexion Médecin</a>
            <a href="login.php" class="btn">Connexion Patient</a>
        </div>
    </section>

    <section class="services">
        <div class="container">
            <h2>Nos Services</h2>
            <div class="service-grid">
                <div class="service-card">
                    <h3>Analyses Sanguines</h3>
                    <p>Tests complets pour évaluer votre état de santé général.</p>
                </div>
                <div class="service-card">
                    <h3>Analyses d'Urine</h3>
                    <p>Dépistage et diagnostic de diverses pathologies.</p>
                </div>
                <div class="service-card">
                    <h3>Bilans Spécialisés</h3>
                    <p>Examens spécialisés pour un suivi personnalisé.</p>
                </div>
                <div class="service-card">
                    <h3>Consultation en Ligne</h3>
                    <p>Accédez à vos résultats en toute sécurité.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Laboratoire Médical. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>