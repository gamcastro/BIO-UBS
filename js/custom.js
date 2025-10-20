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
                
                // Exibe uma mensagem de carregamento para o usuário.
                $('#dadosPaciente').slideUp(function(){
                    $(this).find('#nomePaciente span').text('Buscando...');
                    $(this).slideDown();
                });

                // Inicia a requisição AJAX usando o método $.post do jQuery.
                // Parâmetro 1: A URL do nosso serviço de busca.
                // Parâmetro 2: Os dados que estamos enviando ({ chave: valor }).
                // Parâmetro 3: A função que será executada quando o servidor responder.
                $.post('querys/buscaPaciente.php', { termo_busca: termoBusca }, function(data) {
                    
                    // 'data' é a resposta JSON que o PHP enviou.
                    if (data.status === 'success') {
                        // Se a busca foi bem-sucedida...
                        
                        // Preenche os campos na tela com os dados retornados.
                        $('#nomePaciente span').text(data.nome);
                        $('#nascimentoPaciente').text(data.data_nascimento);
                        $('#pacienteId').val(data.id); // Preenche o campo oculto com o ID.
                        
                        // Mostra a div com os dados do paciente.
                        $('#dadosPaciente').slideDown(); 
                    } else {
                        // Se o PHP retornou um status de erro...
                        
                        // Esconde a div de resultados e mostra um alerta para o usuário.
                        $('#dadosPaciente').slideUp();
                        alert(data.message); // Exibe a mensagem "Paciente não encontrado."
                    }
                });

            } else {
                alert('Por favor, digite um CPF para realizar a busca.');
            }
        });

        // --- Fim da lógica do modal ---
    });

    // Boa prática: Limpa o conteúdo do modal quando ele é fechado.
    // Isso garante que, ao abri-lo novamente, ele carregue uma versão "nova".
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

});