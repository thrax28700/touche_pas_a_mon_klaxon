-- Schéma de la base de données "Touche pas au klaxon"
-- Compatible MySQL 8+ / MariaDB 10.4+

CREATE DATABASE IF NOT EXISTS touche_pas_au_klaxon
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE touche_pas_au_klaxon;

DROP TABLE IF EXISTS trajets;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS agences;

CREATE TABLE agences (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    UNIQUE KEY uq_agences_nom (nom)
) ENGINE = InnoDB;

CREATE TABLE utilisateurs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('employe', 'admin') NOT NULL DEFAULT 'employe',
    UNIQUE KEY uq_utilisateurs_email (email)
) ENGINE = InnoDB;

CREATE TABLE trajets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    agence_depart_id INT UNSIGNED NOT NULL,
    agence_arrivee_id INT UNSIGNED NOT NULL,
    date_heure_depart DATETIME NOT NULL,
    date_heure_arrivee DATETIME NOT NULL,
    nb_places_total INT UNSIGNED NOT NULL,
    nb_places_disponibles INT UNSIGNED NOT NULL,
    utilisateur_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_trajets_agence_depart FOREIGN KEY (agence_depart_id) REFERENCES agences (id),
    CONSTRAINT fk_trajets_agence_arrivee FOREIGN KEY (agence_arrivee_id) REFERENCES agences (id),
    CONSTRAINT fk_trajets_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id),
    CONSTRAINT chk_trajets_agences_distinctes CHECK (agence_depart_id <> agence_arrivee_id),
    CONSTRAINT chk_trajets_dates CHECK (date_heure_arrivee > date_heure_depart),
    CONSTRAINT chk_trajets_places CHECK (nb_places_disponibles <= nb_places_total),
    INDEX idx_trajets_date_depart (date_heure_depart)
) ENGINE = InnoDB;
