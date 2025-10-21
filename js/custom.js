$(document).ready(function(){

    // O Bootstrap 3 dispara este evento QUANDO o conteúdo remoto TERMINA de ser carregado no modal.
    // É essencial usar 'loaded.bs.modal' para garantir que os botões e campos já existam na página.
    $('#acolhimentoBioUBS').on('loaded.bs.modal', function () {
        
        // --- Início da lógica que funciona DENTRO do modal carregado ---
        
        // Encontra o botão de busca pelo ID (#btnBuscar) DENTRO do modal atual ('this').
        var btnBuscar = $(this).find('#btnBuscar');
        
        // Adiciona a função que será executada quando o botão for clicado.
        btnBuscar.on('click', function() {

            // Pega o valor digitado no campo de busca (CPF).
            var termoBusca = $('#buscaPaciente').val().replace(/\D/g, '');

            // Validação simples para não fazer uma busca vazia.
            if (termoBusca.length > 0) {
                
             var spanNomePaciente = $('#nomePaciente span');

                // Exibe a mensagem de carregamento.
                spanNomePaciente.text('Buscando...');
                $('#dadosPaciente').slideDown();

                $.post('querys/buscaPaciente.php', { termo_busca: termoBusca }, function(data) {
                    
                    if (data.status === 'success') {
                        // MUDANÇA 2: Preenche o span e os outros campos.
                        spanNomePaciente.text(data.nome);
                        $('#nascimentoPaciente').text(data.data_nascimento);
                        $('#pacienteId').val(data.id);
                        
                    } else {
                        $('#dadosPaciente').slideUp();
                        alert(data.message);
                    }
                });

            } else {
                alert('Por favor, digite um CPF para realizar a busca.');
            }
        });
    });

    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

});