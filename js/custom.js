document.addEventListener('DOMContentLoaded', function () {

    // 1. Encontre TODOS os elementos com a classe .modal na página
    var allModals = document.querySelectorAll('.modal');

    // 2. Faça um loop (forEach) em cada modal encontrado
    allModals.forEach(function (modalElement) {

        // 3. Adicione o listener 'show.bs.modal' (prestes a abrir) A CADA MODAL
        modalElement.addEventListener('show.bs.modal', function (event) {

            // 'event.target' é o modal que está abrindo (ex: #acolhimentoBioUBS ou #updateBioUBS)
            var currentModal = event.target;

            // 'event.relatedTarget' é o botão/link que disparou o modal
            var button = event.relatedTarget;

            // Verifique se o botão que abriu o modal REALMENTE TEM um 'data-url'
            if (button && button.hasAttribute('data-url')) {
                var url = button.getAttribute('data-url');
                var modalContent = currentModal.querySelector('.modal-content');

                if (url && modalContent) {

                    // Coloca o spinner (exatamente como estava na sua "casca")
                    modalContent.innerHTML = `
                        <div class="modal-body text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>`;

                    // Busca o conteúdo HTML do modal usando Axios
                    axios.get(url)
                        .then(function (response) {
                            // Se a busca for bem-sucedida, injeta o HTML no modal
                            modalContent.innerHTML = response.data;

                            // Normaliza nomes pré-preenchidos (capitaliza respeitando preposições)
                            try {
                                function applyNameNormalization(container) {
                                    if (typeof capitalizeNameWithPrepositions !== 'function') return;
                                    var nameInputs = container.querySelectorAll('input[name="nome"], input[name="nome_mae"], input[name="nome_pai"]');
                                    nameInputs.forEach(function (inp) {
                                        try {
                                            if (inp && inp.value && inp.value.trim().length > 0) {
                                                inp.value = capitalizeNameWithPrepositions(inp.value);
                                            }
                                            if (!inp.dataset.normalizeBound) {
                                                inp.addEventListener('blur', function () {
                                                    if (inp.value) inp.value = capitalizeNameWithPrepositions(inp.value);
                                                });
                                                inp.addEventListener('paste', function () {
                                                    setTimeout(function () {
                                                        if (inp.value) inp.value = capitalizeNameWithPrepositions(inp.value);
                                                    }, 10);
                                                });
                                                inp.dataset.normalizeBound = '1';
                                            }
                                        } catch (e) {
                                            console.error('Erro ao normalizar input:', e);
                                        }
                                    });
                                }

                                // Aplicação imediata e reforçada após um pequeno delay
                                applyNameNormalization(modalContent);
                                setTimeout(function () { applyNameNormalization(modalContent); }, 80);
                            } catch (err) {
                                console.error('Erro ao normalizar nomes no modal:', err);
                            }

                            // =========================================================
                            // 4. CHAMADA CONDICIONAL:
                            // Só execute a função de "buscar paciente" se este for
                            // o modal de acolhimento.
                            // =========================================================
                            if (currentModal.id === 'acolhimentoBioUBS') {
                                setupModalEventListeners(currentModal);
                            }

                            // Reaplica máscaras e validação para o conteúdo injetado (fallback explícito)
                            try {
                                if (window.reaplicarMascaras) window.reaplicarMascaras(modalContent);
                            } catch (e) { console && console.debug && console.debug('custom: reaplicarMascaras failed', e); }
                            try {
                                if (window.initCustomBindings) window.initCustomBindings(modalContent);
                            } catch (e) { console && console.debug && console.debug('custom: initCustomBindings failed', e); }
                            try {
                                if (window.initValidacaoDataNascimento) window.initValidacaoDataNascimento(modalContent);
                            } catch (e) { console && console.debug && console.debug('custom: initValidacaoDataNascimento failed', e); }
                            
                            // Inicializa TomSelect para modais carregados dinamicamente
                            try {
                                if (window.initTomSelectForModal) window.initTomSelectForModal(currentModal);
                            } catch (e) { console && console.debug && console.debug('custom: initTomSelectForModal failed', e); }
                        })
                        .catch(function (error) {
                            // Se der erro ao carregar o conteúdo
                            console.error('Erro ao carregar conteúdo do modal:', error);
                            var errorTitle = (error.response && error.response.status)
                                ? `Erro ${error.response.status}`
                                : 'Erro de Conexão';
                            var errorText = (error.response && error.response.statusText)
                                ? error.response.statusText
                                : 'Não foi possível carregar o conteúdo.';

                            modalContent.innerHTML = `
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger">${errorTitle}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>URL:</strong> ${url}</p>
                                    <p class="text-danger">${errorText}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>`;
                        });
                }
            }
        }); // Fim do listener 'show.bs.modal'

        // 5. Adicione o listener 'hidden.bs.modal' (depois de fechar) A CADA MODAL
        modalElement.addEventListener('hidden.bs.modal', function (event) {

            // Só limpa o conteúdo de modais que carregam via data-url
            if (event.target.id === 'acolhimentoBioUBS' ||
                event.target.id === 'updateBioUBS' ||
                event.target.id === 'deleteBioUBS') {
                var modalContent = event.target.querySelector('.modal-content');
                if (modalContent) {
                    // Restaura o spinner original, preparando para a próxima abertura
                    modalContent.innerHTML = `
                        <div class="modal-body text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>`;
                }
            }
        }); // Fim do listener 'hidden.bs.modal'

    }); // Fim do loop forEach

}); // Fim do listener 'DOMContentLoaded'

