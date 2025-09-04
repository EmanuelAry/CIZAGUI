<?php
namespace App\Controllers;

use App\Models\ProfissionalModel;
use App\Classes\Profissional;

class ProfissionalController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ProfissionalModel($pdo);
    }

    // RF001 - Cadastrar novo profissional
    public function cadastrar($dados) {
        $profissional = new Profissional($dados);
        return $this->model->criarProfissional($profissional->toArray());
    }

    // RF002 - Login
    public function login($email, $senha) {
        $usuario = $this->model->autenticar($email, $senha);
        if ($usuario) {
            //Verificar funcionamento
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            return true;
        }
        return false;
    }

    // RF004 - Atualizar dados do profissional
    public function atualizar($id, $dados) {
        return $this->model->atualizarProfissional($id, $dados);
    }

    // RF005 - Listar profissionais da clínica
    public function listarPorClinica($clinica_id) {
        return $this->model->listarProfissionais($clinica_id);
    }

    // RF008 - Histórico de atendimentos do profissional
    public function historicoAtendimentos($profissional_id) {
        return $this->model->historicoAtendimentos($profissional_id);
    }

    // RF011 - Consultar atendimentos com filtros
    public function filtrarAtendimentos($clinica_id, $filtros) {
        return $this->model->filtrarAtendimentos($clinica_id, $filtros);
    }

    // RF016 - Consultar procedimentos da clínica
    public function listarProcedimentos($clinica_id) {
        return $this->model->listarProcedimentos($clinica_id);
    }

    // Métodos adicionais podem ser implementados para:
    // RF003, RF006, RF007, RF009, RF010, RF012, RF013, RF014, RF015
    // usando os respectivos modelos: PacienteModel, AtendimentoModel, OrcamentoModel, ProcedimentoModel etc.
}
