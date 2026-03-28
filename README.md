# Système Web de Gestion d'un Laboratoire d'Analyses Médicales

## Description

Ce projet est une application web complète pour la gestion d'un laboratoire médical. Il permet aux médecins de gérer les patients et leurs analyses, et aux patients d'accéder à leurs résultats en ligne.

## Fonctionnalités

### Pour les Médecins
- Connexion sécurisée
- Gestion des patients (ajout, modification, suppression)
- Gestion des prélèvements (ajout, modification, suppression)
- Recherche dynamique des patients
- Consultation des résultats

### Pour les Patients
- Connexion sécurisée
- Consultation de leurs analyses
- Accès aux résultats détaillés

## Technologies Utilisées

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP
- **Base de données**: MySQL
- **Sécurité**: Hashage des mots de passe, sessions, protection contre les injections SQL

## Installation

### Prérequis
- Windows 10/11 (ou Linux/Mac)
- Droits administrateur (pour Windows)

### Installation locale avec XAMPP (recommandé)
1. Téléchargez XAMPP depuis https://www.apachefriends.org/
2. Installez XAMPP, puis démarrez Apache et MySQL via le panneau de contrôle XAMPP
3. Copiez le dossier du projet dans `C:\xampp\htdocs\labo_medical`

### Créer la base de données
1. Ouvrez `http://localhost/phpmyadmin`
2. Créez une nouvelle base `labo_medical`
3. Importez `sql/schema.sql`

### Configurer la connexion BD
- Ouvrez `includes/db.php`
- Ajustez `DB_USER` et `DB_PASS` si nécessaire

### Démarrage de l'application
1. Assurez-vous qu'Apache et MySQL sont démarrés
2. Rendez-vous sur `http://localhost/labo_medical/`

### Comptes de test
- Médecin : jean.dupont@labo.com / test123
- Patient : pierre.durand@email.com / test123

### Notes complémentaires
- Pour générer les mots de passe hashés, créez un fichier PHP temporaire :
```php
<?php
echo password_hash('test123', PASSWORD_DEFAULT);
?>
```
- Réutilisez le hash généré dans `sql/schema.sql` pour les comptes de test

### Ancienne méthode (Git)
1. Cloner le projet
   ```bash
   git clone <repository-url>
   cd labo_medical
   ```
2. Placer le dossier dans le répertoire du serveur web
3. S'assurer que PHP et MySQL sont lancés

### Vérification
- URL : `http://localhost/labo_medical/`
- Page d'accueil et connexion accessibles

## Utilisation

1. Accéder à l'application via `http://localhost/labo_medical`
2. Se connecter en tant que médecin ou patient avec les comptes de test

### Comptes de test

**Médecins:**
- Email: jean.dupont@labo.com, Mot de passe: test123
- Email: marie.martin@labo.com, Mot de passe: test123

**Patients:**
- Email: pierre.durand@email.com, Mot de passe: test123
- Email: sophie.leroy@email.com, Mot de passe: test123

## Structure du Projet

```
labo_medical/
├── index.php              # Page d'accueil
├── login.php              # Page de connexion
├── dashboard_medecin.php  # Tableau de bord médecin
├── dashboard_patient.php  # Tableau de bord patient
├── logout.php             # Déconnexion
├── includes/
│   ├── db.php            # Configuration base de données
│   └── functions.php     # Fonctions utilitaires
├── css/
│   └── style.css         # Styles CSS
├── js/
│   └── script.js         # JavaScript
└── sql/
    └── schema.sql        # Schéma base de données
```

## Sécurité

- Hashage des mots de passe avec `password_hash()`
- Utilisation de requêtes préparées pour éviter les injections SQL
- Gestion des sessions PHP
- Validation des entrées utilisateur

## Diagrammes UML

### Diagramme de Cas d'Utilisation

```
+-----------------------------------+
|           Utilisateur            |
+-----------------------------------+
| - Se connecter                   |
| - Se déconnecter                 |
+-----------------------------------+
            /\
           /  \
          /    \
         /      \
+-----------------------------------+    +-----------------------------------+
|             Médecin               |    |             Patient              |
+-----------------------------------+    +-----------------------------------+
| - Gérer les patients             |    | - Consulter ses analyses         |
| - Gérer les prélèvements         |    | - Voir les résultats            |
| - Rechercher des patients        |    +-----------------------------------+
+-----------------------------------+
```

### Diagramme de Classes

```
+-------------------+     +-------------------+     +-------------------+
|     Medecin       |     |     Patient       |     |   Prelevement     |
+-------------------+     +-------------------+     +-------------------+
| - id_medecin      |     | - id_patient      |     | - id_prelevement  |
| - nom             |     | - nom             |     | - id_patient      |
| - prenom          |     | - prenom          |     | - type_analyse    |
| - email           |     | - email           |     | - resultat        |
| - mot_de_passe    |     | - mot_de_passe    |     | - date_prelevement|
+-------------------+     +-------------------+     +-------------------+
                                                        |
                                                        |
                                                        v
                                               +-------------------+
                                               |   Patient         |
                                               +-------------------+
```

## Évolutions Possibles

- Téléchargement PDF des résultats
- Notifications par email
- Ajout d'un rôle administrateur
- Historique médical complet
- API REST pour intégration mobile

## Auteur

Projet réalisé dans le cadre d'un cahier de charges pour un système de gestion de laboratoire médical.

## Licence

Ce projet est fourni tel quel, sans garantie.