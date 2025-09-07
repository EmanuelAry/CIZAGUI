<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clínica - Sistema de Gestão</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .loading {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h2 class="mb-0">
                            <i class="fas fa-hospital me-2"></i>
                            Cadastro de Clínica
                        </h2>
                        <p class="mb-0 opacity-75">Preencha os dados da nova clínica</p>
                    </div>
                    
                    <div class="card-body p-5">
                        <form id="formCadastroClinica">
                            <!-- Dados Básicos -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Dados Básicos
                                    </h5>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="nome" class="form-label required-field">Nome da Clínica</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required 
                                           placeholder="Digite o nome completo da clínica">
                                    <div class="invalid-feedback">Por favor, informe o nome da clínica.</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="cnpj" class="form-label required-field">CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" required 
                                           placeholder="00.000.000/0000-00" maxlength="18">
                                    <div class="invalid-feedback">CNPJ inválido.</div>
                                </div>

                               <div class="col-md-12 mb-3">
                                    <label for="senha" class="form-label required-field">Senha</label>
                                    <input type="password" class="form-control" id="senha" name="senha" required>
                                    <div class="invalid-feedback">Senha Inválida</div>
                                </div>
                            
                            <!-- Botões -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" class="btn btn-secondary me-md-2" onclick="limparFormulario()">
                                            <i class="fas fa-eraser me-1"></i> Limpar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Cadastrar Clínica
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Loading -->
                        <div class="text-center mt-4 loading" id="loading">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <p class="mt-2">Cadastrando clínica...</p>
                        </div>
                        
                        <!-- Mensagens -->
                        <div id="mensagem" class="mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/blueimp-md5@2.19.0/js/md5.min.js"></script>
    <script>
        // Máscaras para os campos
        function aplicarMascaras() {
            // Máscara para CNPJ
            const cnpjInput = document.getElementById('cnpj');
            cnpjInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 14) {
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                    e.target.value = value.substring(0, 18);
                }
            });
        }

        // Validação de CNPJ
        function validarCNPJ(cnpj) {
            cnpj = cnpj.replace(/[^\d]+/g, '');
            
            if (cnpj.length !== 14) return false;
            
            // Elimina CNPJs invalidos conhecidos
            if (/^(\d)\1+$/.test(cnpj)) return false;
            
            // Valida DVs
            let tamanho = cnpj.length - 2;
            let numeros = cnpj.substring(0, tamanho);
            let digitos = cnpj.substring(tamanho);
            let soma = 0;
            let pos = tamanho - 7;
            
            for (let i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) pos = 9;
            }
            
            let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado !== parseInt(digitos.charAt(0))) return false;
            
            tamanho = tamanho + 1;
            numeros = cnpj.substring(0, tamanho);
            soma = 0;
            pos = tamanho - 7;
            
            for (let i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) pos = 9;
            }
            
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            return resultado === parseInt(digitos.charAt(1));
        }

        // Limpar formulário
        function limparFormulario() {
            document.getElementById('formCadastroClinica').reset();
            document.getElementById('mensagem').innerHTML = '';
            // Remove classes de validação
            const inputs = document.querySelectorAll('.form-control, .form-select');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                input.classList.remove('is-valid');
            });
        }

        // Enviar formulário
        document.getElementById('formCadastroClinica').addEventListener('submit', async function(e) {
            e.preventDefault();
            // Validar campos obrigatórios
            const nome  = document.getElementById('nome');
            const cnpj  = document.getElementById('cnpj');
            const senha = document.getElementById('senha');
            let isValid = true;
            if (!nome.value.trim()) {
                nome.classList.add('is-invalid');
                isValid = false;
            } else {
                nome.classList.remove('is-invalid');
                nome.classList.add('is-valid');
            }
            
            if (!cnpj.value.trim() || !validarCNPJ(cnpj.value)) {
                cnpj.classList.add('is-invalid');
                isValid = false;
            } else {
                cnpj.classList.remove('is-invalid');
                cnpj.classList.add('is-valid');
            }
            
            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campos inválidos',
                    text: 'Por favor, verifique os campos destacados em vermelho.'
                });
                return;
            }
            
            // Mostrar loading
            document.getElementById('loading').style.display = 'block';
            document.getElementById('mensagem').innerHTML = '';
            
            // Preparar dados
            const dados = {
                nome: nome.value,
                cnpj: cnpj.value.replace(/\D/g, ''),
                senha: md5(senha) 
            };

            try {
                // Enviar para o backend
                const response = await fetch('src/api/clinica.php?XDEBUG_SESSION_START=VSCODE', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dados)
                });
                
                const resultado = await response.json();
                
                if (resultado.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Clínica cadastrada com sucesso!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    
                    // Limpar formulário após sucesso
                    setTimeout(() => {
                        limparFormulario();
                        // Redirecionar ou fazer outras ações
                    }, 2000);
                } else {
                    throw new Error(resultado.message);
                }
                
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: error.message || 'Erro ao cadastrar clínica. Tente novamente.'
                });
            } finally {
                document.getElementById('loading').style.display = 'none';
            }
        });

        // Inicializar máscaras quando o documento carregar
        document.addEventListener('DOMContentLoaded', function() {
            aplicarMascaras();
        });
    </script>
</body>
</html>