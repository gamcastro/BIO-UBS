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

                            // =========================================================
                            // 4. CHAMADA CONDICIONAL:
                            // Só execute a função de "buscar paciente" se este for
                            // o modal de acolhimento.
                            // =========================================================
                            if (currentModal.id === 'acolhimentoBioUBS') {
                                setupModalEventListeners(currentModal);
                            }
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


// =========================================================================
// FUNÇÃO DE EVENTOS DO MODAL DE ACOLHIMENTO (AGORA CORRIGIDA)
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
        // CORREÇÃO: Removido um 'ar' que estava sobrando antes desta linha
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