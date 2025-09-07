<?php
namespace Tests\Unit\App\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\ProfissionalController;
use App\Models\ProfissionalModel;
use App\Classes\Profissional;
use PDO;

class ProfissionalControllerTests extends TestCase{
    private $pdoMock;
    private $modelMock;
    private $controller;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->modelMock = $this->createMock(ProfissionalModel::class);
        
        // Criar controller com PDO mockado
        $this->controller = new ProfissionalController($this->pdoMock);
        
        // Substituir o model interno pelo mock usando Reflection
        $reflection = new \ReflectionClass($this->controller);
        $property = $reflection->getProperty('model');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->modelMock);
    }

    /** @test */
    public function cadastrar_deve_chamar_model_criar_profissional()
    {
        $dados = [
            'clinica_id' => 1,
            'profissional_nome' => 'Dr. Teste',
            'profissional_email' => 'teste@clinica.com',
            'profissional_senha' => 'senha123'
        ];

        $arrayEsperado = [
            'clinica_id' => 1,
            'nome' => 'Dr. Teste',
            'email' => 'teste@clinica.com',
            'senha' => 'senha123',
            'telefone' => '',
            'cpf' => '',
            'cnpj' => '',
            'nasc' => ''
        ];

        $this->modelMock->expects($this->once())
            ->method('criarProfissional')
            ->with($arrayEsperado)
            ->willReturn(100); // ID do novo profissional

        $resultado = $this->controller->cadastrar($dados);

        $this->assertEquals(100, $resultado);
    }

    /** @test */
    public function login_deve_retornar_true_quando_autenticacao_sucesso()
    {
        $email = 'teste@clinica.com';
        $senha = 'senha123';

        $this->modelMock->expects($this->once())
            ->method('autenticar')
            ->with($email, $senha)
            ->willReturn(['id' => 1, 'nome' => 'Dr. Teste']);

        $resultado = $this->controller->login($email, $senha);

        $this->assertTrue($resultado);
    }

    /** @test */
    public function login_deve_retornar_false_quando_autenticacao_falha()
    {
        $email = 'teste@clinica.com';
        $senha = 'senhaErrada';

        $this->modelMock->expects($this->once())
            ->method('autenticar')
            ->with($email, $senha)
            ->willReturn(false);

        $resultado = $this->controller->login($email, $senha);

        $this->assertFalse($resultado);
    }

    /** @test */
    public function atualizar_deve_chamar_model_atualizar_profissional()
    {
        $id = 1;
        $dados = [
            'nome' => 'Dr. Atualizado',
            'email' => 'atualizado@clinica.com',
            'telefone' => '(11) 99999-9999'
        ];

        $this->modelMock->expects($this->once())
            ->method('atualizarProfissional')
            ->with($id, $dados)
            ->willReturn(true);

        $resultado = $this->controller->atualizar($id, $dados);

        $this->assertTrue($resultado);
    }

    /** @test */
    public function listar_por_clinica_deve_chamar_model_listar_profissionais()
    {
        $clinica_id = 1;
        $profissionaisEsperados = [
            ['id' => 1, 'nome' => 'Dr. Teste 1'],
            ['id' => 2, 'nome' => 'Dr. Teste 2']
        ];

        $this->modelMock->expects($this->once())
            ->method('listarProfissionais')
            ->with($clinica_id)
            ->willReturn($profissionaisEsperados);

        $resultado = $this->controller->listarPorClinica($clinica_id);

        $this->assertEquals($profissionaisEsperados, $resultado);
    }

    /** @test */
    public function historico_atendimentos_deve_chamar_model_historico_atendimentos()
    {
        $profissional_id = 1;
        $historicoEsperado = [
            ['id' => 1, 'paciente' => 'Paciente 1', 'data' => '2024-01-15'],
            ['id' => 2, 'paciente' => 'Paciente 2', 'data' => '2024-01-16']
        ];

        $this->modelMock->expects($this->once())
            ->method('historicoAtendimentos')
            ->with($profissional_id)
            ->willReturn($historicoEsperado);

        $resultado = $this->controller->historicoAtendimentos($profissional_id);

        $this->assertEquals($historicoEsperado, $resultado);
    }

    /** @test */
    public function filtrar_atendimentos_deve_chamar_model_filtrar_atendimentos()
    {
        $clinica_id = 1;
        $filtros = [
            'data_inicio' => '2024-01-01',
            'data_fim' => '2024-01-31',
            'profissional_id' => 1
        ];

        $atendimentosFiltrados = [
            ['id' => 1, 'paciente' => 'Paciente 1', 'data' => '2024-01-15'],
            ['id' => 2, 'paciente' => 'Paciente 2', 'data' => '2024-01-20']
        ];

        $this->modelMock->expects($this->once())
            ->method('filtrarAtendimentos')
            ->with($clinica_id, $filtros)
            ->willReturn($atendimentosFiltrados);

        $resultado = $this->controller->filtrarAtendimentos($clinica_id, $filtros);

        $this->assertEquals($atendimentosFiltrados, $resultado);
    }

    /** @test */
    public function listar_procedimentos_deve_chamar_model_listar_procedimentos()
    {
        $clinica_id = 1;
        $procedimentosEsperados = [
            ['id' => 1, 'nome' => 'Consulta', 'preco' => 150.00],
            ['id' => 2, 'nome' => 'Exame', 'preco' => 200.00]
        ];

        $this->modelMock->expects($this->once())
            ->method('listarProcedimentos')
            ->with($clinica_id)
            ->willReturn($procedimentosEsperados);

        $resultado = $this->controller->listarProcedimentos($clinica_id);

        $this->assertEquals($procedimentosEsperados, $resultado);
    }

    /** @test */
    public function cadastrar_deve_lidar_com_falha_do_model()
    {
        $dados = [
            'clinica_id' => 1,
            'profissional_nome' => 'Dr. Teste'
        ];

        $this->modelMock->expects($this->once())
            ->method('criarProfissional')
            ->willReturn(false);

        $resultado = $this->controller->cadastrar($dados);

        $this->assertFalse($resultado);
    }

    /** @test */
    public function atualizar_deve_lidar_com_falha_do_model()
    {
        $id = 1;
        $dados = ['nome' => 'Dr. Teste'];

        $this->modelMock->expects($this->once())
            ->method('atualizarProfissional')
            ->willReturn(false);

        $resultado = $this->controller->atualizar($id, $dados);

        $this->assertFalse($resultado);
    }

    /** @test */
    public function listar_por_clinica_deve_retornar_array_vazio_quando_sem_resultados()
    {
        $clinica_id = 999;

        $this->modelMock->expects($this->once())
            ->method('listarProfissionais')
            ->with($clinica_id)
            ->willReturn([]);

        $resultado = $this->controller->listarPorClinica($clinica_id);

        $this->assertEmpty($resultado);
        $this->assertIsArray($resultado);
    }

    /** @test */
    public function filtrar_atendimentos_deve_passar_filtros_vazios()
    {
        $clinica_id = 1;
        $filtros = [];

        $this->modelMock->expects($this->once())
            ->method('filtrarAtendimentos')
            ->with($clinica_id, $filtros)
            ->willReturn([]);

        $resultado = $this->controller->filtrarAtendimentos($clinica_id, $filtros);

        $this->assertIsArray($resultado);
    }
}