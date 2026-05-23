<?php

class Client
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int|false
    {
        // Vérifier si l'email existe déjà
        if ($this->findByEmail($data['email'])) {
            return false;
        }

        $sql = "INSERT INTO clients (nom, prenom, email, telephone, adresse, ville, type_client, nom_entreprise, ninea, password, statut, created_at)
                VALUES (:nom, :prenom, :email, :telephone, :adresse, :ville, :type_client, :nom_entreprise, :ninea, :password, 'actif', NOW())";

        $this->db->query($sql, [
            ':nom'           => sanitize($data['nom']),
            ':prenom'        => sanitize($data['prenom']),
            ':email'         => sanitize($data['email']),
            ':telephone'     => sanitize($data['telephone'] ?? ''),
            ':adresse'       => sanitize($data['adresse'] ?? ''),
            ':ville'         => sanitize($data['ville'] ?? ''),
            ':type_client'   => in_array($data['type_client'], ['particulier', 'professionnel']) ? $data['type_client'] : 'particulier',
            ':nom_entreprise' => sanitize($data['nom_entreprise'] ?? ''),
            ':ninea'         => sanitize($data['ninea'] ?? ''),
            ':password'      => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function findById(int $id): array|false
    {
        return $this->db->fetch("SELECT * FROM clients WHERE id = :id", [':id' => $id]);
    }

    public function findByEmail(string $email): array|false
    {
        return $this->db->fetch("SELECT * FROM clients WHERE email = :email", [':email' => $email]);
    }

    public function verifyPassword(string $email, string $password): array|false
    {
        $client = $this->findByEmail($email);
        if ($client && password_verify($password, $client['password'])) {
            return $client;
        }
        return false;
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        $allowed = ['nom', 'prenom', 'telephone', 'adresse', 'ville'];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = :$field";
                $params[":$field"] = sanitize($data[$field]);
            }
        }
        if (isset($data['nom_entreprise'])) {
            $fields[] = "nom_entreprise = :nom_entreprise";
            $params[':nom_entreprise'] = sanitize($data['nom_entreprise']);
        }

        if (!empty($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if (empty($fields)) return false;

        $sql = "UPDATE clients SET " . implode(', ', $fields) . " WHERE id = :id";
        $this->db->query($sql, $params);
        return true;
    }

    public function setResetToken(string $email): string|false
    {
        $client = $this->findByEmail($email);
        if (!$client) return false;

        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1h

        $this->db->query(
            "UPDATE clients SET reset_token = :token, reset_expires = :expires WHERE email = :email",
            [':token' => $token, ':expires' => $expires, ':email' => $email]
        );
        return $token;
    }

    public function findByResetToken(string $token): array|false
    {
        return $this->db->fetch(
            "SELECT * FROM clients WHERE reset_token = :token AND reset_expires > NOW()",
            [':token' => $token]
        );
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $client = $this->findByResetToken($token);
        if (!$client) return false;

        $this->db->query(
            "UPDATE clients SET password = :pwd, reset_token = NULL, reset_expires = NULL WHERE id = :id",
            [':pwd' => password_hash($newPassword, PASSWORD_BCRYPT), ':id' => $client['id']]
        );
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->query("DELETE FROM clients WHERE id = :id", [':id' => $id]);
        return true;
    }

    public function getAll(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        return $this->db->fetchAll(
            "SELECT id, nom, prenom, email, telephone, type_client, statut, created_at FROM clients ORDER BY created_at DESC LIMIT :limit OFFSET :offset",
            [':limit' => $perPage, ':offset' => $offset]
        );
    }

    public function count(): int
    {
        $row = $this->db->fetch("SELECT COUNT(*) as total FROM clients");
        return (int)($row['total'] ?? 0);
    }

    public function updateStatut(int $id, string $statut): bool
    {
        $statuts = ['actif', 'inactif', 'suspendu'];
        if (!in_array($statut, $statuts)) return false;
        $this->db->query("UPDATE clients SET statut = :statut WHERE id = :id", [':statut' => $statut, ':id' => $id]);
        return true;
    }
}
