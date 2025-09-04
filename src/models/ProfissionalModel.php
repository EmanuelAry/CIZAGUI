<?php
namespace App\Models;

class ProfissionalModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // RF001 - Cadastrar novo profissional
    public function criarProfissional($dados) {
        $sql = "INSERT INTO profissional (clinica_id, profissional_nome, profissional_email, profissional_senha, profissional_tel, profissional_cpf,  profissional_cnpj,  profissional_nasc) 
                VALUES (:clinica_id, :nome, :email, :senha, :telefone, :cpf, :cnpj, :nasc)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($dados);
    }

    // RF002 - Login de profissional ou administrador
    public function autenticar($email, $senha) {
        $sql = "SELECT * 
                  FROM profissional 
                 WHERE profissional_email = :email 
                   AND profissional_senha = :senha";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'senha' => $senha]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // RF004 - Alterar dados do profissional
    public function atualizarProfissional($id, $dados) {
        $sql = "UPDATE profissional 
                   SET profissional_nome = :nome, profissional_email = :email, profissional_tel = :telefone, profissional_cpf = :cpf, profissional_cnpj = :cnpj, profissional_nasc = :nasc 
                 WHERE profissional_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $dados['id'] = $id;
        return $stmt->execute($dados);
    }

    // RF005 - Consultar dados dos profissionais
    public function listarProfissionais($clinica_id) {
        $sql = "SELECT * 
                  FROM profissional 
                 WHERE clinica_id = :clinica_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['clinica_id' => $clinica_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // RF008 - Histórico de atendimentos dos profissionais
    public function historicoAtendimentos($profissional_id) {
        $sql = "SELECT * 
                  FROM atendimento 
                 WHERE profissional_id = :profissional_id 
                 ORDER BY atendimento_data DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['profissional_id' => $profissional_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // RF011 - Consultar atendimentos com filtros
    public function filtrarAtendimentos($clinica_id, $filtros = []) {
        $sql = "SELECT * 
                  FROM atendimento 
                 WHERE clinica_id = :clinica_id";
        $params = ['clinica_id' => $clinica_id];

        if (!empty($filtros['status'])) {
            $sql .= " AND atendimento_status_id = :status";
            $params['status'] = $filtros['status'];
        }

        if (!empty($filtros['data_inicio']) && !empty($filtros['data_fim'])) {
            $sql .= " AND atendimento_data BETWEEN :data_inicio AND :data_fim";
            $params['data_inicio'] = $filtros['data_inicio'];
            $params['data_fim'] = $filtros['data_fim'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // RF016 - Consulta de procedimentos
    public function listarProcedimentos($clinica_id) {
        $sql = "SELECT * FROM procedimento WHERE clinica_id = :clinica_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['clinica_id' => $clinica_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
