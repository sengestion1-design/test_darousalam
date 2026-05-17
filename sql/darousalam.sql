-- =============================================
-- Darou Salam Business Company - Base de données
-- =============================================

CREATE DATABASE IF NOT EXISTS `darousalam`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `darousalam`;

-- --------------------------
-- Table: categories
-- --------------------------
CREATE TABLE IF NOT EXISTS `categories` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nom`         VARCHAR(150) NOT NULL,
    `description` TEXT,
    `image`       VARCHAR(255) DEFAULT '',
    `parent_id`   INT UNSIGNED DEFAULT NULL,
    `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------
-- Table: produits
-- --------------------------
CREATE TABLE IF NOT EXISTS `produits` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `reference`    VARCHAR(50) DEFAULT '',
    `nom`          VARCHAR(255) NOT NULL,
    `description`  TEXT,
    `prix`         DECIMAL(12,2) NOT NULL DEFAULT 0,
    `prix_promo`   DECIMAL(12,2) DEFAULT NULL,
    `stock`        INT NOT NULL DEFAULT 0,
    `categorie_id` INT UNSIGNED NOT NULL,
    `image`        VARCHAR(255) DEFAULT '',
    `statut`       ENUM('actif','inactif','supprime') DEFAULT 'actif',
    `created_at`   DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`categorie_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------
-- Table: clients
-- --------------------------
CREATE TABLE IF NOT EXISTS `clients` (
    `id`             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nom`            VARCHAR(100) NOT NULL,
    `prenom`         VARCHAR(100) NOT NULL,
    `email`          VARCHAR(255) NOT NULL UNIQUE,
    `telephone`      VARCHAR(20) DEFAULT '',
    `adresse`        VARCHAR(255) DEFAULT '',
    `ville`          VARCHAR(100) DEFAULT '',
    `type_client`    ENUM('particulier','professionnel') DEFAULT 'particulier',
    `nom_entreprise` VARCHAR(200) DEFAULT '',
    `ninea`          VARCHAR(50) DEFAULT '',
    `password`       VARCHAR(255) NOT NULL,
    `statut`         ENUM('actif','inactif','suspendu') DEFAULT 'actif',
    `created_at`     DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------
-- Table: commandes
-- --------------------------
CREATE TABLE IF NOT EXISTS `commandes` (
    `id`                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `reference`           VARCHAR(50) NOT NULL UNIQUE,
    `client_id`           INT UNSIGNED NOT NULL,
    `total`               DECIMAL(12,2) NOT NULL DEFAULT 0,
    `adresse_livraison`   VARCHAR(255) DEFAULT '',
    `ville_livraison`     VARCHAR(100) DEFAULT '',
    `telephone_livraison` VARCHAR(20)  DEFAULT '',
    `mode_paiement`       ENUM('a_la_livraison','orange_money','wave','carte') DEFAULT 'a_la_livraison',
    `statut`              ENUM('en_attente','confirmee','en_preparation','expediee','livree','annulee') DEFAULT 'en_attente',
    `notes`               TEXT,
    `created_at`          DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------
-- Table: commande_lignes
-- --------------------------
CREATE TABLE IF NOT EXISTS `commande_lignes` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `commande_id`   INT UNSIGNED NOT NULL,
    `produit_id`    INT UNSIGNED DEFAULT NULL,
    `nom_produit`   VARCHAR(255) NOT NULL,
    `prix_unitaire` DECIMAL(12,2) NOT NULL,
    `quantite`      INT NOT NULL DEFAULT 1,
    `sous_total`    DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (`commande_id`) REFERENCES `commandes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`produit_id`)  REFERENCES `produits`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------
-- Table: contacts
-- --------------------------
CREATE TABLE IF NOT EXISTS `contacts` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nom`        VARCHAR(150) NOT NULL,
    `email`      VARCHAR(255) NOT NULL,
    `sujet`      VARCHAR(255) DEFAULT '',
    `message`    TEXT NOT NULL,
    `lu`         TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------
-- Données de démonstration
-- --------------------------

INSERT INTO `categories` (`nom`, `description`) VALUES
('Alimentation', 'Produits alimentaires et boissons'),
('Électronique', 'Téléphones, ordinateurs et accessoires'),
('Textile & Mode', 'Vêtements, chaussures et accessoires de mode'),
('Maison & Décoration', 'Articles pour la maison et la décoration'),
('Agriculture', 'Semences, engrais et matériel agricole'),
('Beauté & Santé', 'Cosmétiques, soins et produits de santé');

INSERT INTO `produits` (`reference`, `nom`, `description`, `prix`, `stock`, `categorie_id`) VALUES
('ALI-001', 'Riz parfumé 25kg', 'Riz parfumé de qualité supérieure, sac de 25 kg', 15000, 200, 1),
('ALI-002', 'Huile végétale 5L', 'Huile végétale raffinée, bidon de 5 litres', 6500, 150, 1),
('ALI-003', 'Sucre en poudre 5kg', 'Sucre blanc en poudre, sac de 5 kg', 4000, 300, 1),
('ELEC-001', 'Smartphone Android 4G', 'Smartphone 4G, écran 6.5", 64Go ROM, 4Go RAM', 85000, 30, 2),
('ELEC-002', 'Batterie externe 20000mAh', 'Batterie de secours 20000mAh, charge rapide', 18000, 50, 2),
('TEXT-001', 'Tissu wax 6 yards', 'Tissu wax 100% coton, motifs variés, 6 yards', 12000, 100, 3),
('TEXT-002', 'Boubou brodé homme', 'Boubou brodé main, coton damassé, taille unique', 35000, 40, 3),
('MAIS-001', 'Moustiquaire imprégnée', 'Moustiquaire imprégnée longue durée, 180x200cm', 8500, 80, 4);

-- Client de test (mot de passe : Test1234!)
INSERT INTO `clients` (`nom`, `prenom`, `email`, `telephone`, `adresse`, `ville`, `type_client`, `password`, `statut`) VALUES
('Diallo', 'Mamadou', 'mamadou@test.sn', '771234567', 'Rue 10, Médina', 'Dakar', 'particulier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'actif');
-- Note: le hash ci-dessus correspond au mot de passe 'password' (hash de test Laravel).
-- Pour un vrai hash de 'Test1234!' : le générer via PHP password_hash()
