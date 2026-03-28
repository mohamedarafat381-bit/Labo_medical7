<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';
requireLogin();
requirePatient();

// Récupération des prélèvements du patient
$stmt = $pdo->prepare("SELECT * FROM prelevement WHERE id_patient = ? ORDER BY date_prelevement DESC");
$stmt->execute([$_SESSION['user_id']]);
$prelevements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Patient - Laboratoire Médical</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">🩺 Laboratoire Médical</div>
            <ul>
                <li><a href="dashboard_patient.php">Mes Analyses</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard">
        <div class="container">
            <h1>Bonjour <?php echo $_SESSION['user_name']; ?></h1>

            <div class="main-content">
                <h2>Mes Analyses Médicales</h2>

                <?php if (empty($prelevements)): ?>
                    <p>Vous n'avez pas encore d'analyses enregistrées.</p>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type d'analyse</th>
                                <th>Résultat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prelevements as $prelevement): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($prelevement['date_prelevement'])); ?></td>
                                    <td><?php echo $prelevement['type_analyse']; ?></td>
                                    <td><?php echo substr($prelevement['resultat'], 0, 50) . '...'; ?></td>
                                    <td>
                                        <a href="#" onclick="viewResult('<?php echo addslashes($prelevement['resultat']); ?>')" class="btn-small btn-view">Voir le résultat</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modale Voir Résultat -->
    <div id="view-result-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Résultat de l'Analyse</h2>
            <div id="result-content"></div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        function viewResult(result) {
            document.getElementById('result-content').textContent = result;
            openModal('view-result-modal');
        }
    </script>
</body>
</html>