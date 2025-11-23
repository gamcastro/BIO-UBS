console.log('[custom.js] carregado');
document.addEventListener('DOMContentLoaded', function () {
    console.log('[custom.js] DOMContentLoaded');
});

// Delegação para qualquer modal aberto no futuro
document.addEventListener('show.bs.modal', function(event){
    const currentModal = event.target;
    console.log('[modal] show disparado', currentModal.id);
    const button = event.relatedTarget;
    if (button && button.hasAttribute('data-url')) {
        var url = button.getAttribute('data-url');
        var modalContent = currentModal.querySelector('.modal-content');
        if (url && modalContent) {
            modalContent.innerHTML = '<div class="modal-body text-center"><div class="spinner-border" role="status"><span class="visualmente-hidden">Carregando...</span></div></div>';
            axios.get(url)
                .then(function(resp){
                    modalContent.innerHTML = resp.data;
                    try { if (window.initCustomBindings) window.initCustomBindings(modalContent); } catch(e) {}
                    try { if (window.reaplicarMascaras) window.reaplicarMascaras(modalContent); } catch(e) {}
                    try { if (window.initValidacaoDataNascimento) window.initValidacaoDataNascimento(modalContent); } catch(e) {}
                    try { if (window.initTomSelectForModal) window.initTomSelectForModal(currentModal); } catch(e) { console.warn('Falha initTomSelectForModal', e); }
                })
                .catch(function(error){ console.error('Erro carregando conteúdo modal', error); });
        }
    } else {
        // Modal estático: ainda assim inicializa bindings e TomSelect
        try { if (window.initTomSelectForModal) window.initTomSelectForModal(currentModal); } catch(e) {}
    }
});

document.addEventListener('shown.bs.modal', function(event){
    console.log('[modal] shown', event.target.id);
});

document.addEventListener('hidden.bs.modal', function(event){
    if (['acolhimentoBioUBS','updateBioUBS','deleteBioUBS'].includes(event.target.id)){
        var modalContent = event.target.querySelector('.modal-content');
        if (modalContent) {
            modalContent.innerHTML = '<div class="modal-body text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
        }
    }
});

// -------------------------------------------------------------------------
// Reaplica máscaras em inputs adicionados dinamicamente ao exibir qualquer modal
// -------------------------------------------------------------------------
document.addEventListener('shown.bs.modal', function(e){
    var root = e.target;
    if(!root) return;

    // Preferência: função personalizada se existir
    if (typeof window.reaplicarMascaras === 'function') {
        try { window.reaplicarMascaras(root); } catch(err){ console.debug('reaplicarMascaras (shown) falhou', err); }
        return;
    }

    // Fallback: procurar data-mask e aplicar função aplicaMascara se existir
    if (typeof window.aplicaMascara === 'function') {
        root.querySelectorAll('[data-mask]').forEach(function(el){
            try { window.aplicaMascara(el); } catch(err){ console.debug('aplicaMascara individual falhou', err); }
        });
    }
});

// -------------------------------------------------------------------------
// TomSelect genérico para qualquer campo de município em modais
// -------------------------------------------------------------------------
const __tomSelectMunicipiosRegistry = new WeakMap(); // input -> instance

function buildMunicipioUrl(uf, query){
    const parts = location.pathname.split('/').filter(Boolean);
    const base = parts.length ? '/' + parts[0] : '';
    return base + '/ajax/municipios.php?uf=' + encodeURIComponent(uf) + '&q=' + encodeURIComponent(query || '');
}

// Busca direta de nome por ID sem depender de lista completa (ajuda em edições)
async function fetchMunicipioNomePorId(id){
    if(!id) return null;
    try {
        const ufOptions = Array.from(document.querySelectorAll('select option'))
            .map(o => o.value).filter(v => v && /(\d{1,3}|[A-Za-z]{2})/.test(v));
        const tried = new Set();
        for(const uf of ufOptions){
            if(tried.has(uf)) continue; tried.add(uf);
            const resp = await fetch(buildMunicipioUrl(uf, ''));
            if(!resp.ok) continue;
            const list = await resp.json();
            const found = list.find(it => String(it.id) === String(id));
            if(found) return found.nome || null;
        }
    } catch(e){}
    return null;
}

