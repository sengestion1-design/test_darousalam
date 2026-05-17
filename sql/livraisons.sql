CREATE TABLE IF NOT EXISTS livreurs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  telephone VARCHAR(20) NOT NULL,
  zone VARCHAR(100),
  actif TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS livraisons (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  commande_id INT UNSIGNED NOT NULL,
  livreur_id INT UNSIGNED DEFAULT NULL,
  statut ENUM('en_attente','assignee','en_cours','livree','echec') DEFAULT 'en_attente',
  date_prevue DATE DEFAULT NULL,
  date_livree DATETIME DEFAULT NULL,
  notes TEXT,
  created_at DATETIME DEFAULT NOW(),
  FOREIGN KEY (commande_id) REFERENCES commandes(id)
);