// -------------------------------------------------------------------------
// TomSelect genérico para qualquer campo de município em modais
// -------------------------------------------------------------------------
const __tomSelectMunicipiosRegistry = new WeakMap(); // input -> instance

function buildMunicipioUrl(uf, query){
    const parts = location.pathname.split('/').filter(Boolean);
    const base = parts.length ? '/' + parts[0] : '';
    return base + '/ajax/municipios.php?uf=' + encodeURIComponent(uf) + '&q=' + encodeURIComponent(query || '');
}

function initTomSelectForModal(modal){
    if(!modal) return;
    // Procura inputs de município (variações de id/name)
    const municipioInputs = modal.querySelectorAll('input#MUNICIPIO, input#municipio, input[id*="municipio"], input[name="municipio"], input[name="MUNICIPIO"]');
    if(!municipioInputs.length) return;

    // Detecta UF select
    const ufSelect = modal.querySelector('select[name="estado"], select[name="uf"], select[id*="cad-uf"], select[id*="UF"], select[name="UF"]');
    if(!ufSelect) return; // Sem UF não inicializa

    municipioInputs.forEach(function(inp){
        if(__tomSelectMunicipiosRegistry.has(inp)) return; // Já inicializado

        // Captura valor pré-preenchido (para modais de edição)
        const prefilledValue = inp.value ? inp.value.trim() : '';

        const instance = new TomSelect(inp, {
            plugins: ['clear_button'],
            maxItems: 1,
            valueField: 'id',
            labelField: 'nome',
            searchField: 'nome',
            create: true, // Permite criar valor temporário para nome pré-preenchido
            preload: true,
            maxOptions: null,
            placeholder: 'Selecione a UF primeiro',
            shouldLoad: function(){ return true; },
            load: function(query, callback){
                const uf = ufSelect.value;
                if(!uf){ callback(); return; }
                fetch(buildMunicipioUrl(uf, query))
                    .then(r => r.json())
                    .then(json => callback(json))
                    .catch(() => callback());
            },
            onInitialize: function(){
                if(!ufSelect.value) this.disable();
                // Se tem valor pré-preenchido (modal de edição), adiciona como opção
                if(prefilledValue && ufSelect.value){
                    this.addOption({id: prefilledValue, nome: prefilledValue});
                    this.setValue(prefilledValue, true);
                }
            }
        });

        // Reage à mudança da UF
        ufSelect.addEventListener('change', function(){
            if(!instance) return;
            instance.clear();
            instance.clearOptions();
            if(this.value){
                instance.enable();
                instance.settings.placeholder = 'Selecione um município';
                // Força atualização imediata do placeholder sem esperar blur
                instance.inputState();
                instance.load('');
            } else {
                instance.disable();
                instance.settings.placeholder = 'Selecione a UF primeiro';
                instance.inputState();
            }
        });

        __tomSelectMunicipiosRegistry.set(inp, instance);
    });
}

function destroyTomSelectForModal(modal){
    if(!modal) return;
    const municipioInputs = modal.querySelectorAll('input#MUNICIPIO, input#municipio, input[id*="municipio"], input[name="municipio"], input[name="MUNICIPIO"]');
    municipioInputs.forEach(function(inp){
        const inst = __tomSelectMunicipiosRegistry.get(inp);
        if(inst){
            inst.destroy();
            __tomSelectMunicipiosRegistry.delete(inp);
        }
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