<?php
session_start();

// Fonction pour hasher le mot de passe
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Fonction pour vérifier le mot de passe
function verifyPassword($password, $hash) {
    // Vérification sécurisée par hash
    if (password_verify($password, $hash)) {
        return true;
    }

    // Mode développement : accepter mot de passe en clair si value stockée en clair
    if ($password === $hash) {
        return true;
    }

    return false;
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si c'est un médecin
function isMedecin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'medecin';
}

// Fonction pour vérifier si c'est un patient
function isPatient() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient';
}

// Fonction pour rediriger si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Fonction pour rediriger si pas médecin
function requireMedecin() {
    if (!isMedecin()) {
        header('Location: index.php');
        exit();
    }
}

// Fonction pour rediriger si pas patient
function requirePatient() {
    if (!isPatient()) {
        header('Location: index.php');
        exit();
    }
}

// Fonction pour se déconnecter
function logout() {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Fonction pour nettoyer les entrées
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>