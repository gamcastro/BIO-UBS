/**
 * Validação de Data de Nascimento
 * Aplica validação visual e lógica para campos de data de nascimento
 */

(function() {
    'use strict';
    // --- Consolidated validation (desktop + modal dynamic fields)
    // Função para validar data de nascimento
    function validarDataNascimento(inputElement) {
        if (!inputElement) return true;
        if (!inputElement.value) {
            inputElement.setCustomValidity('');
            return true; // Se não tem valor e não é required, passa
        }

        // Normaliza valor esperado do input[type=date]
        const valor = inputElement.value;
        // Usa formato ISO quando possível para evitar timezone
        const dataNasc = new Date(valor + (valor.length === 10 ? 'T00:00:00' : ''));
        const hoje = new Date(); hoje.setHours(0,0,0,0);
        const dataMinima = new Date('1900-01-01T00:00:00');

        // Verificações
        if (isNaN(dataNasc.getTime())) {
            inputElement.setCustomValidity('Formato de data inválido.');
            setInvalidFeedback(inputElement, 'Por favor, informe uma data de nascimento válida.');
            return false;
        }
        if (dataNasc > hoje) {
            inputElement.setCustomValidity('A data de nascimento não pode ser no futuro.');
            setInvalidFeedback(inputElement, 'A data de nascimento não pode ser no futuro.');
            return false;
        }
        if (dataNasc < dataMinima) {
            inputElement.setCustomValidity('A data de nascimento deve ser posterior a 01/01/1900.');
            setInvalidFeedback(inputElement, 'A data de nascimento deve ser posterior a 01/01/1900.');
            return false;
        }

        // Calcula idade e valida máximo
        let idade = hoje.getFullYear() - dataNasc.getFullYear();
        const mesAtual = hoje.getMonth(), diaAtual = hoje.getDate();
        const mesNasc = dataNasc.getMonth(), diaNasc = dataNasc.getDate();
        if (mesAtual < mesNasc || (mesAtual === mesNasc && diaAtual < diaNasc)) idade--;
        if (idade > 120) {
            inputElement.setCustomValidity('A idade calculada excede 120 anos. Verifique a data.');
            setInvalidFeedback(inputElement, 'A idade calculada excede 120 anos.');
            return false;
        }

        // Tudo ok
        inputElement.setCustomValidity('');
        setInvalidFeedback(inputElement, 'Por favor, informe uma data de nascimento válida.');
        return true;
    }

    // Aplica classes de estilo bootstrap
    function aplicarEstiloValidacao(inputElement, isValid) {
        if (!inputElement) return;
        if (isValid) {
            inputElement.classList.remove('is-invalid');
            inputElement.classList.add('is-valid');
        } else {
            inputElement.classList.remove('is-valid');
            inputElement.classList.add('is-invalid');
        }
    }

    function setInvalidFeedback(input, text) {
        if (!input) return;
        const fb = input.parentElement && input.parentElement.querySelector('.invalid-feedback');
        if (fb) fb.textContent = text;
    }

    // Aplica toda a lógica a um input ou a todos dentro de um root
    function applyToField(input) {
        if (!input || input.__vdn_applied) return;
        input.__vdn_applied = true;

        try { input.setAttribute('min','1900-01-01'); input.setAttribute('max', new Date().toISOString().split('T')[0]); } catch(e) {}

        const handler = function() {
            const isValid = validarDataNascimento(this);
            aplicarEstiloValidacao(this, isValid);
        };

        input.addEventListener('change', handler);
        input.addEventListener('blur', handler);
        input.addEventListener('input', handler);
        input.addEventListener('focus', function(){ this.classList.remove('is-valid','is-invalid'); });

        // garante feedback element
        if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('invalid-feedback')) {
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = 'Por favor, informe uma data de nascimento válida.';
            input.parentNode.insertBefore(feedback, input.nextSibling);
        }
    }

    function initValidacaoDataNascimento(root) {
        if (!root) root = document;
        // Se root for um input, aplica diretamente
        if (root.tagName && root.tagName.toLowerCase() === 'input') {
            return applyToField(root);
        }

        const inputs = root.querySelectorAll('input[name="DATA_NASCIMENTO"], input[name="data_nascimento"]');
        inputs.forEach(function(i){ applyToField(i); });

        // Forms: intercepta submit
        const forms = root.querySelectorAll('form');
        forms.forEach(function(form) {
            if (form.__vdn_submit_attached) return;
            form.__vdn_submit_attached = true;
            form.addEventListener('submit', function(e){
                let ok = true;
                const campos = form.querySelectorAll('input[name="DATA_NASCIMENTO"], input[name="data_nascimento"]');
                campos.forEach(function(c){
                    const valid = validarDataNascimento(c);
                    aplicarEstiloValidacao(c, valid);
                    if (!valid) ok = false;
                });
                if (!ok) {
                    e.preventDefault(); e.stopPropagation();
                    const primeiro = form.querySelector('.is-invalid');
                    if (primeiro) { primeiro.scrollIntoView({behavior:'smooth', block:'center'}); primeiro.focus(); }
                    form.classList.add('was-validated');
                }
            });
        });
    }

    // Inicializa no carregamento do DOM
    document.addEventListener('DOMContentLoaded', function(){ initValidacaoDataNascimento(document); });

    // Reaplica quando modal é aberta
    document.addEventListener('shown.bs.modal', function(e){ initValidacaoDataNascimento(e.target || document); });

    // jQuery fallback
    if (window.jQuery) {
        try { jQuery(document).on('shown.bs.modal', function(e){ initValidacaoDataNascimento(e.target || document); }); } catch(err) { console && console.debug && console.debug('vdn:jQuery attach failed', err); }
    }

    // MutationObserver para nodes adicionados dinamicamente
    try {
        const observer = new MutationObserver(function(muts){
            muts.forEach(function(m){
                m.addedNodes && m.addedNodes.forEach(function(node){
                    if (node.nodeType !== 1) return;
                    if (node.matches && (node.matches('input[name="DATA_NASCIMENTO"]') || node.matches('input[name="data_nascimento"]'))) {
                        initValidacaoDataNascimento(node);
                    } else if (node.querySelectorAll) {
                        const found = node.querySelectorAll('input[name="DATA_NASCIMENTO"], input[name="data_nascimento"]');
                        if (found && found.length) initValidacaoDataNascimento(node);
                    }
                });
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    } catch(err) { console && console.debug && console.debug('vdn: MutationObserver fail', err); }

    // Expor a função principal para uso por outros scripts (ex: carregamento via AJAX)
    try {
        window.initValidacaoDataNascimento = initValidacaoDataNascimento;
    } catch (e) {
        // ambiente restrito, ignora
    }

})();
