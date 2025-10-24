$(document).ready(function(){

    $('#acolhimentoBioUBS').on('loaded.bs.modal', function () {
        
        var btnBuscar = $(this).find('#btnBuscar');
        var inputBusca = $(this).find('#buscaPaciente');
        var btnEncaminhar = $(this).find('#btnEncaminhar');
        
        // --- BÔNUS: Desabilita o botão de encaminhar se o usuário digitar um novo CPF ---
        inputBusca.on('input', function() {
            btnEncaminhar.prop('disabled', true);
            $('#dadosPaciente').slideUp();
        });

        btnBuscar.on('click', function() {
            var termoBusca = inputBusca.val().replace(/\D/g, '');

            if (termoBusca.length > 0) {
                var spanNomePaciente = $('#nomePaciente span');
                spanNomePaciente.text('Buscando...');
                $('#dadosPaciente').slideDown();

                $.post('querys/buscaPaciente.php', { termo_busca: termoBusca }, function(data) {
                    if (data.status === 'success') {
                        spanNomePaciente.text(data.nome);
                        $('#nascimentoPaciente').text(data.data_nascimento);
                        $('#pacienteId').val(data.id);
                        
                        // A MÁGICA ACONTECE AQUI: Habilita o botão de encaminhar
                        btnEncaminhar.prop('disabled', false);
                        
                    } else {
                        $('#dadosPaciente').slideUp();
                        btnEncaminhar.prop('disabled', true); // Garante que continue desabilitado em caso de erro
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