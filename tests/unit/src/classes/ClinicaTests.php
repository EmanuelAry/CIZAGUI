<?php
namespace Tests\Unit\App\Classes;

use App\Classes\Clinica;
use PHPUnit\Framework\TestCase;

class ClinicaTest extends TestCase
{
    /** @test */
    public function pode_ser_criada_com_array_vazio()
    {
        $clinica = new Clinica();
        
        $this->assertNull($clinica->getId());
        $this->assertEquals('', $clinica->getNome());
        $this->assertEquals('', $clinica->getCnpj());
        $this->assertEquals('', $clinica->getSenha());
    }

    /** @test */
    public function pode_ser_criada_com_dados_completos()
    {
        $dados = [
            'clinica_id' => 1,
            'nome' => 'Clinica Teste',
            'cnpj' => '12.345.678/0001-90',
            'senha' => 'senha123'
        ];

        $clinica = new Clinica($dados);
        
        $this->assertEquals(1, $clinica->getId());
        $this->assertEquals('Clinica Teste', $clinica->getNome());
        $this->assertEquals('12.345.678/0001-90', $clinica->getCnpj());
        $this->assertEquals('senha123', $clinica->getSenha());
    }

    /** @test */
    public function pode_ser_criada_com_dados_parciais()
    {
        $dados = [
            'nome' => 'Clinica Parcial',
            'cnpj' => '98.765.432/0001-10'
        ];

        $clinica = new Clinica($dados);
        
        $this->assertNull($clinica->getId());
        $this->assertEquals('Clinica Parcial', $clinica->getNome());
        $this->assertEquals('98.765.432/0001-10', $clinica->getCnpj());
        $this->assertEquals('', $clinica->getSenha());
    }

    /** @test */
    public function setters_e_getters_funcionam_corretamente()
    {
        $clinica = new Clinica();
        
        $clinica->setId(5);
        $clinica->setNome('Clinica Setter');
        $clinica->setCnpj('11.222.333/0001-44');
        $clinica->setSenha('novaSenha');
        
        $this->assertEquals(5, $clinica->getId());
        $this->assertEquals('Clinica Setter', $clinica->getNome());
        $this->assertEquals('11.222.333/0001-44', $clinica->getCnpj());
        $this->assertEquals('novaSenha', $clinica->getSenha());
    }

    /** @test */
    public function isValid_retorna_false_quando_faltam_campos_obrigatorios()
    {
        $clinica1 = new Clinica(['nome' => 'Teste']);
        $clinica2 = new Clinica(['cnpj' => '123']);
        $clinica3 = new Clinica(['senha' => '123']);
        $clinica4 = new Clinica();
        
        $this->assertFalse($clinica1->isValid());
        $this->assertFalse($clinica2->isValid());
        $this->assertFalse($clinica3->isValid());
        $this->assertFalse($clinica4->isValid());
    }

    /** @test */
    public function isValid_retorna_true_quando_todos_campos_obrigatorios_estao_presentes()
    {
        $dados = [
            'nome' => 'Clinica Valida',
            'cnpj' => '12.345.678/0001-90',
            'senha' => 'senhaSegura'
        ];

        $clinica = new Clinica($dados);
        
        $this->assertTrue($clinica->isValid());
    }

    /** @test */
    public function isValid_ignora_id_na_validacao()
    {
        $dadosComId = [
            'clinica_id' => 999,
            'nome' => 'Clinica Com ID',
            'cnpj' => '12.345.678/0001-90',
            'senha' => 'senha123'
        ];

        $dadosSemId = [
            'nome' => 'Clinica Sem ID',
            'cnpj' => '12.345.678/0001-90',
            'senha' => 'senha123'
        ];

        $clinicaComId = new Clinica($dadosComId);
        $clinicaSemId = new Clinica($dadosSemId);
        
        $this->assertTrue($clinicaComId->isValid());
        $this->assertTrue($clinicaSemId->isValid());
    }

    /** @test */
    public function campos_vazios_ou_nulos_invalidam_a_instancia()
    {
        $dadosNomeVazio = [
            'nome' => '',
            'cnpj' => '12.345.678/0001-90',
            'senha' => 'senha123'
        ];

        $dadosCnpjVazio = [
            'nome' => 'Clinica Teste',
            'cnpj' => '',
            'senha' => 'senha123'
        ];

        $dadosSenhaVazia = [
            'nome' => 'Clinica Teste',
            'cnpj' => '12.345.678/0001-90',
            'senha' => ''
        ];

        $clinica1 = new Clinica($dadosNomeVazio);
        $clinica2 = new Clinica($dadosCnpjVazio);
        $clinica3 = new Clinica($dadosSenhaVazia);
        
        $this->assertFalse($clinica1->isValid());
        $this->assertFalse($clinica2->isValid());
        $this->assertFalse($clinica3->isValid());
    }
}
?>