function initTomSelectForModal(modal){
    if(!modal) return;
    console.log('[TomSelect município] initTomSelectForModal start', {modalId: modal.id});
    const municipioInputs = modal.querySelectorAll('input#MUNICIPIO, input#municipio, input[id*="municipio"], input[name="municipio"], input[name="MUNICIPIO"]');
    console.log('[TomSelect município] encontrados inputs município:', municipioInputs.length);
    if(!municipioInputs.length){
        // tenta novamente mais tarde (conteúdo pode não ter carregado ainda)
        setTimeout(()=>{
            const laterInputs = modal.querySelectorAll('input#MUNICIPIO, input#municipio, input[id*="municipio"], input[name="municipio"], input[name="MUNICIPIO"]');
            console.log('[TomSelect município] segunda tentativa inputs:', laterInputs.length);
            if(laterInputs.length){
                initTomSelectForModal(modal); // recursivo controlado
            }
        },120);
        return;
    }
    const ufSelect = modal.querySelector('select[name="estado"], select[name="uf"], select[id*="cad-uf"], select[id*="UF"], select[name="UF"], select[name="ESTADO_ENDERECO"], select[name="estado_endereco"], select[name="ESTADO"], select[name="estado_endereco"]');
    console.log('[TomSelect município] ufSelect encontrado?', !!ufSelect, ufSelect ? ufSelect.name : null);
    if(!ufSelect){
        setTimeout(()=>{ const laterUf = modal.querySelector('select[name="ESTADO_ENDERECO"], select[name="UF"], select[name="estado"], select[name="uf"]'); console.log('[TomSelect município] segunda tentativa ufSelect?', !!laterUf); if(laterUf){ initTomSelectForModal(modal);} },120);
        return;
    }
    municipioInputs.forEach(function(inp){
        if(__tomSelectMunicipiosRegistry.has(inp)) return;
        const prefilledValue = inp.value ? inp.value.trim() : '';
        const prefilledLabel = inp.getAttribute('data-pref-label') || '';
        console.log('[TomSelect município] init campo', {id: inp.id, prefilledValue, prefilledLabel, ufValue: ufSelect.value});
        // Correção rápida: se há label amigável e o input ainda mostra só números, aplica antes de TomSelect
        if(prefilledValue && prefilledLabel && prefilledLabel !== prefilledValue && /^\d+$/.test(prefilledValue)){
            inp.setAttribute('data-original-id', prefilledValue);
            inp.value = prefilledLabel; // mostra nome enquanto TomSelect não monta
            console.log('[TomSelect município] substituindo valor bruto pelo label antes da instanciação');
        }
    let instance = null;
    try {
    instance = new TomSelect(inp, {
            plugins:['clear_button'],
            maxItems:1,
            valueField:'id',
            labelField:'nome',
            searchField:'nome',
            create:false,
            preload:true,
            maxOptions:null,
            placeholder:'Selecione a UF primeiro',
            shouldLoad:function(){return true;},
            load:function(query,callback){
                const uf=ufSelect.value; if(!uf){callback();return;}
                fetch(buildMunicipioUrl(uf,query)).then(r=>r.json()).then(callback).catch(()=>callback());
            },
            render:{
                no_results:()=>'<div class="no-results px-2 py-1 text-muted">Nenhum município encontrado</div>',
                loading:()=>'<div class="loading px-2 py-1 text-muted">Carregando municípios...</div>'
            },
            onInitialize:function(){
                const ts=this;
                if(prefilledValue){ ts.addOption({id:prefilledValue,nome:prefilledLabel||prefilledValue}); ts.setValue(prefilledValue,true); }
                if(!ufSelect.value){ ts.disable(); } else { ts.enable(); }
                const needsLookup = prefilledValue && (!prefilledLabel || prefilledLabel.trim()==='' || prefilledLabel===prefilledValue || /^\d+$/.test(prefilledLabel));
                if(needsLookup){
                    (async()=>{
                        let nome=null;
                        // 1) Tenta endpoint direto por ID
                        try {
                            const r1 = await fetch((()=>{ const parts=location.pathname.split('/').filter(Boolean); const base=parts.length?'/'+parts[0]:''; return base + '/ajax/municipio_por_id.php?id=' + encodeURIComponent(prefilledValue); })());
                            if(r1.ok){ const j = await r1.json(); if(j && j.nome) nome = j.nome; }
                        } catch(e){ console.debug('Lookup direto falhou', e); }
                        if(ufSelect.value){
                            try {
                                const r=await fetch(buildMunicipioUrl(ufSelect.value,''));
                                if(r.ok){ const list=await r.json(); const found=list.find(it=>String(it.id)===String(prefilledValue)); if(found) nome=found.nome; }
                            } catch(e){}
                        }
                        if(!nome){ nome=await fetchMunicipioNomePorId(prefilledValue); }
                        if(nome && nome!==prefilledValue){ ts.updateOption(prefilledValue,{id:prefilledValue,nome:nome}); ts.setValue(prefilledValue,true); }
                        else { console.warn('[TomSelect município] label não resolvido, mantendo ID', {prefilledValue, prefilledLabel}); }
                    })();
                }
            }
        });
        } catch(err){
            console.error('[TomSelect município] falha ao instanciar', err);
            // Fallback: mostra label amigável no próprio input se existir
            if(prefilledLabel && prefilledLabel !== prefilledValue){
                inp.setAttribute('data-original-id', prefilledValue);
                inp.value = prefilledLabel;
            }
            return; // não registra nem adiciona listeners
        }
        ufSelect.addEventListener('change',function(){
            instance.clear(); instance.clearOptions();
            if(this.value){
                instance.enable(); instance.settings.placeholder='Selecione um município'; instance.inputState(); instance.load('');
                if(prefilledValue){ setTimeout(()=>{ if(instance.options[prefilledValue]) instance.setValue(prefilledValue,true); },150); }
            } else {
                instance.disable(); instance.settings.placeholder='Selecione a UF primeiro'; instance.inputState();
            }
        });
        __tomSelectMunicipiosRegistry.set(inp,instance);
        console.log('[TomSelect município] instanciado', {input: inp.id, hasOption: !!instance.options[prefilledValue], instanceType: typeof instance});
    });
    console.log('[TomSelect município] finalizado init para modal', modal.id);
}

