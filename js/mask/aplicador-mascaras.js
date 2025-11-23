/**
 * Aplicador de Máscaras para Formulários
 * Responsável por aplicar máscaras em campos que já possuem valores
 * e reaplica máscaras quando modais são exibidos via Bootstrap
 */

// Aplica máscara a um valor existente de acordo com o padrão (ex: '#####-###')
function aplicarMascaraValor(elemento, padrao, preservarCursor) {
    if (!elemento || !padrao) return;

    // Salva a posição do cursor antes de aplicar a máscara (apenas se preservarCursor = true)
    var cursorPos = preservarCursor ? (elemento.selectionStart || 0) : 0;
    var valorAnterior = elemento.value || '';
    var ehCarregamentoInicial = !preservarCursor;
    
    var digitos = valorAnterior.toString().replace(/\D/g, '');
    // Se nenhum dígito foi informado, não pré-preenche com os caracteres da máscara
    if (digitos.length === 0) {
        elemento.value = '';
        return;
    }
    var resultado = '';
    var idx = 0;
    for (var i = 0; i < padrao.length; i++) {
        var ch = padrao.charAt(i);
        if (ch === '#') {
            if (idx < digitos.length) {
                resultado += digitos.charAt(idx);
                idx++;
            } else {
                break; // Para no primeiro placeholder sem dígito
            }
        } else {
            // Só adiciona caractere fixo se já houver ao menos um dígito capturado
            // Porém preserva o parêntese de abertura "(" mesmo antes do primeiro dígito
            if (idx > 0) {
                resultado += ch;
            } else {
                if (ch === '(') {
                    resultado += ch;
                }
                // Caso não seja um parêntese de abertura, ignora o prefixo
                continue;
            }
        }
    }
    
    elemento.value = resultado;

    // Só restaura cursor se estiver editando (não no carregamento inicial)
    if (!ehCarregamentoInicial && preservarCursor) {
        // Calcula a nova posição do cursor
        // Conta quantos dígitos existem antes da posição do cursor original
        var digitosAntesDoCursor = valorAnterior.substring(0, cursorPos).replace(/\D/g, '').length;
        
        // Encontra a posição correspondente no novo valor formatado
        var novaPosicao = 0;
        var digitosContados = 0;
        for (var i = 0; i < resultado.length; i++) {
            if (/\d/.test(resultado.charAt(i))) {
                digitosContados++;
                // Caso especial: se não há dígitos antes do cursor (posição 0), mantém em 0
                if (digitosAntesDoCursor === 0) {
                    novaPosicao = 0;
                    break;
                }
                if (digitosContados >= digitosAntesDoCursor) {
                    novaPosicao = i + 1;
                    break;
                }
            }
        }
        
        // Se o cursor estava no final ou após todos os dígitos, coloca no final
        if (digitosAntesDoCursor === 0) {
            novaPosicao = 0; // garante início absoluto
        } else if (digitosContados < digitosAntesDoCursor || cursorPos >= valorAnterior.length) {
            novaPosicao = resultado.length;
        }
        
        // Restaura a posição do cursor
        try {
            elemento.setSelectionRange(novaPosicao, novaPosicao);
        } catch(e) {
            // Em caso de erro, ignora (alguns navegadores não suportam)
        }
    }
}

// Procura elementos que usam a função 'mascaras' no atributo onkeypress e aplica a formatação
function aplicarMascaraNosCampos(container) {
    container = container || document;
    // Suporta atributos inline antigos (onkeypress contendo mascaras(...))
    var nodesInline = container.querySelectorAll('[onkeypress*="mascaras("]');
    nodesInline.forEach(function(el) {
        var attr = el.getAttribute('onkeypress') || '';
        var m = attr.match(/mascaras\s*\(\s*event\s*,\s*this\s*,\s*'([^']+)'\s*\)/);
        if (m && m[1]) {
            aplicarMascaraValor(el, m[1], false);
            try {
                if (!el.dataset.maskAttached) {
                    el.addEventListener('input', function() { aplicarMascaraValor(el, m[1], true); });
                    // adiciona listener keypress que replica comportamento da função mascaras
                    if (typeof window.mascaras === 'function') {
                        el.addEventListener('keypress', function(evt) { return window.mascaras(evt, el, m[1]); });
                    }
                    el.dataset.maskAttached = '1';
                }
            } catch (e) { /* silent */ }
        }
    });

    // Suporta novo atributo data-mask (recomendado)
    var nodesData = container.querySelectorAll('[data-mask]');
    nodesData.forEach(function(el) {
        var padrao = el.getAttribute('data-mask');
        if (padrao) {
            aplicarMascaraValor(el, padrao, false);
            try {
                if (!el.dataset.maskAttached) {
                    el.addEventListener('input', function() { aplicarMascaraValor(el, padrao, true); });
                    if (typeof window.mascaras === 'function') {
                        el.addEventListener('keypress', function(evt) { return window.mascaras(evt, el, padrao); });
                    }
                    el.dataset.maskAttached = '1';
                }
            } catch (e) { /* silent */ }
        }
    });
}

// Aplica no carregamento da página (para páginas que já têm campos preenchidos)
document.addEventListener('DOMContentLoaded', function() {
    try { 
        aplicarMascaraNosCampos(document); 
    } catch(e) { 
        console.warn('Erro ao aplicar máscaras no carregamento:', e); 
    }
});

// Quando um modal for exibido via Bootstrap, reaplica as máscaras dentro do modal
document.addEventListener('shown.bs.modal', function(e) {
    try { 
        aplicarMascaraNosCampos(e.target); 
    } catch(err) { 
        console.warn('Erro ao aplicar máscaras no modal:', err); 
    }
});

// Para compatibilidade com conteúdo carregado via AJAX
// Esta função pode ser chamada manualmente após carregamento de conteúdo dinâmico
window.reaplicarMascaras = function(container) {
    try {
        aplicarMascaraNosCampos(container);
    } catch(err) {
        console.warn('Erro ao reaplicar máscaras:', err);
    }
};