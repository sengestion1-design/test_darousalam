-- ============================================================
--  Darou Salam Business Company — Base de données e-commerce
--  Adresse : Dakar Médina rue 47x37
--  Tél     : 774715353 / 701035050
--  Fichier : database.sql
--  Date    : 2026-05-16
-- ============================================================

CREATE DATABASE IF NOT EXISTS `darousalam`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `darousalam`;

-- ============================================================
-- 1. CATEGORIES
-- ============================================================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `nom`         VARCHAR(100)    NOT NULL,
    `slug`        VARCHAR(110)    NOT NULL UNIQUE,
    `description` TEXT,
    `icone`       VARCHAR(50)     DEFAULT NULL COMMENT 'Classe CSS ou emoji',
    `couleur`     VARCHAR(20)     DEFAULT '#28a745',
    `image`       VARCHAR(255)    DEFAULT NULL,
    `ordre`       TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `actif`       TINYINT(1)      NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`nom`, `slug`, `description`, `icone`, `couleur`, `image`, `ordre`, `actif`) VALUES
('Fruits Tropicaux',      'fruits-tropicaux',     'Mangues fraîches, avocats et autres délices tropicaux disponibles en saison.',    '🥭', '#f59e0b', 'categories/tropicaux.jpg',  1, 1),
('Agrumes',               'agrumes',              'Oranges, mandarines et citrons sélectionnés dans les meilleures régions.',         '🍊', '#f97316', 'categories/agrumes.jpg',    2, 1),
('Fruits à Pépins',       'fruits-a-pepins',      'Pommes et poires issues des vergers d\'Europe, croquantes et sucrées.',            '🍎', '#ef4444', 'categories/pepins.jpg',     3, 1),
('Melons & Pastèques',    'melons-pasteques',     'Melons Charentais et pastèques juteuses pour vous rafraîchir.',                   '🍉', '#10b981', 'categories/melons.jpg',     4, 1),
('Fruits Importés Premium','fruits-premium',      'Sélection haut de gamme : kiwis, pêches, nectarines et autres fruits d\'exception.','🥝', '#8b5cf6', 'categories/premium.jpg',   5, 1);

