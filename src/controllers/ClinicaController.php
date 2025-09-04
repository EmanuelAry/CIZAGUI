<?php
namespace App\Controllers;
use App\Models\ClinicaModel;
use App\Classes\Clinica;

class ClinicaController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        try {
            $this->model = new ClinicaModel($pdo);
        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }

    public function handleRequest($method, $data = null, $id = null) {
        try {
            switch ($method) {
                case 'GET':
                    return $this->handleGetRequest($data, $id);
                
                case 'POST':
                    return $this->handlePostRequest($data);
                
                case 'PUT':
                    return $this->handlePutRequest($data, $id);
                
                case 'DELETE':
                    return $this->handleDeleteRequest($id);
                
                default:
                    return ['success' => false, 'message' => 'Método não suportado'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function handleGetRequest($filters, $id) {
        if ($id) {
            $clinica = $this->model->read($id);
            if ($clinica) {
                return ['success' => true, 'data' => $this->clinicaToArray($clinica)];
            }
            return ['success' => false, 'message' => 'Clínica não encontrada'];
        }

        // Listar todas as clínicas com filtros
        $clinicas = $this->model->getAll($filters);
        $result = [];
        foreach ($clinicas as $clini) {
            $result[] = $this->clinicaToArray($clini);
        }
        
        return ['success' => true, 'data' => $result];
    }

    private function handlePostRequest($data) {
        // Validação básica
        if (empty($data['nome']) || empty($data['cnpj']) || empty($data['email'])) {
            return ['success' => false, 'message' => 'Campos obrigatórios: nome, CNPJ e email'];
        }

        // Verificar se CNPJ já existe
        $existing = $this->model->getClinicaByCnpj($data['cnpj']);
        if ($existing) {
            return ['success' => false, 'message' => 'CNPJ já cadastrado'];
        }


        $clinica = new Clinica($data);
        $success = $this->model->create($clinica);

        if ($success) {
            return [
                'success' => true, 
                'message' => 'Clínica cadastrada com sucesso',
                'clinica_id' => $clinica->getId()
            ];
        }

        return ['success' => false, 'message' => 'Erro ao cadastrar clínica'];
    }

    private function handlePutRequest($data, $id) {
        if (!$id) {
            return ['success' => false, 'message' => 'ID da clínica não informado'];
        }

        $clinica = $this->model->read($id);
        if (!$clinica) {
            return ['success' => false, 'message' => 'Clínica não encontrada'];
        }

        // Atualizar apenas os campos fornecidos
        if (isset($data['nome'])) $clinica->setNome($data['nome']);
        if (isset($data['cnpj'])) $clinica->setCnpj($data['cnpj']);

        $success = $this->model->update($clinica);

        if ($success) {
            return ['success' => true, 'message' => 'Clínica atualizada com sucesso'];
        }

        return ['success' => false, 'message' => 'Erro ao atualizar clínica'];
    }

    private function handleDeleteRequest($id) {
        if (!$id) {
            return ['success' => false, 'message' => 'ID da clínica não informado'];
        }

        $success = $this->model->delete($id);

        if ($success) {
            return ['success' => true, 'message' => 'Clínica removida com sucesso'];
        }

        return ['success' => false, 'message' => 'Erro ao remover clínica'];
    }

    private function clinicaToArray(Clinica $clinica) {
        return [
            'clinica_id' => $clinica->getId(),
            'nome' => $clinica->getNome(),
            'cnpj' => $clinica->getCnpj()
        ];
    }
}
?>