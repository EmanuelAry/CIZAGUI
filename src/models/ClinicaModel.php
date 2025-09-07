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

        $sql = "INSERT INTO clinica (clinica_nome, clinica_cnpj, clinica_senha)
                VALUES (?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            $clinica->getNome(),
            $clinica->getCnpj(),
            $clinica->getSenha(),
        ]);

        if ($success) {
            $clinica->setId($this->pdo->lastInsertId());
        }

        return $success;
    }

    public function read($id) {
        $sql = "SELECT * FROM clinica WHERE clinica_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new Clinica($data) : null;
    }

    public function update(Clinica $clinica) {
        if (!$clinica->isValid()) {
            throw new \Exception("Dados da clínica inválidos");
        }

        $sql = "UPDATE clinica SET clinica_nome = ?, clinica_cnpj = ?
                WHERE clinica_id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $clinica->getNome(),
            $clinica->getCnpj(),
            $clinica->getId(),
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM clinica WHERE clinica_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getAll($filters = []) {
        $sql = "SELECT * FROM clinica";
        $params = [];

        // Aplicar filtros
        if (!empty($filters['nome'])) {
            $sql .= " AND clinica_nome LIKE ?";
            $params[] = '%' . $filters['nome'] . '%';
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
        $sql = "SELECT * FROM clinica WHERE clinica_cnpj = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cnpj]);
        
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new Clinica($data) : null;
    }
}
?>