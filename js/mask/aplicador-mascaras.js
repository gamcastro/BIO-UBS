/**
 * Aplicador de Máscaras para Formulários
 * Responsável por aplicar máscaras em campos que já possuem valores
 * e reaplica máscaras quando modais são exibidos via Bootstrap
 */

// Aplica máscara a um valor existente de acordo com o padrão (ex: '#####-###')
function aplicarMascaraValor(elemento, padrao) {
    if (!elemento || !padrao) return;
    var digitos = (elemento.value || '').toString().replace(/\D/g, '');
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
}

// Procura elementos que usam a função 'mascaras' no atributo onkeypress e aplica a formatação
function aplicarMascaraNosCampos(container) {
    container = container || document;
    var nodes = container.querySelectorAll('[onkeypress*="mascaras("]');
    nodes.forEach(function(el) {
        var attr = el.getAttribute('onkeypress') || '';
        var m = attr.match(/mascaras\s*\(\s*event\s*,\s*this\s*,\s*'([^']+)'\s*\)/);
        if (m && m[1]) {
            aplicarMascaraValor(el, m[1]);
            // Anexa listener de input para reformatar quando usuário substituir todo o conteúdo
            try {
                if (!el.dataset.maskAttached) {
                    el.addEventListener('input', function() {
                        aplicarMascaraValor(el, m[1]);
                    });
                    el.dataset.maskAttached = '1';
                }
            } catch (e) {
                // falha silenciosa
            }
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