$(document).ready(function() {
    $('#tableBioUBS').DataTable({
        // DOM mais simples: B=Buttons, l=Length, f=Filter, r=Processing, t=Table, i=Info, p=Paginate
        // 'Blfrtip' coloca os botões no topo, junto com o seletor de quantidade e a busca.
        dom: 'Blfrtip', 
        
        // Define apenas os tipos de botões desejados (Excel e Imprimir, como no seu exemplo)
        buttons: [
            'excel', 
            'print' 
            // Se quiser outros, adicione aqui: 'copy', 'csv', 'pdf'
        ],
        
        language: { // Manter a tradução é sempre bom
            search: "_INPUT_",
            searchPlaceholder: "Buscar...",
            lengthMenu: "Mostrar _MENU_ registros",
            zeroRecords: "Nenhum registro encontrado",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros)",
            paginate: {
                first: "<<",
                last: ">>",
                next: ">",
                previous: "<"
            },
            buttons: { // Textos padrão dos botões (serão traduzidos se disponíveis)
                copy: "Copiar",
                csv: "CSV",
                excel: "Excel",
                pdf: "PDF",
                print: "Imprimir",
                copyTitle: 'Copiado',
                copySuccess: {
                    _: '%d linhas copiadas',
                    1: '1 linha copiada'
                }
            }
        }
    });
});