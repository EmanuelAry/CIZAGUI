<?php
namespace App\Classes;
class Clinica {
    private $clinica_id;
    private $nome;
    private $cnpj;
    private $senha;

    public function __construct($data = []) {
        if (!empty($data)) {
            $this->clinica_id = $data['clinica_id'] ?? null;
            $this->nome  = $data['nome'] ?? '';
            $this->cnpj  = $data['cnpj'] ?? '';
            $this->senha = $data['senha'] ?? '';
        }
    }

    // Getters
    public function getId() { return $this->clinica_id; }
    public function getNome() { return $this->nome; }
    public function getCnpj() { return $this->cnpj; }
    public function getSenha() { return $this->senha; }

    // Setters
    public function setId($id) { $this->clinica_id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setCnpj($cnpj) { $this->cnpj = $cnpj; }
    public function setSenha($senha) { $this->senha = $senha; }

    // Validações
    public function isValid() {
        return !empty($this->nome) && !empty($this->cnpj) && !empty($this->senha);
    }
}
?>