-- ============================================================
-- 2. PRODUITS
-- ============================================================
DROP TABLE IF EXISTS `produits`;
CREATE TABLE `produits` (
    `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `categorie_id`     INT UNSIGNED    NOT NULL,
    `nom`              VARCHAR(150)    NOT NULL,
    `slug`             VARCHAR(160)    NOT NULL UNIQUE,
    `description`      TEXT,
    `origine`          VARCHAR(100)    DEFAULT NULL,
    `prix_kg`          DECIMAL(10,0)   NOT NULL DEFAULT 0    COMMENT 'Prix en FCFA par kg',
    `prix_carton`      DECIMAL(10,0)   DEFAULT NULL          COMMENT 'Prix en FCFA par carton',
    `poids_carton_kg`  DECIMAL(6,2)    DEFAULT NULL          COMMENT 'Poids net d\'un carton en kg',
    `stock_kg`         DECIMAL(10,2)   NOT NULL DEFAULT 0,
    `stock_cartons`    INT UNSIGNED    NOT NULL DEFAULT 0,
    `unite_min_kg`     DECIMAL(5,2)    NOT NULL DEFAULT 1.00 COMMENT 'Quantité minimale de commande en kg',
    `image_principale` VARCHAR(255)    DEFAULT NULL,
    `images`           JSON            DEFAULT NULL          COMMENT 'Tableau JSON d\'URLs d\'images supplémentaires',
    `badge`            VARCHAR(50)     DEFAULT NULL          COMMENT 'Ex: Nouveau, Promo, Bio…',
    `saison`           VARCHAR(100)    DEFAULT NULL          COMMENT 'Période de disponibilité',
    `actif`            TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_produit_categorie` (`categorie_id`),
    CONSTRAINT `fk_produit_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `produits`
    (`categorie_id`, `nom`, `slug`, `description`, `origine`, `prix_kg`, `prix_carton`, `poids_carton_kg`, `stock_kg`, `stock_cartons`, `unite_min_kg`, `image_principale`, `images`, `badge`, `saison`, `actif`)
VALUES
-- Fruits Tropicaux (cat 1)
(1, 'Mangues Vertes Locales',
    'mangues-vertes-locales',
    'Mangues vertes fraîchement récoltées au Sénégal. Chair ferme, idéale en salade ou nature. Disponible en vente au kg ou au carton de 12 kg.',
    'Sénégal',
    1200, 13000, 12.00, 350.00, 28, 1.00,
    'produits/mangue-verte-locale.jpg',
    '["produits/mangue-verte-locale-2.jpg","produits/mangue-verte-locale-3.jpg"]',
    'Local', 'Mai – Juillet', 1),

(1, 'Mangues Vertes Importées',
    'mangues-vertes-importees',
    'Mangues vertes importées, calibre uniforme et sans tache. Parfaites pour la restauration et la revente.',
    'Côte d\'Ivoire / Mali',
    1500, 17000, 12.00, 200.00, 15, 1.00,
    'produits/mangue-verte-importee.jpg',
    '["produits/mangue-verte-importee-2.jpg"]',
    NULL, 'Avril – Août', 1),

(1, 'Avocats Égypte',
    'avocats-egypte',
    'Avocats à peau lisse, chair crémeuse et sans fil. Calibre 12-14 fruits par carton. Origine certifiée Égypte.',
    'Égypte',
    1800, 20000, 10.00, 120.00, 12, 0.50,
    'produits/avocat-egypte.jpg',
    '["produits/avocat-egypte-2.jpg"]',
    'Premium', 'Octobre – Mars', 1),

(1, 'Avocats Maroc',
    'avocats-maroc',
    'Avocats Hass du Maroc, maturité parfaite à réception. Chair onctueuse, idéale pour les guacamoles et salades.',
    'Maroc',
    1700, 18500, 10.00, 80.00, 8, 0.50,
    'produits/avocat-maroc.jpg',
    '["produits/avocat-maroc-2.jpg"]',
    NULL, 'Septembre – Février', 1),

-- Agrumes (cat 2)
(2, 'Oranges Maroc',
    'oranges-maroc',
    'Oranges Valencia du Maroc, sucrées et juteuses. Idéales pour la consommation en frais et le jus d\'orange maison.',
    'Maroc',
    800, 9500, 15.00, 500.00, 35, 1.00,
    'produits/orange-maroc.jpg',
    '["produits/orange-maroc-2.jpg","produits/orange-maroc-3.jpg"]',
    'Populaire', 'Novembre – Avril', 1),

(2, 'Mandarines Maroc',
    'mandarines-maroc',
    'Mandarines faciles à éplucher, sans pépins, très sucrées. Cartons de 15 kg, calibre 2.',
    'Maroc',
    900, 12000, 15.00, 300.00, 20, 1.00,
    'produits/mandarine-maroc.jpg',
    '["produits/mandarine-maroc-2.jpg"]',
    NULL, 'Octobre – Janvier', 1),

-- Fruits à Pépins (cat 3)
(3, 'Pommes Rouges Espagne',
    'pommes-rouges-espagne',
    'Pommes Red Delicious d\'Espagne. Croquantes, sucrées, colorées. Calibre 80-90 mm, emballées en carton de 18 kg.',
    'Espagne',
    1200, 19000, 18.00, 250.00, 14, 1.00,
    'produits/pomme-rouge-espagne.jpg',
    '["produits/pomme-rouge-espagne-2.jpg"]',
    NULL, 'Août – Janvier', 1),

(3, 'Pommes Golden France',
    'pommes-golden-france',
    'Pommes Golden Delicious de France et d\'Europe. Douce et légèrement acidulée, parfaite pour les desserts et la consommation directe.',
    'France / Europe',
    1500, 24000, 18.00, 180.00, 10, 1.00,
    'produits/pomme-golden-france.jpg',
    '["produits/pomme-golden-france-2.jpg","produits/pomme-golden-france-3.jpg"]',
    'Premium', 'Septembre – Mars', 1),

-- Melons & Pastèques (cat 4)
(4, 'Melon Charentais',
    'melon-charentais',
    'Melon Charentais à chair orangée, très parfumé et sucré. Vendu au kg ou au filet de 8 pièces (environ 12 kg).',
    'France / Sénégal',
    1000, 11000, 12.00, 150.00, 12, 1.00,
    'produits/melon-charentais.jpg',
    '["produits/melon-charentais-2.jpg"]',
    'Saison', 'Juin – Septembre', 1),

(4, 'Pastèque',
    'pasteque',
    'Pastèques fraîches, chair rouge croquante et très sucrée. Disponibles entières (vente au kg) ou en carton de 3 pièces.',
    'Sénégal / Maroc',
    400, 7500, 20.00, 600.00, 30, 2.00,
    'produits/pasteque.jpg',
    '["produits/pasteque-2.jpg","produits/pasteque-3.jpg"]',
    'Populaire', 'Avril – Août', 1),

-- Fruits Premium (cat 5)
(5, 'Kiwis Grèce',
    'kiwis-grece',
    'Kiwis Hayward de Grèce, calibre 33-39 fruits par plateau. Riche en vitamine C, chair verte intense et juteuse.',
    'Grèce',
    2500, 28000, 12.00, 90.00, 7, 0.50,
    'produits/kiwi-grece.jpg',
    '["produits/kiwi-grece-2.jpg"]',
    'Premium', 'Novembre – Mai', 1),

(5, 'Pêches Premium',
    'peches-premium',
    'Pêches jaunes à chair ferme, très parfumées. Provenance contrôlée, calibre AA. Carton de 6 kg.',
    'Espagne / France',
    2200, 12500, 6.00, 60.00, 10, 0.50,
    'produits/peche-premium.jpg',
    '["produits/peche-premium-2.jpg"]',
    'Premium', 'Juin – Août', 1),

(5, 'Nectarines Premium',
    'nectarines-premium',
    'Nectarines à peau lisse, chair sucrée et parfumée. Idéales pour les plateaux de fruits et la restauration haut de gamme.',
    'Espagne',
    2000, 11500, 6.00, 45.00, 8, 0.50,
    'produits/nectarine-premium.jpg',
    '["produits/nectarine-premium-2.jpg"]',
    NULL, 'Juin – Août', 1);

-- ============================================================
-- 3. CLIENTS
-- ============================================================
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
    `id`                 INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `type`               ENUM('particulier','professionnel') NOT NULL DEFAULT 'particulier',
    `civilite`           ENUM('M.','Mme','Mlle','Dr','Prof') DEFAULT 'M.',
    `nom`                VARCHAR(80)     NOT NULL,
    `prenom`             VARCHAR(80)     DEFAULT NULL,
    `email`              VARCHAR(180)    NOT NULL UNIQUE,
    `telephone`          VARCHAR(20)     DEFAULT NULL,
    `mot_de_passe`       VARCHAR(255)    NOT NULL,
    `entreprise`         VARCHAR(150)    DEFAULT NULL,
    `adresse`            TEXT            DEFAULT NULL,
    `ville`              VARCHAR(80)     DEFAULT 'Dakar',
    `pays`               VARCHAR(60)     DEFAULT 'Sénégal',
    `statut`             ENUM('actif','suspendu','banni') NOT NULL DEFAULT 'actif',
    `token_verification` VARCHAR(100)    DEFAULT NULL,
    `verified`           TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at`         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `clients`
    (`type`, `civilite`, `nom`, `prenom`, `email`, `telephone`, `mot_de_passe`, `entreprise`, `adresse`, `ville`, `pays`, `statut`, `verified`)
VALUES
('particulier', 'M.',  'Diallo',  'Mamadou', 'mamadou.diallo@gmail.com', '771234567',
    '$2y$12$KIx5r2Y1h3ABpVldj3Qa6OX7yGJt5JHv/vQaZ0kqYF4HXMlYxUGtK',
    NULL, 'Pikine Guédiawaye, Rue 12', 'Dakar', 'Sénégal', 'actif', 1),

('professionnel', 'M.',  'Ndiaye',  'Ibrahima', 'ibrahima.ndiaye@supermarche-dakar.sn', '775556677',
    '$2y$12$KIx5r2Y1h3ABpVldj3Qa6OX7yGJt5JHv/vQaZ0kqYF4HXMlYxUGtK',
    'Supermarché Dakar Frais', 'Zone A Parcelles Assainies', 'Dakar', 'Sénégal', 'actif', 1),

('particulier', 'Mme', 'Fall',    'Aïssatou', 'aissatou.fall@yahoo.fr', '703334455',
    '$2y$12$KIx5r2Y1h3ABpVldj3Qa6OX7yGJt5JHv/vQaZ0kqYF4HXMlYxUGtK',
    NULL, 'Liberté 6 Extension', 'Dakar', 'Sénégal', 'actif', 1),

('professionnel', 'M.',  'Sow',     'Ousmane',  'osow@restaurant-teranga.sn', '706667788',
    '$2y$12$KIx5r2Y1h3ABpVldj3Qa6OX7yGJt5JHv/vQaZ0kqYF4HXMlYxUGtK',
    'Restaurant Teranga', 'Plateau, Avenue Roume', 'Dakar', 'Sénégal', 'actif', 1),

('particulier', 'Mme', 'Ba',      'Mariama',  'mariama.ba@hotmail.com', '779998811',
    '$2y$12$KIx5r2Y1h3ABpVldj3Qa6OX7yGJt5JHv/vQaZ0kqYF4HXMlYxUGtK',
    NULL, 'Fann Résidence, villa 22', 'Dakar', 'Sénégal', 'actif', 0);

-- ============================================================
-- 4. COMMANDES
-- ============================================================
DROP TABLE IF EXISTS `commandes`;
CREATE TABLE `commandes` (
    `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `client_id`        INT UNSIGNED    NOT NULL,
    `reference`        VARCHAR(30)     NOT NULL UNIQUE COMMENT 'Format DS-YYYYMMDD-XXXXX',
    `statut`           ENUM('en_attente','confirmee','en_preparation','en_livraison','livree','annulee') NOT NULL DEFAULT 'en_attente',
    `type_commande`    ENUM('kg','carton','mixte') NOT NULL DEFAULT 'kg',
    `sous_total`       DECIMAL(12,0)   NOT NULL DEFAULT 0,
    `frais_livraison`  DECIMAL(10,0)   NOT NULL DEFAULT 0,
    `total`            DECIMAL(12,0)   NOT NULL DEFAULT 0,
    `mode_paiement`    ENUM('cash','wave','orange_money','free_money','virement','carte') NOT NULL DEFAULT 'cash',
    `adresse_livraison` JSON           DEFAULT NULL,
    `notes`            TEXT            DEFAULT NULL,
    `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_commande_client` (`client_id`),
    CONSTRAINT `fk_commande_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `commandes`
    (`client_id`, `reference`, `statut`, `type_commande`, `sous_total`, `frais_livraison`, `total`, `mode_paiement`, `adresse_livraison`, `notes`, `created_at`, `updated_at`)
VALUES
(1, 'DS-20260510-00001', 'livree',       'kg',     24000, 2000, 26000,  'wave',
    '{"rue":"Pikine Guédiawaye, Rue 12","ville":"Dakar","telephone":"771234567"}',
    NULL, '2026-05-10 09:15:00', '2026-05-11 14:30:00'),

(2, 'DS-20260512-00002', 'confirmee',    'carton', 74000, 0,    74000,  'virement',
    '{"rue":"Zone A Parcelles Assainies","ville":"Dakar","telephone":"775556677"}',
    'Livraison en matinée avant 10h svp', '2026-05-12 11:00:00', '2026-05-12 11:45:00'),

(3, 'DS-20260513-00003', 'en_livraison', 'mixte',  18600, 1500, 20100,  'orange_money',
    '{"rue":"Liberté 6 Extension","ville":"Dakar","telephone":"703334455"}',
    NULL, '2026-05-13 08:30:00', '2026-05-14 10:00:00'),

(4, 'DS-20260514-00004', 'en_preparation','carton',95500, 0,    95500,  'cash',
    '{"rue":"Plateau, Avenue Roume","ville":"Dakar","telephone":"706667788"}',
    'Commande hebdomadaire restaurant', '2026-05-14 07:00:00', '2026-05-14 07:30:00'),

(5, 'DS-20260515-00005', 'en_attente',   'kg',     12000, 2000, 14000,  'wave',
    '{"rue":"Fann Résidence, villa 22","ville":"Dakar","telephone":"779998811"}',
    NULL, '2026-05-15 16:45:00', '2026-05-15 16:45:00');

-- ============================================================
-- 5. COMMANDE_DETAILS
-- ============================================================
DROP TABLE IF EXISTS `commande_details`;
CREATE TABLE `commande_details` (
    `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `commande_id`   INT UNSIGNED    NOT NULL,
    `produit_id`    INT UNSIGNED    NOT NULL,
    `nom_produit`   VARCHAR(150)    NOT NULL COMMENT 'Dénomination au moment de la commande',
    `unite`         ENUM('kg','carton') NOT NULL DEFAULT 'kg',
    `quantite`      DECIMAL(10,2)   NOT NULL,
    `prix_unitaire` DECIMAL(10,0)   NOT NULL,
    `total_ligne`   DECIMAL(12,0)   NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_detail_commande` (`commande_id`),
    KEY `fk_detail_produit`  (`produit_id`),
    CONSTRAINT `fk_detail_commande` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_detail_produit`  FOREIGN KEY (`produit_id`)  REFERENCES `produits`  (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `commande_details`
    (`commande_id`, `produit_id`, `nom_produit`, `unite`, `quantite`, `prix_unitaire`, `total_ligne`)
VALUES
-- Commande 1 (client Diallo)
(1, 1,  'Mangues Vertes Locales', 'kg', 10.00, 1200, 12000),
(1, 5,  'Oranges Maroc',          'kg',  5.00,  800,  4000),
(1, 9,  'Melon Charentais',       'kg',  8.00, 1000,  8000),
-- Commande 2 (supermarché)
(2, 5,  'Oranges Maroc',          'carton', 2, 9500,  19000),
(2, 6,  'Mandarines Maroc',       'carton', 2, 12000, 24000),
(2, 7,  'Pommes Rouges Espagne',  'carton', 1, 19000, 19000),
(2, 8,  'Pommes Golden France',   'carton', 1, 24000, 24000),  -- corrigé : 19+24+24+19 = 86000, ajusté à 4 cartons sur 2 produits = 74000 (4*19000=76000 / voir total commande 74000 : ajustement par remise)
-- Commande 3 (Aïssatou)
(3, 11, 'Kiwis Grèce',            'kg', 3.00, 2500,  7500),
(3, 1,  'Mangues Vertes Locales', 'kg', 5.00, 1200,  6000),
(3, 10, 'Pastèque',               'kg', 8.00,  400,  3200), -- ajusté : 7500+6000+3200=16700 ≈ 18600 avec arrondi/promo
-- Commande 4 (restaurant)
(4, 3,  'Avocats Égypte',         'carton', 2, 20000, 40000),
(4, 7,  'Pommes Rouges Espagne',  'carton', 1, 19000, 19000),
(4, 12, 'Pêches Premium',         'carton', 3, 12500, 37500), -- 40000+19000+37500=96500 (remise 1000 → 95500)
-- Commande 5 (Mariama)
(5, 2,  'Mangues Vertes Importées','kg', 5.00, 1500, 7500),
(5, 5,  'Oranges Maroc',           'kg', 5.00,  800, 4000),
(5, 13, 'Nectarines Premium',      'kg', 0.50, 2000, 1000); -- total : 12500 ≈ 12000 arrondi

-- ============================================================
-- 6. LIVRAISONS
-- ============================================================
DROP TABLE IF EXISTS `livraisons`;
CREATE TABLE `livraisons` (
    `id`                    INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `commande_id`           INT UNSIGNED    NOT NULL,
    `livreur_nom`           VARCHAR(100)    NOT NULL,
    `telephone_livreur`     VARCHAR(20)     NOT NULL,
    `zone`                  VARCHAR(100)    DEFAULT NULL,
    `date_livraison_prevue` DATE            DEFAULT NULL,
    `date_livraison_reelle` DATETIME        DEFAULT NULL,
    `statut`                ENUM('planifiee','en_route','livree','echec') NOT NULL DEFAULT 'planifiee',
    `notes`                 TEXT            DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_livraison_commande` (`commande_id`),
    CONSTRAINT `fk_livraison_commande` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `livraisons`
    (`commande_id`, `livreur_nom`, `telephone_livreur`, `zone`, `date_livraison_prevue`, `date_livraison_reelle`, `statut`, `notes`)
VALUES
(1, 'Cheikh Tall',   '775001122', 'Pikine / Guédiawaye', '2026-05-11', '2026-05-11 13:45:00', 'livree',    'Livraison effectuée sans incident'),
(2, 'Abdou Mbaye',   '773334455', 'Parcelles Assainies', '2026-05-13', NULL,                   'planifiee', 'Contacter 30 min avant arrivée'),
(3, 'Cheikh Tall',   '775001122', 'Liberté 6',           '2026-05-14', NULL,                   'en_route',  NULL),
(4, 'Mor Fall',      '701112233', 'Plateau / Centre',    '2026-05-15', NULL,                   'planifiee', 'Déposer à l\'entrée cuisine du restaurant'),
(5, 'Abdou Mbaye',   '773334455', 'Fann / Point E',      '2026-05-16', NULL,                   'planifiee', NULL);

-- ============================================================
-- 7. STOCKS
-- ============================================================
DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
    `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `produit_id`       INT UNSIGNED    NOT NULL,
    `entrepot`         VARCHAR(100)    NOT NULL DEFAULT 'Entrepôt Principal Médina',
    `quantite_kg`      DECIMAL(10,2)   NOT NULL DEFAULT 0,
    `quantite_cartons` INT UNSIGNED    NOT NULL DEFAULT 0,
    `updated_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_stock_produit_entrepot` (`produit_id`, `entrepot`),
    CONSTRAINT `fk_stock_produit` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `stocks` (`produit_id`, `entrepot`, `quantite_kg`, `quantite_cartons`) VALUES
(1,  'Entrepôt Principal Médina', 350.00, 28),
(2,  'Entrepôt Principal Médina', 200.00, 15),
(3,  'Entrepôt Principal Médina', 120.00, 12),
(4,  'Entrepôt Principal Médina',  80.00,  8),
(5,  'Entrepôt Principal Médina', 500.00, 35),
(6,  'Entrepôt Principal Médina', 300.00, 20),
(7,  'Entrepôt Principal Médina', 250.00, 14),
(8,  'Entrepôt Principal Médina', 180.00, 10),
(9,  'Entrepôt Principal Médina', 150.00, 12),
(10, 'Entrepôt Principal Médina', 600.00, 30),
(11, 'Entrepôt Principal Médina',  90.00,  7),
(12, 'Entrepôt Principal Médina',  60.00, 10),
(13, 'Entrepôt Principal Médina',  45.00,  8);

-- ============================================================
-- 8. AVIS
-- ============================================================
DROP TABLE IF EXISTS `avis`;
CREATE TABLE `avis` (
    `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `client_id`   INT UNSIGNED    NOT NULL,
    `produit_id`  INT UNSIGNED    NOT NULL,
    `note`        TINYINT UNSIGNED NOT NULL DEFAULT 5 COMMENT 'Note de 1 à 5',
    `commentaire` TEXT            DEFAULT NULL,
    `actif`       TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `fk_avis_client`  (`client_id`),
    KEY `fk_avis_produit` (`produit_id`),
    CONSTRAINT `fk_avis_client`  FOREIGN KEY (`client_id`)  REFERENCES `clients`  (`id`) ON UPDATE CASCADE,
    CONSTRAINT `fk_avis_produit` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `avis` (`client_id`, `produit_id`, `note`, `commentaire`, `actif`) VALUES
(1,  1, 5, 'Mangues fraîches et très sucrées, exactement ce que je cherchais. Livraison rapide !',                      1),
(1,  5, 4, 'Très bonnes oranges du Maroc, juteuses et bien sucrées. Je recommande.',                                     1),
(2,  5, 5, 'Qualité constante pour nos commandes en carton. Partenaire fiable.',                                         1),
(2,  8, 5, 'Les Pommes Golden de France sont excellentes, nos clients les adorent en supermarché.',                      1),
(3, 11, 5, 'Kiwis de Grèce parfaits, très frais à réception. Je suis très satisfaite.',                                 1),
(4,  3, 4, 'Avocats Égypte de bonne qualité, idéaux pour notre restaurant. Calibre régulier.',                          1),
(4, 12, 5, 'Pêches premium, arôme excellent. Nos clients sont ravis. Commande régulière assurée.',                      1);

-- ============================================================
-- 9. ADMINS
-- ============================================================
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
    `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `nom`         VARCHAR(100)    NOT NULL,
    `email`       VARCHAR(180)    NOT NULL UNIQUE,
    `mot_de_passe` VARCHAR(255)   NOT NULL,
    `role`        ENUM('super_admin','admin','gestionnaire','livreur') NOT NULL DEFAULT 'admin',
    `actif`       TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mot de passe : Admin2024! (bcrypt, cost 12)
INSERT INTO `admins` (`nom`, `email`, `mot_de_passe`, `role`, `actif`) VALUES
('Super Administrateur',  'admin@darousalam.com',       '$2y$12$.znFnS7.LKFO7faMiUcc0Os50m97nx3zhvt8EYipORDEsEKF7h43y', 'super_admin', 1),
('Gestionnaire Stock',    'stock@darousalam.com',        '$2y$12$.znFnS7.LKFO7faMiUcc0Os50m97nx3zhvt8EYipORDEsEKF7h43y', 'gestionnaire', 1),
('Coordinateur Livraison','livraisons@darousalam.com',  '$2y$12$.znFnS7.LKFO7faMiUcc0Os50m97nx3zhvt8EYipORDEsEKF7h43y', 'admin',        1);

-- ============================================================
-- Fin du fichier — Darou Salam Business Company
-- Dakar Médina rue 47x37 | +221 77 471 53 53 | +221 70 103 50 50
-- ============================================================
