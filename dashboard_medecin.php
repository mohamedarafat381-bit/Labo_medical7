<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';
requireLogin();
requireMedecin();

// Gestion des patients
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_patient'])) {
        $nom = sanitize($_POST['nom']);
        $prenom = sanitize($_POST['prenom']);
        $email = sanitize($_POST['email']);
        $telephone = sanitize($_POST['telephone']);
        $password = hashPassword($_POST['password']);

        $stmt = $pdo->prepare("INSERT INTO patient (nom, prenom, email, mot_de_passe, telephone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $password, $telephone]);
    } elseif (isset($_POST['edit_patient'])) {
        $id = $_POST['id_patient'];
        $nom = sanitize($_POST['nom']);
        $prenom = sanitize($_POST['prenom']);
        $email = sanitize($_POST['email']);
        $telephone = sanitize($_POST['telephone']);

        // Gestion du mot de passe optionnel
        if (!empty($_POST['password'])) {
            $password = hashPassword($_POST['password']);
            $stmt = $pdo->prepare("UPDATE patient SET nom = ?, prenom = ?, email = ?, telephone = ?, mot_de_passe = ? WHERE id_patient = ?");
            $stmt->execute([$nom, $prenom, $email, $telephone, $password, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE patient SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id_patient = ?");
            $stmt->execute([$nom, $prenom, $email, $telephone, $id]);
        }
    } elseif (isset($_POST['delete_patient'])) {
        $id = $_POST['id_patient'];
        $stmt = $pdo->prepare("DELETE FROM patient WHERE id_patient = ?");
        $stmt->execute([$id]);
    } elseif (isset($_POST['add_prelevement'])) {
        $id_patient = $_POST['id_patient'];
        $type_analyse = sanitize($_POST['type_analyse']);
        $resultat = sanitize($_POST['resultat']);
        $date_prelevement = $_POST['date_prelevement'];

        $stmt = $pdo->prepare("INSERT INTO prelevement (id_patient, type_analyse, resultat, date_prelevement) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_patient, $type_analyse, $resultat, $date_prelevement]);
    } elseif (isset($_POST['edit_prelevement'])) {
        $id = $_POST['id_prelevement'];
        $type_analyse = sanitize($_POST['type_analyse']);
        $resultat = sanitize($_POST['resultat']);
        $date_prelevement = $_POST['date_prelevement'];

        $stmt = $pdo->prepare("UPDATE prelevement SET type_analyse = ?, resultat = ?, date_prelevement = ? WHERE id_prelevement = ?");
        $stmt->execute([$type_analyse, $resultat, $date_prelevement, $id]);
    } elseif (isset($_POST['delete_prelevement'])) {
        $id = $_POST['id_prelevement'];
        $stmt = $pdo->prepare("DELETE FROM prelevement WHERE id_prelevement = ?");
        $stmt->execute([$id]);
    }
}

// Récupération des patients
$stmt = $pdo->query("SELECT * FROM patient ORDER BY nom, prenom");
$patients = $stmt->fetchAll();

// Récupération des prélèvements pour un patient spécifique
$prelevements = [];
$selected_patient = null;
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $stmt = $pdo->prepare("SELECT * FROM patient WHERE id_patient = ?");
    $stmt->execute([$patient_id]);
    $selected_patient = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM prelevement WHERE id_patient = ? ORDER BY date_prelevement DESC");
    $stmt->execute([$patient_id]);
    $prelevements = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Médecin - Laboratoire Médical</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">🩺 Laboratoire Médical</div>
            <ul>
                <li><a href="dashboard_medecin.php">Patients</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard">
        <div class="container">
            <h1>Bonjour Dr. <?php echo $_SESSION['user_name']; ?></h1>

            <div class="dashboard-grid">
                <div class="sidebar">
                    <h3>Actions</h3>
                    <ul>
                        <li><a href="#" onclick="openModal('add-patient-modal')">➕ Ajouter Patient</a></li>
                        <li><a href="dashboard_medecin.php">📋 Liste Patients</a></li>
                    </ul>
                </div>

                <div class="main-content">
                    <?php if (!$selected_patient): ?>
                        <h2>Gestion des Patients</h2>
                        <div class="search-container">
                            <input type="text" id="search" placeholder="Rechercher un patient...">
                        </div>
                        <table class="table" id="patients-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($patients as $patient): ?>
                                    <tr>
                                        <td><?php echo $patient['id_patient']; ?></td>
                                        <td><?php echo $patient['nom']; ?></td>
                                        <td><?php echo $patient['prenom']; ?></td>
                                        <td><?php echo $patient['email']; ?></td>
                                        <td><?php echo $patient['telephone']; ?></td>
                                        <td class="actions">
                                            <a href="dashboard_medecin.php?patient_id=<?php echo $patient['id_patient']; ?>" class="btn-small btn-view">Voir</a>
                                            <a href="#" onclick="editPatient(<?php echo $patient['id_patient']; ?>, '<?php echo $patient['nom']; ?>', '<?php echo $patient['prenom']; ?>', '<?php echo $patient['email']; ?>', '<?php echo $patient['telephone']; ?>')" class="btn-small btn-edit">Modifier</a>
                                            <a href="#" onclick="deletePatient(<?php echo $patient['id_patient']; ?>)" class="btn-small btn-delete">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <h2>Prélèvements de <?php echo $selected_patient['prenom'] . ' ' . $selected_patient['nom']; ?></h2>
                        <a href="dashboard_medecin.php" class="btn">← Retour à la liste</a>
                        <button onclick="openModal('add-prelevement-modal')" class="btn" style="margin-left: 10px;">➕ Ajouter Prélèvement</button>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type d'analyse</th>
                                    <th>Résultat</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prelevements as $prelevement): ?>
                                    <tr>
                                        <td><?php echo $prelevement['id_prelevement']; ?></td>
                                        <td><?php echo $prelevement['type_analyse']; ?></td>
                                        <td><?php echo substr($prelevement['resultat'], 0, 50) . '...'; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($prelevement['date_prelevement'])); ?></td>
                                        <td class="actions">
                                            <a href="#" onclick="viewPrelevement('<?php echo $prelevement['resultat']; ?>')" class="btn-small btn-view">Voir</a>
                                            <a href="#" onclick="editPrelevement(<?php echo $prelevement['id_prelevement']; ?>, '<?php echo $prelevement['type_analyse']; ?>', '<?php echo $prelevement['resultat']; ?>', '<?php echo $prelevement['date_prelevement']; ?>')" class="btn-small btn-edit">Modifier</a>
                                            <a href="#" onclick="deletePrelevement(<?php echo $prelevement['id_prelevement']; ?>)" class="btn-small btn-delete">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale Ajouter Patient -->
    <div id="add-patient-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter un Patient</h2>
            <form method="POST" onsubmit="return validatePatientForm()">
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" name="nom" id="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" name="prenom" id="prenom" required>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="telephone">Téléphone :</label>
                    <input type="tel" name="telephone" id="telephone" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <button type="button" onclick="validateAndSubmit()" class="btn-primary" style="font-weight: bold; background: #28a745;">Valider et Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modale Modifier Patient -->
    <div id="edit-patient-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modifier un Patient</h2>
            <form method="POST" onsubmit="return validatePatientForm()">
                <input type="hidden" name="id_patient" id="edit-id-patient">
                <div class="form-group">
                    <label for="edit-nom">Nom :</label>
                    <input type="text" name="nom" id="edit-nom" required>
                </div>
                <div class="form-group">
                    <label for="edit-prenom">Prénom :</label>
                    <input type="text" name="prenom" id="edit-prenom" required>
                </div>
                <div class="form-group">
                    <label for="edit-email">Email :</label>
                    <input type="email" name="email" id="edit-email" required>
                </div>
                <div class="form-group">
                    <label for="edit-telephone">Téléphone :</label>
                    <input type="tel" name="telephone" id="edit-telephone" required>
                </div>
                <div class="form-group">
                    <label for="edit-password">Nouveau mot de passe (laisser vide pour ne pas changer) :</label>
                    <input type="password" name="password" id="edit-password" placeholder="Nouveau mot de passe">
                </div>
                <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" name="edit_patient" class="btn-primary" style="font-weight: bold; background: #ffc107; color: #212529;">Modifier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modale Ajouter Prélèvement -->
    <div id="add-prelevement-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter un Prélèvement</h2>
            <form method="POST" onsubmit="return validatePrelevementForm()">
                <input type="hidden" name="id_patient" value="<?php echo $selected_patient['id_patient']; ?>">
                <div class="form-group">
                    <label for="type_analyse">Type d'analyse :</label>
                    <input type="text" name="type_analyse" id="type_analyse" required>
                </div>
                <div class="form-group">
                    <label for="resultat">Résultat :</label>
                    <textarea name="resultat" id="resultat" required></textarea>
                </div>
                <div class="form-group">
                    <label for="date_prelevement">Date de prélèvement :</label>
                    <input type="date" name="date_prelevement" id="date_prelevement" required>
                </div>
                <button type="submit" name="add_prelevement" class="btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Modale Modifier Prélèvement -->
    <div id="edit-prelevement-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Modifier un Prélèvement</h2>
            <form method="POST" onsubmit="return validatePrelevementForm()">
                <input type="hidden" name="id_prelevement" id="edit-id-prelevement">
                <div class="form-group">
                    <label for="edit-type_analyse">Type d'analyse :</label>
                    <input type="text" name="type_analyse" id="edit-type_analyse" required>
                </div>
                <div class="form-group">
                    <label for="edit-resultat">Résultat :</label>
                    <textarea name="resultat" id="edit-resultat" required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit-date_prelevement">Date de prélèvement :</label>
                    <input type="date" name="date_prelevement" id="edit-date_prelevement" required>
                </div>
                <button type="submit" name="edit_prelevement" class="btn-primary">Modifier</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        function editPatient(id, nom, prenom, email, telephone) {
            document.getElementById('edit-id-patient').value = id;
            document.getElementById('edit-nom').value = nom;
            document.getElementById('edit-prenom').value = prenom;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-telephone').value = telephone;
            openModal('edit-patient-modal');
        }

        function deletePatient(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce patient ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="id_patient" value="' + id + '"><input type="hidden" name="delete_patient" value="1">';
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editPrelevement(id, type, resultat, date) {
            document.getElementById('edit-id-prelevement').value = id;
            document.getElementById('edit-type_analyse').value = type;
            document.getElementById('edit-resultat').value = resultat;
            document.getElementById('edit-date_prelevement').value = date;
            openModal('edit-prelevement-modal');
        }

        function deletePrelevement(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce prélèvement ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="id_prelevement" value="' + id + '"><input type="hidden" name="delete_prelevement" value="1">';
                document.body.appendChild(form);
                form.submit();
            }
        }

        function viewPrelevement(resultat) {
            document.getElementById('prelevement-resultat').textContent = resultat;
            openModal('view-prelevement-modal');
        }

        function validateAndSubmit() {
            if (validatePatientForm()) {
                // Ajouter le champ caché pour indiquer l'action
                const form = document.querySelector('#add-patient-modal form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'add_patient';
                hiddenInput.value = '1';
                form.appendChild(hiddenInput);
                form.submit();
            }
        }
    </script>
</body>
</html>