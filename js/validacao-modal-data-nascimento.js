// Validação global para campos de data de nascimento em modais (AJAX/dinâmicos)
(function(){
    function validarDataGeneric(input){
        if(!input) return;
        var val = input.value;
        if(!val){ input.classList.remove('is-valid','is-invalid'); return; }
        var hoje = new Date(); hoje.setHours(0,0,0,0);
        var data = new Date(val); data.setHours(0,0,0,0);
        var dataMin = new Date('1900-01-01');
        if(data > hoje){
            input.classList.remove('is-valid'); input.classList.add('is-invalid');
            setInvalidFeedback(input, 'A data de nascimento não pode ser no futuro.');
            return false;
        }
        if(data < dataMin){
            input.classList.remove('is-valid'); input.classList.add('is-invalid');
            setInvalidFeedback(input, 'A data de nascimento deve ser posterior a 01/01/1900.');
            return false;
        }
        // idade máxima 120
        var hojeAno = hoje.getFullYear();
        var idade = hojeAno - data.getFullYear();
        var mesHoje = hoje.getMonth(), diaHoje = hoje.getDate();
        var mesN = data.getMonth(), diaN = data.getDate();
        if(mesHoje < mesN || (mesHoje === mesN && diaHoje < diaN)) idade--;
        if(idade > 120){
            input.classList.remove('is-valid'); input.classList.add('is-invalid');
            setInvalidFeedback(input, 'A idade calculada excede 120 anos.');
            return false;
        }
        input.classList.remove('is-invalid'); input.classList.add('is-valid');
        // restore default feedback text if exists
        setInvalidFeedback(input, 'Por favor, informe uma data de nascimento válida.');
        return true;
    }

    function setInvalidFeedback(input, text){
        var fb = input.parentElement.querySelector('.invalid-feedback');
        if(fb) fb.textContent = text;
    }

    function applyToField(input){
        if(!input || input.__dataValApplied) return;
        input.__dataValApplied = true;
        // set min and max dynamically
        try{ input.setAttribute('min','1900-01-01'); input.setAttribute('max', new Date().toISOString().split('T')[0]); }catch(e){}
        var handler = function(){ validarDataGeneric(input); };
        input.addEventListener('change', handler);
        input.addEventListener('blur', handler);
        input.addEventListener('input', handler);
        input.addEventListener('focus', function(){ input.classList.remove('is-valid','is-invalid'); });
    }

    // Listen for bootstrap modal show (works when modal events bubble)
    document.addEventListener('shown.bs.modal', function(e){
        try{
            var modal = e.target;
            var input = modal.querySelector('input[name="data_nascimento"]');
            if(input) applyToField(input);
        }catch(err){ console && console.error(err); }
    }, true);

    // Fallback: observe DOM additions and apply when field appears
    var observer = new MutationObserver(function(mutations){
        mutations.forEach(function(m){
            m.addedNodes && m.addedNodes.forEach(function(node){
                if(node.nodeType !== 1) return;
                var input = null;
                if(node.matches && node.matches('input[name="data_nascimento"]')) input = node;
                else input = node.querySelector && node.querySelector('input[name="data_nascimento"]');
                if(input) applyToField(input);
            });
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // Also apply to any existing fields on page load
    document.addEventListener('DOMContentLoaded', function(){
        var existing = document.querySelectorAll('input[name="data_nascimento"]');
        existing.forEach(function(i){ applyToField(i); });
    });
})();
