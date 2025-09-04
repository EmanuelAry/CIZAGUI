<?php
namespace App\Classes;

class Profissional {
    private $id;
    private $clinica_id;
    private $nome;
    private $email;
    private $senha;
    private $telefone;
    private $cpf;
    private $cnpj;
    private $nasc;  

    public function __construct($dados = []) {
        if (!empty($dados)) {
            $this->id = $dados['profissional_id'] ?? null;
            $this->clinica_id = $dados['clinica_id'] ?? null;
            $this->nome = $dados['profissional_nome'] ?? '';
            $this->email = $dados['profissional_email'] ?? '';
            $this->senha = $dados['profissional_senha'] ?? '';
            $this->telefone = $dados['profissional_telefone1'] ?? '';
            $this->cpf = $dados['profissional_cpf'] ?? '';
            $this->cnpj = $dados['profissional_cnpj'] ?? '';
            $this->nasc = $dados['profissional_nasc'] ?? '';
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getClinicaId() { return $this->clinica_id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }
    public function getTelefone() { return $this->telefone; }
    public function getCpf() { return $this->cpf; }
    public function getCnpj() { return $this->cnpj; }
    public function getNasc() { return $this->nasc; }

    // Setters
    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = $email; }
    public function setSenha($senha) { $this->senha = $senha; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }
    public function setCpf($cpf) { $this->cpf = $cpf; }
    public function setCnpj($cnpj) { $this->cnpj = $cnpj; }
    public function setNasc($nasc) { $this->nasc = $nasc; }

    // Validação básica para login (RF002)
    public function validarLogin($email, $senha) {
        return ($this->email === $email && $this->senha === $senha);
    }

    // Atualização de dados (RF004)
    public function atualizarDados($dados) {
        $this->nome = $dados['nome'] ?? $this->nome;
        $this->email = $dados['email'] ?? $this->email;
        $this->telefone = $dados['telefone'] ?? $this->telefone;
        $this->cpf = $dados['cpf'] ?? $this->cpf;
        $this->cnpj = $dados['cnpj'] ?? $this->cnpj;
        $this->nasc = $dados['nasc'] ?? $this->nasc;
    }

    // Exportar dados como array (útil para salvar no banco)
    public function toArray() {
        return [
            'clinica_id' => $this->clinica_id,
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
            'telefone' => $this->telefone,
            'cpf' => $this->cpf,
            'cnpj' => $this->cnpj,
            'nasc' => $this->nasc
        ];
    }
}