function destroyTomSelectForModal(modal){
    if(!modal) return;
    const municipioInputs = modal.querySelectorAll('input#MUNICIPIO, input#municipio, input[id*="municipio"], input[name="municipio"], input[name="MUNICIPIO"]');
    municipioInputs.forEach(function(inp){
        const inst=__tomSelectMunicipiosRegistry.get(inp);
        if(inst){ inst.destroy(); __tomSelectMunicipiosRegistry.delete(inp); }
    });
}

// Expõe função globalmente para ser chamada após carregamento dinâmico
window.initTomSelectForModal = initTomSelectForModal;
window.destroyTomSelectForModal = destroyTomSelectForModal;

// Inicializa em modais estáticos ao serem mostrados
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.modal').forEach(function(m){
        m.addEventListener('shown.bs.modal', function(){
            initTomSelectForModal(m);
            
            // Foco automático: prefere #NOME_COMPLETO se presente (profissionais), senão #CPF (pacientes)
            setTimeout(function(){
                try {
                    var nomeInput = m.querySelector('#NOME_COMPLETO');
                    if (nomeInput) {
                        nomeInput.focus();
                        try {
                            if (typeof nomeInput.setSelectionRange === 'function') {
                                nomeInput.setSelectionRange(0, 0);
                            } else {
                                nomeInput.selectionStart = nomeInput.selectionEnd = 0;
                            }
                        } catch (e) {}
                        return;
                    }
                    var cpfInput = m.querySelector('#CPF');
                    if (cpfInput) {
                        cpfInput.focus();
                        try {
                            if (typeof cpfInput.setSelectionRange === 'function') {
                                cpfInput.setSelectionRange(0, 0);
                            } else {
                                cpfInput.selectionStart = cpfInput.selectionEnd = 0;
                            }
                        } catch (e) {}
                        return;
                    }
                    // fallback: foco no primeiro input do modal
                    var first = m.querySelector('input:not([type=hidden]):not([disabled]), select:not([disabled])');
                    if (first) first.focus();
                } catch (e) { console && console.debug && console.debug('focus modal', e); }
            }, 100);
        });
        m.addEventListener('hidden.bs.modal', function(){
            destroyTomSelectForModal(m);
        });
    });
});

