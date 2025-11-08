/**
 * Validação de Formulários com Bootstrap 5
 * Ativa a validação visual para todos os formulários da aplicação
 */

(function() {
    'use strict';

    // Função para validar e-mail com regex mais completa
    function validarEmail(email) {
        if (!email) return true; // Se não é required e está vazio, aceita
        
        // Regex completa para e-mail válido
        const regex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
        return regex.test(email);
    }

    // Função para validar CPF (verifica se tem 11 dígitos quando limpo)
    function validarCPF(cpf) {
        if (!cpf) return true; // Se não é required e está vazio, aceita
        
        // Remove tudo que não é dígito
        const cpfLimpo = cpf.replace(/\D/g, '');
        
        // Deve ter exatamente 11 dígitos
        if (cpfLimpo.length !== 11) return false;
        
        // Verifica se não é uma sequência repetida (111.111.111-11, etc)
        if (/^(\d)\1{10}$/.test(cpfLimpo)) return false;
        
        return true;
    }

    // Função para validar matrícula (apenas números)
    function validarMatricula(matricula) {
        if (!matricula) return false; // Matrícula é obrigatória
        
        // Deve conter apenas números
        return /^\d+$/.test(matricula);
    }

    // Função para aplicar validação em tempo real nos campos de e-mail
    function configurarValidacaoEmail(campoEmail) {
        if (!campoEmail) return;

        // Validação ao perder o foco (blur)
        campoEmail.addEventListener('blur', function() {
            if (this.value) {
                const isValid = validarEmail(this.value);
                
                if (isValid) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            } else if (!this.hasAttribute('required')) {
                // Se não é obrigatório e está vazio, remove classes
                this.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Remove feedback visual ao focar (para não distrair)
        campoEmail.addEventListener('focus', function() {
            // Mantém o is-invalid se já estava inválido, mas permite editar
        });

        // Validação em tempo real ao digitar (após o primeiro blur)
        let jaValidou = false;
        campoEmail.addEventListener('blur', function() {
            jaValidou = true;
        }, { once: true });

        campoEmail.addEventListener('input', function() {
            if (jaValidou && this.value) {
                const isValid = validarEmail(this.value);
                
                if (isValid) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            }
        });
    }

    // Função para validação de CPF em tempo real
    function configurarValidacaoCPF(campoCPF) {
        if (!campoCPF) return;

        campoCPF.addEventListener('blur', function() {
            if (this.value) {
                const isValid = validarCPF(this.value);
                
                if (isValid) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            } else if (this.hasAttribute('required')) {
                this.classList.add('is-invalid');
            }
        });

        campoCPF.addEventListener('input', function() {
            // Remove validação visual enquanto digita (para não atrapalhar)
            if (this.value.replace(/\D/g, '').length < 11) {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }

    // Função para validação de matrícula em tempo real
    function configurarValidacaoMatricula(campoMatricula) {
        if (!campoMatricula) return;

        campoMatricula.addEventListener('blur', function() {
            if (this.value) {
                const isValid = validarMatricula(this.value);
                
                if (isValid) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            } else if (this.hasAttribute('required')) {
                this.classList.add('is-invalid');
            }
        });

        campoMatricula.addEventListener('input', function() {
            // Valida em tempo real
            if (this.value) {
                const isValid = validarMatricula(this.value);
                
                if (isValid) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            }
        });
    }

    // Inicialização quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        
        // Busca todos os campos de e-mail
        const camposEmail = document.querySelectorAll('input[type="email"]');
        camposEmail.forEach(configurarValidacaoEmail);

        // Busca todos os campos de CPF
        const camposCPF = document.querySelectorAll('input[name="CPF"], input[name="cpf"]');
        camposCPF.forEach(configurarValidacaoCPF);

        // Busca todos os campos de matrícula
        const camposMatricula = document.querySelectorAll('input[name="MATRICULA"], input[name="matricula"]');
        camposMatricula.forEach(configurarValidacaoMatricula);

        // Busca todos os formulários que precisam de validação
        const formularios = document.querySelectorAll('form');
        
        formularios.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                
                // Verifica validade de todos os campos
                let formValido = true;

                // Validação especial para e-mails
                const emailsNoForm = form.querySelectorAll('input[type="email"]');
                emailsNoForm.forEach(function(campoEmail) {
                    if (campoEmail.value) {
                        const isValid = validarEmail(campoEmail.value);
                        
                        if (isValid) {
                            campoEmail.classList.remove('is-invalid');
                            campoEmail.classList.add('is-valid');
                        } else {
                            campoEmail.classList.remove('is-valid');
                            campoEmail.classList.add('is-invalid');
                            formValido = false;
                        }
                    } else if (campoEmail.hasAttribute('required')) {
                        campoEmail.classList.add('is-invalid');
                        formValido = false;
                    }
                });

                // Validação especial para CPF
                const cpfsNoForm = form.querySelectorAll('input[name="CPF"], input[name="cpf"]');
                cpfsNoForm.forEach(function(campoCPF) {
                    if (campoCPF.hasAttribute('required')) {
                        if (campoCPF.value) {
                            const isValid = validarCPF(campoCPF.value);
                            
                            if (isValid) {
                                campoCPF.classList.remove('is-invalid');
                                campoCPF.classList.add('is-valid');
                            } else {
                                campoCPF.classList.remove('is-valid');
                                campoCPF.classList.add('is-invalid');
                                formValido = false;
                            }
                        } else {
                            campoCPF.classList.add('is-invalid');
                            formValido = false;
                        }
                    }
                });

                // Validação especial para matrícula
                const matriculasNoForm = form.querySelectorAll('input[name="MATRICULA"], input[name="matricula"]');
                matriculasNoForm.forEach(function(campoMatricula) {
                    if (campoMatricula.hasAttribute('required')) {
                        if (campoMatricula.value) {
                            const isValid = validarMatricula(campoMatricula.value);
                            
                            if (isValid) {
                                campoMatricula.classList.remove('is-invalid');
                                campoMatricula.classList.add('is-valid');
                            } else {
                                campoMatricula.classList.remove('is-valid');
                                campoMatricula.classList.add('is-invalid');
                                formValido = false;
                            }
                        } else {
                            campoMatricula.classList.add('is-invalid');
                            formValido = false;
                        }
                    }
                });

                // Verifica validação nativa do HTML5
                if (!form.checkValidity()) {
                    formValido = false;
                }

                // Se o formulário não for válido, previne o submit
                if (!formValido) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Scroll até o primeiro campo inválido
                    const primeiroInvalido = form.querySelector('.is-invalid, :invalid');
                    if (primeiroInvalido) {
                        primeiroInvalido.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        setTimeout(function() {
                            primeiroInvalido.focus();
                        }, 300);
                    }
                }

                // Adiciona classe do Bootstrap para mostrar feedback
                form.classList.add('was-validated');
            });

            // Remove a classe was-validated ao resetar o formulário
            form.addEventListener('reset', function() {
                this.classList.remove('was-validated');
                const campos = this.querySelectorAll('.is-valid, .is-invalid');
                campos.forEach(function(campo) {
                    campo.classList.remove('is-valid', 'is-invalid');
                });
            });
        });
    });

    // Observador para formulários carregados dinamicamente (modais AJAX)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    // Verifica se é um formulário ou contém formulários
                    const forms = node.matches && node.matches('form') 
                        ? [node] 
                        : node.querySelectorAll ? node.querySelectorAll('form') : [];
                    
                    // Configura validação para campos nos novos formulários
                    forms.forEach(function(form) {
                        const emailsNoForm = form.querySelectorAll('input[type="email"]');
                        emailsNoForm.forEach(configurarValidacaoEmail);
                        
                        const cpfsNoForm = form.querySelectorAll('input[name="CPF"], input[name="cpf"]');
                        cpfsNoForm.forEach(configurarValidacaoCPF);
                        
                        const matriculasNoForm = form.querySelectorAll('input[name="MATRICULA"], input[name="matricula"]');
                        matriculasNoForm.forEach(configurarValidacaoMatricula);
                    });
                }
            });
        });
    });

    // Observa mudanças no body para detectar modais carregados via AJAX
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

})();
