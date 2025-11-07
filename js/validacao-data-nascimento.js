/**
 * Validação de Data de Nascimento
 * Aplica validação visual e lógica para campos de data de nascimento
 */

(function() {
    'use strict';

    // Função para validar data de nascimento
    function validarDataNascimento(inputElement) {
        if (!inputElement || !inputElement.value) {
            return true; // Se não tem valor e não é required, passa
        }

        const valor = inputElement.value;
        const dataNasc = new Date(valor + 'T00:00:00'); // Força UTC para evitar problemas de timezone
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);

        // Data mínima: 1900-01-01
        const dataMinima = new Date('1900-01-01T00:00:00');
        
        // Validações
        if (dataNasc > hoje) {
            inputElement.setCustomValidity('A data de nascimento não pode ser no futuro.');
            return false;
        }
        
        if (dataNasc < dataMinima) {
            inputElement.setCustomValidity('A data de nascimento deve ser posterior a 01/01/1900.');
            return false;
        }

        // Calcula idade
        let idade = hoje.getFullYear() - dataNasc.getFullYear();
        const mesAtual = hoje.getMonth();
        const diaAtual = hoje.getDate();
        const mesNasc = dataNasc.getMonth();
        const diaNasc = dataNasc.getDate();
        
        if (mesAtual < mesNasc || (mesAtual === mesNasc && diaAtual < diaNasc)) {
            idade--;
        }

        // Idade máxima: 120 anos
        if (idade > 120) {
            inputElement.setCustomValidity('A idade calculada excede 120 anos. Verifique a data.');
            return false;
        }

        // Se passou em todas as validações
        inputElement.setCustomValidity('');
        return true;
    }

    // Adiciona validação visual do Bootstrap
    function aplicarEstiloValidacao(inputElement, isValid) {
        if (isValid) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.add('is-valid');
        } else {
            inputElement.classList.remove('is-valid');
            inputElement.classList.add('is-invalid');
        }
    }

    // Inicializa validação quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        
        // Busca todos os campos de data de nascimento
        const camposData = document.querySelectorAll(
            'input[name="data_nascimento"], input[name="DATA_NASCIMENTO"]'
        );

        camposData.forEach(function(campo) {
            
            // Define atributos min e max dinamicamente
            campo.setAttribute('min', '1900-01-01');
            campo.setAttribute('max', new Date().toISOString().split('T')[0]);

            // Adiciona div de feedback se não existir
            if (!campo.nextElementSibling || !campo.nextElementSibling.classList.contains('invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Por favor, informe uma data de nascimento válida.';
                campo.parentNode.insertBefore(feedback, campo.nextSibling);
            }

            // Validação em tempo real (ao digitar/alterar)
            campo.addEventListener('change', function() {
                const isValid = validarDataNascimento(this);
                aplicarEstiloValidacao(this, isValid);
            });

            campo.addEventListener('blur', function() {
                if (this.value) {
                    const isValid = validarDataNascimento(this);
                    aplicarEstiloValidacao(this, isValid);
                }
            });

            // Remove estilos de validação ao focar (para não distrair)
            campo.addEventListener('focus', function() {
                this.classList.remove('is-valid', 'is-invalid');
            });
        });

        // Intercepta submissão de formulários com campos de data
        const formularios = document.querySelectorAll('form');
        formularios.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                const camposDataNoForm = form.querySelectorAll(
                    'input[name="data_nascimento"], input[name="DATA_NASCIMENTO"]'
                );

                let formValido = true;

                camposDataNoForm.forEach(function(campo) {
                    if (campo.value) {
                        const isValid = validarDataNascimento(campo);
                        aplicarEstiloValidacao(campo, isValid);
                        
                        if (!isValid) {
                            formValido = false;
                        }
                    }
                });

                if (!formValido) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Scroll até o primeiro campo inválido
                    const primeiroInvalido = form.querySelector('.is-invalid');
                    if (primeiroInvalido) {
                        primeiroInvalido.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        primeiroInvalido.focus();
                    }
                }

                // Adiciona classe 'was-validated' do Bootstrap
                form.classList.add('was-validated');
            });
        });
    });

})();
