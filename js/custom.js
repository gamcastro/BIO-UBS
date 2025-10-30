document.addEventListener('DOMContentLoaded', function () {

    var acolhimentoModalElement = document.getElementById('acolhimentoBioUBS');

    if (acolhimentoModalElement) {
        
        // Listener para QUANDO O MODAL ESTÁ PRESTES A ABRIR ('show.bs.modal')
        acolhimentoModalElement.addEventListener('show.bs.modal', function (event) {
            
            var button = event.relatedTarget; // O link <a> que disparou o modal
            var url = button.getAttribute('data-url'); // Pega a URL do atributo data-url
            var modalContent = acolhimentoModalElement.querySelector('.modal-content');

            // Se a URL existe e o conteúdo ainda não foi carregado
            if (url && modalContent) {
                
                // Mostra um indicador de carregamento
                modalContent.innerHTML = `
                    <div class="modal-body text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>`;

                // Busca o conteúdo HTML do modal usando Axios
                axios.get(url)
                    .then(function(response) {
                        // Se a busca for bem-sucedida, injeta o HTML no modal
                        modalContent.innerHTML = response.data;
                        
                        // AGORA, DEPOIS de injetar o HTML, configura os listeners internos
                        setupModalEventListeners(acolhimentoModalElement); 
                    })
                    .catch(function(error) {
                        // Se der erro ao carregar o conteúdo
                        console.error('Erro ao carregar conteúdo do modal:', error);
                        modalContent.innerHTML = `
                            <div class="modal-header">
                                <h5 class="modal-title">Erro</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-danger">Não foi possível carregar o formulário de acolhimento.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>`;
                    });
            }
        }); // Fim do listener 'show.bs.modal'

        // Listener para QUANDO O MODAL TERMINA DE FECHAR ('hidden.bs.modal')
        acolhimentoModalElement.addEventListener('hidden.bs.modal', function () {
            // Limpa o conteúdo para garantir que carregue novamente na próxima vez
            var modalContent = acolhimentoModalElement.querySelector('.modal-content');
            if (modalContent) {
                modalContent.innerHTML = ''; 
            }
        });

    } // Fim do if (acolhimentoModalElement)

}); // Fim do listener 'DOMContentLoaded'


// ---- Função Separada para Configurar os Eventos DENTRO do Modal ----
function setupModalEventListeners(modalElement) {
    var btnBuscar = modalElement.querySelector('#btnBuscar');
    var inputBusca = modalElement.querySelector('#buscaPaciente');
    var btnEncaminhar = modalElement.querySelector('#btnEncaminhar'); // Assumindo que você adicionou este ID ao botão submit
    var divDadosPaciente = modalElement.querySelector('#dadosPaciente');
    var spanNomePaciente = modalElement.querySelector('#nomePaciente span');
    var spanNascimentoPaciente = modalElement.querySelector('#nascimentoPaciente');
    var inputPacienteId = modalElement.querySelector('#pacienteId');
    var txtQueixa = modalElement.querySelector('#queixaPrincipal'); // Adicionado para validação

    // Garante que o botão de encaminhar comece desabilitado E SÓ HABILITE SE AMBOS PACIENTE E QUEIXA ESTIVEREM OK
    function checkEnableSubmit() {
        if(btnEncaminhar) {
             // Habilita só se pacienteId tiver valor E queixa tiver algum texto
            btnEncaminhar.disabled = !(inputPacienteId && inputPacienteId.value && txtQueixa && txtQueixa.value.trim());
        }
    }
    checkEnableSubmit(); // Verifica estado inicial (deve estar desabilitado)

    if (inputBusca) {
        inputBusca.addEventListener('input', function() {
            if (divDadosPaciente) divDadosPaciente.style.display = 'none';
            if(inputPacienteId) inputPacienteId.value = ''; // Limpa ID do paciente se digitar novo CPF
             checkEnableSubmit(); // Desabilita o botão ao digitar novo CPF
        });
    }
    
    // Listener para a queixa, para habilitar o botão submit
     if (txtQueixa) {
        txtQueixa.addEventListener('input', checkEnableSubmit);
     }


    if (btnBuscar) {
        btnBuscar.addEventListener('click', function() {
            var termoBusca = inputBusca ? inputBusca.value.replace(/\D/g, '') : '';

            if (termoBusca.length > 0) {
                if (spanNomePaciente) spanNomePaciente.textContent = 'Buscando...';
                if (divDadosPaciente) divDadosPaciente.style.display = 'block';

                const params = new URLSearchParams();
                params.append('termo_busca', termoBusca);

                axios.post('querys/buscaPaciente.php', params)
                    .then(function (response) {
                        var data = response.data;
                        if (data.status === 'success') {
                            if (spanNomePaciente) spanNomePaciente.textContent = data.nome;
                            if (spanNascimentoPaciente) spanNascimentoPaciente.textContent = data.data_nascimento;
                            if (inputPacienteId) inputPacienteId.value = data.id;
                             checkEnableSubmit(); // Verifica se pode habilitar o botão submit
                        } else {
                            if (divDadosPaciente) divDadosPaciente.style.display = 'none';
                             if(inputPacienteId) inputPacienteId.value = ''; // Limpa ID
                             checkEnableSubmit(); // Desabilita o botão submit
                            alert(data.message || 'Paciente não encontrado.');
                        }
                    })
                    .catch(function (error) {
                        console.error('Erro na requisição AJAX:', error);
                        if (divDadosPaciente) divDadosPaciente.style.display = 'none';
                         if(inputPacienteId) inputPacienteId.value = ''; // Limpa ID
                         checkEnableSubmit(); // Desabilita o botão submit
                        alert('Ocorreu um erro ao buscar o paciente.');
                    });
            } else {
                alert('Por favor, digite um CPF para realizar a busca.');
            }
        });
    }
} // Fim da função setupModalEventListeners