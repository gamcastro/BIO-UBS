// recepcao.js - lógica do módulo de recepção
(function(){
    const BASE_URL = window.BASE_URL || '';
    // Instância Axios para este módulo
    const api = axios.create({
        timeout: 10000,
        withCredentials: true // garante envio de cookies de sessão
    });
    api.interceptors.response.use(r => r, err => {
        console.error('[Axios erro]', err.response?.status, err.message);
        return Promise.reject(err);
    });

    function calcularTempoEspera(dataHora){
        const agora = new Date();
        const chegada = new Date(dataHora);
        const diffMin = Math.floor((agora - chegada)/60000);
        if (diffMin < 1) return 'agora';
        if (diffMin < 60) return diffMin + ' min';
        const h = Math.floor(diffMin/60);
        const m = diffMin % 60;
        return h + 'h' + (m>0 ? m + 'min' : '');
    }

    function preencherCard(response){
        $('#pacienteID').val(response.id);
        $('#pacienteNome').text(response.nome);
        $('#pacienteIdade').text(response.idade);
        $('#pacienteMae').text(response.nome_mae || 'Não informado');
        $('#pacienteCPF').text(response.cpf_formatado);
        $('#pacienteCNS').text(response.cns || 'Não informado');
        $('#queixaPrincipal').val('');
        $('#cardPaciente').fadeIn();
        if(tomSelectBusca){ tomSelectBusca.disable(); }
    }

    function mostrarNaoEncontrado(){
        $('#cardPaciente').hide();
        $('#cardNaoEncontrado').fadeIn();
        if(tomSelectBusca){ tomSelectBusca.disable(); }
    }

    function carregarFilaEspera(){
        api.get(BASE_URL + '/querys/filaEsperaTriagem.php')
           .then(r => {
               const response = r.data;
            console.debug('[FilaEspera] resposta recebida', response);
               if(response.status === 'success'){
                   const fila = response.fila;
                   $('#contadorFila').text(fila.length);
                   if(fila.length === 0){
                       $('#listaEspera').html('<div class="text-center text-muted py-5">\n<i class="bi bi-check-circle" style="font-size: 2rem;"></i>\n<p class="mt-2">Nenhum paciente aguardando triagem</p>\n</div>');
                   } else {
                       let html='';
                       fila.forEach(function(item,i){
                           const tempo = calcularTempoEspera(item.data_hora_chegada);
                           html += '<div class="list-group-item list-group-item-action">\n'
                               + '<div class="d-flex w-100 justify-content-between align-items-center">'
                               + '<div><span class="badge bg-secondary me-2">'+(i+1)+'º</span><strong>'+item.nome_paciente+'</strong></div>'
                               + '<small class="text-muted"><i class="bi bi-clock me-1"></i>'+tempo+'</small>'
                               + '</div>'
                               + (item.queixa_principal ? '<small class="text-muted d-block mt-1"><i class="bi bi-chat-left-dots me-1"></i>'+item.queixa_principal+'</small>' : '')
                               + '</div>';
                       });
                       $('#listaEspera').html(html);
                   }
                } else {
                    console.warn('[FilaEspera] status != success:', response.message);
                    $('#listaEspera').html('<div class="text-center text-muted py-5">\n<i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>\n<p class="mt-2">'+(response.message || 'Erro ao carregar a fila')+'</p>\n</div>');
               }
           })
           .catch((err)=>{
               console.error('Falha ao carregar fila (network/axios)', err);
               $('#listaEspera').html('<div class="text-center text-muted py-5">\n<i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>\n<p class="mt-2">Não foi possível carregar a fila.</p>\n</div>');
           });
    }

    let tomSelectBusca;

    $(document).ready(function(){
        tomSelectBusca = new TomSelect('#termo_busca', {
            valueField: 'id',
            labelField: 'label',
            searchField: ['nome','cpf','cns'],
            create: false,
            maxOptions: 15,
            persist: false,
            loadingClass: 'ts-loading',
            openOnFocus: true,
            shouldLoad: (query) => query && query.length >= 2,
            allowEmptyOption: false,
            items: [],
            render: {
                option: function(item, escape){
                    return '<div><strong>'+escape(item.nome)+'</strong><div class="text-muted small">'+escape(item.cpf)+(item.cns ? ' | CNS: '+escape(item.cns) : '')+'</div></div>';
                },
                item: function(item, escape){
                    if(!item || !item.nome){ return ''; }
                    return '<div>'+escape(item.nome)+' <span class="text-muted">('+escape(item.cpf)+')</span></div>';
                }
            },
            loadThrottle: 250,
            load: function(query, callback){
                if(!query || query.length < 2) return callback();
                api.post(BASE_URL + '/querys/sugestoesPacienteRecepcao.php', new URLSearchParams({ term: query }))
                   .then(r => {
                       const data = r.data;
                       callback(Array.isArray(data)? data : []);
                   })
                   .catch(()=> callback());
            }
        });

        tomSelectBusca.on('change', function(val){
            if(!val) return;
            api.post(BASE_URL + '/querys/buscaPacienteRecepcao.php', new URLSearchParams({ id_paciente: val }))
               .then(r => {
                   const response = r.data;
                   if(response.status === 'success'){ preencherCard(response); }
                   else { mostrarNaoEncontrado(); }
               })
               .catch(()=> mostrarNaoEncontrado());
        });

        tomSelectBusca.on('initialize', function(){
            const control = document.querySelector('#termo_busca');
            if(control){ control.setAttribute('placeholder','Digite Nome, CPF ou CNS'); }
            tomSelectBusca.clear();
        });

        $('#btnFecharCard').on('click', function(){
            $('#cardPaciente').fadeOut();
            tomSelectBusca.clear();
            $('#termo_busca').focus();
            tomSelectBusca.enable();
        });

        $('#btnFecharCardNaoEncontrado, #btnNovaConsulta').on('click', function(){
            $('#cardNaoEncontrado').fadeOut();
            tomSelectBusca.clear();
            $('#termo_busca').focus();
            tomSelectBusca.enable();
        });

        $('#btnCheckin').on('click', function(){
            const idPaciente = $('#pacienteID').val();
            const queixa = $('#queixaPrincipal').val().trim();
            if(!idPaciente){ alert('Erro: ID do paciente não encontrado.'); return; }
            if(!confirm('Confirma o check-in deste paciente?')){ return; }
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processando...');
            api.post(BASE_URL + '/actions/processar_checkin.php', new URLSearchParams({ id_paciente: idPaciente, queixa_principal: queixa }))
               .then(r => {
                   const response = r.data;
                   if(response.status === 'success'){
                       alert('✓ Check-in realizado com sucesso!\n\nPaciente enviado para a fila de triagem.');
                       $('#cardPaciente').fadeOut();
                       tomSelectBusca.clear();
                       carregarFilaEspera();
                   } else {
                       alert('Erro: ' + response.message);
                   }
               })
               .catch(()=> alert('Erro ao processar check-in. Tente novamente.'))
               .finally(()=> {
                   $btn.prop('disabled', false).html('<i class="bi bi-box-arrow-in-right me-2"></i>Confirmar Chegada - Enviar para Triagem');
               });
        });

        carregarFilaEspera();
        setInterval(carregarFilaEspera, 30000);

        $('#insertPaciente').on('show.bs.modal', function(){
            $('#origem_recepcao').val('1');
        });
    });
})();
