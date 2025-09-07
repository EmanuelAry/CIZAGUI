<?php
namespace Tests\Unit\App\Classes;

use PHPUnit\Framework\TestCase;
use App\Classes\Profissional;

class ProfissionalTest extends TestCase
{
    /** @test */
    public function pode_ser_criada_com_array_vazio()
    {
        $profissional = new Profissional();
        
        $this->assertNull($profissional->getId());
        $this->assertNull($profissional->getClinicaId());
        $this->assertEquals('', $profissional->getNome());
        $this->assertEquals('', $profissional->getEmail());
        $this->assertEquals('', $profissional->getSenha());
        $this->assertEquals('', $profissional->getTelefone());
        $this->assertEquals('', $profissional->getCpf());
        $this->assertEquals('', $profissional->getCnpj());
        $this->assertEquals('', $profissional->getNasc());
    }

    /** @test */
    public function pode_ser_criada_com_dados_completos()
    {
        $dados = [
            'profissional_id' => 1,
            'clinica_id' => 10,
            'profissional_nome' => 'Dr. João Silva',
            'profissional_email' => 'joao@clinica.com',
            'profissional_senha' => 'senha123',
            'profissional_telefone1' => '(11) 99999-9999',
            'profissional_cpf' => '123.456.789-00',
            'profissional_cnpj' => '12.345.678/0001-90',
            'profissional_nasc' => '1980-01-15'
        ];

        $profissional = new Profissional($dados);
        
        $this->assertEquals(1, $profissional->getId());
        $this->assertEquals(10, $profissional->getClinicaId());
        $this->assertEquals('Dr. João Silva', $profissional->getNome());
        $this->assertEquals('joao@clinica.com', $profissional->getEmail());
        $this->assertEquals('senha123', $profissional->getSenha());
        $this->assertEquals('(11) 99999-9999', $profissional->getTelefone());
        $this->assertEquals('123.456.789-00', $profissional->getCpf());
        $this->assertEquals('12.345.678/0001-90', $profissional->getCnpj());
        $this->assertEquals('1980-01-15', $profissional->getNasc());
    }

    /** @test */
    public function pode_ser_criada_com_dados_parciais()
    {
        $dados = [
            'profissional_nome' => 'Dra. Maria Santos',
            'profissional_email' => 'maria@clinica.com'
        ];

        $profissional = new Profissional($dados);
        
        $this->assertNull($profissional->getId());
        $this->assertNull($profissional->getClinicaId());
        $this->assertEquals('Dra. Maria Santos', $profissional->getNome());
        $this->assertEquals('maria@clinica.com', $profissional->getEmail());
        $this->assertEquals('', $profissional->getSenha());
        $this->assertEquals('', $profissional->getTelefone());
    }

    /** @test */
    public function setters_e_getters_funcionam_corretamente()
    {
        $profissional = new Profissional();
        
        $profissional->setNome('Dr. Carlos Oliveira');
        $profissional->setEmail('carlos@clinica.com');
        $profissional->setSenha('novaSenha456');
        $profissional->setTelefone('(11) 88888-8888');
        $profissional->setCpf('987.654.321-00');
        $profissional->setCnpj('98.765.432/0001-10');
        $profissional->setNasc('1975-05-20');
        
        $this->assertEquals('Dr. Carlos Oliveira', $profissional->getNome());
        $this->assertEquals('carlos@clinica.com', $profissional->getEmail());
        $this->assertEquals('novaSenha456', $profissional->getSenha());
        $this->assertEquals('(11) 88888-8888', $profissional->getTelefone());
        $this->assertEquals('987.654.321-00', $profissional->getCpf());
        $this->assertEquals('98.765.432/0001-10', $profissional->getCnpj());
        $this->assertEquals('1975-05-20', $profissional->getNasc());
    }

    /** @test */
    public function validar_login_retorna_true_quando_email_e_senha_corretos()
    {
        $dados = [
            'profissional_email' => 'ana@clinica.com',
            'profissional_senha' => 'senhaSecreta'
        ];

        $profissional = new Profissional($dados);
        
        $this->assertTrue($profissional->validarLogin('ana@clinica.com', 'senhaSecreta'));
    }

    /** @test */
    public function validar_login_retorna_false_quando_email_incorreto()
    {
        $dados = [
            'profissional_email' => 'ana@clinica.com',
            'profissional_senha' => 'senhaSecreta'
        ];

        $profissional = new Profissional($dados);
        
        $this->assertFalse($profissional->validarLogin('email_errado@clinica.com', 'senhaSecreta'));
    }

