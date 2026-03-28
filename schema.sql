-- Base de données pour le système de gestion de laboratoire médical

CREATE DATABASE IF NOT EXISTS labo_medical;
USE labo_medical;

-- Table des médecins
CREATE TABLE medecin (
    id_medecin INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- Table des patients
CREATE TABLE patient (
    id_patient INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20)
);

-- Table des prélèvements
CREATE TABLE prelevement (
    id_prelevement INT AUTO_INCREMENT PRIMARY KEY,
    id_patient INT NOT NULL,
    type_analyse VARCHAR(100) NOT NULL,
    resultat TEXT,
    date_prelevement DATE NOT NULL,
    FOREIGN KEY (id_patient) REFERENCES patient(id_patient) ON DELETE CASCADE
);

-- Insertion de données de test
-- Mots de passe en clair pour faciliter les tests (remplacer par hash en production)
INSERT INTO medecin (nom, prenom, email, mot_de_passe) VALUES
('Mohamed', 'Arafat', 'mohamed@labo.com', 'test123'),
('Martin', 'Marie', 'marie.martin@labo.com', 'test123');

INSERT INTO patient (nom, prenom, email, mot_de_passe, telephone) VALUES
('Alice', 'Marie', 'alice@email.com', 'test123', '0123456789'),
('Leroy', 'Sophie', 'sophie.leroy@email.com', 'test123', '0987654321');

INSERT INTO prelevement (id_patient, type_analyse, resultat, date_prelevement) VALUES
(1, 'Analyse sanguine', 'Hémoglobine: 14g/dL, Leucocytes: 7000/mm³', '2023-10-01'),
(1, 'Analyse d\'urine', 'pH: 6.5, Protéines: négatif', '2023-10-02'),
(2, 'Bilan lipidique', 'Cholestérol total: 2.1g/L', '2023-10-03');