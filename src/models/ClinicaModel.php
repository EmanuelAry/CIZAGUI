<?php
namespace App\Models;
use App\Classes\Clinica;

class ClinicaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create(Clinica $clinica) {
        if (!$clinica->isValid()) {
            throw new \Exception("Dados da clínica inválidos");
        }

        $sql = "INSERT INTO clinica (nome, cnpj)
                VALUES (?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            $clinica->getNome(),
            $clinica->getCnpj()
        ]);

        if ($success) {
            $clinica->setId($this->pdo->lastInsertId());
        }

        return $success;
    }

    public function read($id) {
        $sql = "SELECT * FROM clinica WHERE clinica_id = ? AND status = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new Clinica($data) : null;
    }

    public function update(Clinica $clinica) {
        if (!$clinica->isValid()) {
            throw new \Exception("Dados da clínica inválidos");
        }

        $sql = "UPDATE clinica SET nome = ?, cnpj = ?
                WHERE clinica_id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $clinica->getNome(),
            $clinica->getCnpj(),
            $clinica->getId()
        ]);
    }

    public function delete($id) {
        // Soft delete - marca como inativo
        $sql = "UPDATE clinica SET status = 0 WHERE clinica_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getAll($filters = []) {
        $sql = "SELECT * FROM clinica WHERE status = 1";
        $params = [];

        // Aplicar filtros
        if (!empty($filters['nome'])) {
            $sql .= " AND nome LIKE ?";
            $params[] = '%' . $filters['nome'] . '%';
        }

        if (!empty($filters['cidade'])) {
            $sql .= " AND cidade = ?";
            $params[] = $filters['cidade'];
        }

        if (!empty($filters['estado'])) {
            $sql .= " AND estado = ?";
            $params[] = $filters['estado'];
        }

        $sql .= " ORDER BY nome";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $clinicas = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $clinicas[] = new Clinica($data);
        }

        return $clinicas;
    }

    public function getClinicaByCnpj($cnpj) {
        $sql = "SELECT * FROM clinica WHERE cnpj = ? AND status = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cnpj]);
        
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new Clinica($data) : null;
    }
}
?>