    /** @test */
    public function validar_login_retorna_false_quando_senha_incorreta()
    {
        $dados = [
            'profissional_email' => 'ana@clinica.com',
            'profissional_senha' => 'senhaSecreta'
        ];

        $profissional = new Profissional($dados);
        
        $this->assertFalse($profissional->validarLogin('ana@clinica.com', 'senhaErrada'));
    }

    /** @test */
    public function validar_login_retorna_false_quando_email_e_senha_incorretos()
    {
        $dados = [
            'profissional_email' => 'ana@clinica.com',
            'profissional_senha' => 'senhaSecreta'
        ];

        $profissional = new Profissional($dados);
        
        $this->assertFalse($profissional->validarLogin('email_errado@clinica.com', 'senhaErrada'));
    }

    /** @test */
    public function atualizar_dados_modifica_apenas_campos_fornecidos()
    {
        $dadosIniciais = [
            'profissional_nome' => 'Dr. Antônio',
            'profissional_email' => 'antonio@clinica.com',
            'profissional_telefone1' => '(11) 77777-7777',
            'profissional_cpf' => '111.222.333-44'
        ];

        $profissional = new Profissional($dadosIniciais);
        
        $novosDados = [
            'nome' => 'Dr. Antônio Carlos',
            'email' => 'antonio.carlos@clinica.com',
            'telefone' => '(11) 76666-6666'
        ];

        $profissional->atualizarDados($novosDados);
        
        $this->assertEquals('Dr. Antônio Carlos', $profissional->getNome());
        $this->assertEquals('antonio.carlos@clinica.com', $profissional->getEmail());
        $this->assertEquals('(11) 76666-6666', $profissional->getTelefone());
        $this->assertEquals('111.222.333-44', $profissional->getCpf()); // Não foi alterado
        $this->assertEquals('', $profissional->getCnpj()); // Não foi alterado
        $this->assertEquals('', $profissional->getNasc()); // Não foi alterado
    }

    /** @test */
    public function atualizar_dados_nao_sobrescreve_campos_nao_fornecidos()
    {
        $dadosIniciais = [
            'profissional_nome' => 'Dr. Roberto',
            'profissional_email' => 'roberto@clinica.com',
            'profissional_senha' => 'senhaOriginal'
        ];

        $profissional = new Profissional($dadosIniciais);
        
        $novosDados = [
            'nome' => 'Dr. Roberto Alves'
        ];

        $profissional->atualizarDados($novosDados);
        
        $this->assertEquals('Dr. Roberto Alves', $profissional->getNome());
        $this->assertEquals('roberto@clinica.com', $profissional->getEmail()); // Manteve o original
        $this->assertEquals('senhaOriginal', $profissional->getSenha()); // Manteve o original
    }

    /** @test */
    public function to_array_retorna_dados_corretos()
    {
        $dados = [
            'clinica_id' => 5,
            'profissional_nome' => 'Dra. Paula',
            'profissional_email' => 'paula@clinica.com',
            'profissional_senha' => 'senhaPaula',
            'profissional_telefone1' => '(11) 75555-5555',
            'profissional_cpf' => '555.666.777-88',
            'profissional_cnpj' => '55.666.777/0001-88',
            'profissional_nasc' => '1985-12-25'
        ];

        $profissional = new Profissional($dados);
        
        $arrayEsperado = [
            'clinica_id' => 5,
            'nome' => 'Dra. Paula',
            'email' => 'paula@clinica.com',
            'senha' => 'senhaPaula',
            'telefone' => '(11) 75555-5555',
            'cpf' => '555.666.777-88',
            'cnpj' => '55.666.777/0001-88',
            'nasc' => '1985-12-25'
        ];

        $this->assertEquals($arrayEsperado, $profissional->toArray());
    }

    /** @test */
    public function to_array_nao_inclui_id_do_profissional()
    {
        $dados = [
            'profissional_id' => 99,
            'clinica_id' => 5,
            'profissional_nome' => 'Teste'
        ];

        $profissional = new Profissional($dados);
        $arrayResultado = $profissional->toArray();
        
        $this->assertArrayNotHasKey('profissional_id', $arrayResultado);
        $this->assertArrayHasKey('clinica_id', $arrayResultado);
        $this->assertArrayHasKey('nome', $arrayResultado);
    }

    /** @test */
    public function to_array_funciona_com_dados_parciais()
    {
        $dados = [
            'clinica_id' => 3,
            'profissional_nome' => 'Dr. Simples'
        ];

        $profissional = new Profissional($dados);
        
        $arrayEsperado = [
            'clinica_id' => 3,
            'nome' => 'Dr. Simples',
            'email' => '',
            'senha' => '',
            'telefone' => '',
            'cpf' => '',
            'cnpj' => '',
            'nasc' => ''
        ];

        $this->assertEquals($arrayEsperado, $profissional->toArray());
    }
}