// -------------------------------------------------------------------------
// Comportamentos genéricos para reduzir JS inline
// - data-sync="fieldName"  : ao alterar (change) seta o input[name=fieldName] com o valor
// - data-titlecase="true"  : aplica capitalizeNameWithPrepositions no evento blur/input
// - data-altera-nome-profissional="true": chama alteraNomeProfissional() no keyup
// -------------------------------------------------------------------------
// Exponha uma função para inicializar bindings genéricos em um root (document ou conteúdo injetado)
function initCustomBindings(root) {
    root = root || document;
    try {
        // data-sync
        root.querySelectorAll('[data-sync]').forEach(function(el){
            if (el.__ds_bound) return; el.__ds_bound = true;
            el.addEventListener('change', function(){
                var targetName = el.getAttribute('data-sync');
                if (!targetName) return;
                var target = document.getElementsByName(targetName)[0];
                if (target) target.value = el.value;
            });
        });

        // data-titlecase: capitaliza no blur/paste e também em tempo real (input) com debounce
        root.querySelectorAll('[data-titlecase]').forEach(function(inp){
            if (inp.__title_bound) return; inp.__title_bound = true;
            var fn = window.capitalizeNameWithPrepositions || function(v){return v;};
            inp.addEventListener('blur', function(){ if (this.value) this.value = fn(this.value); });
            inp.addEventListener('paste', function(){ var self=this; setTimeout(function(){ if(self.value) self.value = fn(self.value); }, 10); });
            // Debounce helper
            var timer = null;
            inp.addEventListener('input', function(){
                var self = this;
                if (timer) clearTimeout(timer);
                timer = setTimeout(function(){ if(self.value) self.value = fn(self.value); }, 250);
            });
        });

        // data-altera-nome-profissional
        root.querySelectorAll('[data-altera-nome-profissional]').forEach(function(inp){
            if (inp.__anp_bound) return; inp.__anp_bound = true;
            if (typeof window.alteraNomeProfissional === 'function') {
                inp.addEventListener('keyup', function(){ try{ window.alteraNomeProfissional(); }catch(e){} });
            }
        });

        // data-numeric: remove qualquer caractere não númerico em tempo real
        root.querySelectorAll('[data-numeric]').forEach(function(inp){
            if (inp.__numeric_bound) return; inp.__numeric_bound = true;
            inp.addEventListener('input', function(){ this.value = this.value.replace(/\D+/g, ''); });
        });

        // data-callback="fnName": chama window[fnName] no evento keyup (se existir)
        root.querySelectorAll('[data-callback]').forEach(function(inp){
            if (inp.__callback_bound) return; inp.__callback_bound = true;
            var fnName = inp.getAttribute('data-callback');
            if (!fnName) return;
            var fn = window[fnName];
            if (typeof fn === 'function') {
                inp.addEventListener('keyup', function(){ try{ fn.call(this); }catch(e){} });
            }
        });
    } catch (err) { console && console.debug && console.debug('custom:bind generic data attrs', err); }
}

// Inicializa no carregamento da página
document.addEventListener('DOMContentLoaded', function(){ initCustomBindings(document); });


// =========================================================================
// FUNÇÃO DE EVENTOS DO MODAL DE ACOLHIMENTO
// =========================================================================
function setupModalEventListeners(modalElement) {
    var btnBuscar = modalElement.querySelector('#btnBuscar');
    var inputBusca = modalElement.querySelector('#buscaPaciente');
    var btnEncaminhar = modalElement.querySelector('#btnEncaminhar');
    var divDadosPaciente = modalElement.querySelector('#dadosPaciente');
    var spanNomePaciente = modalElement.querySelector('#nomePaciente span');
    var spanNascimentoPaciente = modalElement.querySelector('#nascimentoPaciente');
    var inputPacienteId = modalElement.querySelector('#pacienteId');
    var txtQueixa = modalElement.querySelector('#queixaPrincipal');

    function checkEnableSubmit() {
        if (btnEncaminhar) {
            btnEncaminhar.disabled = !(inputPacienteId && inputPacienteId.value && txtQueixa && txtQueixa.value.trim());
        }
    }
    checkEnableSubmit();

    if (inputBusca) {
        inputBusca.addEventListener('input', function () {
            if (divDadosPaciente) divDadosPaciente.style.display = 'none';
            if (inputPacienteId) inputPacienteId.value = '';
            checkEnableSubmit();
        });
    }

    if (txtQueixa) {
       
        txtQueixa.addEventListener('input', checkEnableSubmit);
    }

    if (btnBuscar) {
        btnBuscar.addEventListener('click', function () {
            var termoBusca = inputBusca ? inputBusca.value.replace(/\D/g, '') : '';

            if (termoBusca.length > 0) {
                if (spanNomePaciente) spanNomePaciente.textContent = 'Buscando...';
                if (divDadosPaciente) divDadosPaciente.style.display = 'block';

                const params = new URLSearchParams();
                params.append('termo_busca', termoBusca);

                axios.post('querys/buscaPaciente.php', params)
                    .then(function (response) {
                        var data = response.data;

                        // CORREÇÃO: Removido o 't' que estava sobrando antes deste 'if'
                        if (data.status === 'success') {
                            if (spanNomePaciente) spanNomePaciente.textContent = data.nome;
                            if (spanNascimentoPaciente) spanNascimentoPaciente.textContent = data.data_nascimento;
                            if (inputPacienteId) inputPacienteId.value = data.id;
                            checkEnableSubmit();
                        } else {
                            if (divDadosPaciente) divDadosPaciente.style.display = 'none';
                            if (inputPacienteId) inputPacienteId.value = '';
                            checkEnableSubmit();
                            alert(data.message || 'Paciente não encontrado.');
                            // CORREÇÃO: Removido um 'transform' que estava sobrando aqui
                        }
                    })
                    .catch(function (error) {
                        console.error('Erro na requisição AJAX:', error);
                        if (divDadosPaciente) divDadosPaciente.style.display = 'none';
                        if (inputPacienteId) inputPacienteId.value = '';
                        checkEnableSubmit();
                        alert('Ocorreu um erro ao buscar o paciente.');
                    });
            } else {
                alert('Por favor, digite um CPF para realizar a busca.');
            }
        });
    }
} // Fim da função setupModalEventListeners

// =========================================================================
// FUNÇÃO DE CAPITALIZAÇÃO DE NOMES COM PREPOSIÇÕES
// =========================================================================
/**
 * Capitaliza nomes próprios respeitando preposições e conectivos.
 * Exemplo: "maria da silva" -> "Maria da Silva"
 * @param {string} value - O texto a ser capitalizado
 * @returns {string} - O texto capitalizado
 */
function capitalizeNameWithPrepositions(value) {
    var prepositions = ['das', 'dos', 'da', 'do', 'de', 'di', 'du', 'del', 'della', 'von', 'van', 'el', 'la', 'e', 'y'];
    return value.replace(/\w\S*/g, function(txt, index, fullText) {
        var word = txt.toLowerCase();
        // Se for primeira palavra ou não for preposição, capitaliza
        var wordPosition = fullText.substring(0, index).trim().length === 0 ? 0 : 1;
        return (wordPosition > 0 && prepositions.indexOf(word) !== -1) 
            ? word 
            : txt.charAt(0).toUpperCase() + txt.substring(1).toLowerCase();
    });
}