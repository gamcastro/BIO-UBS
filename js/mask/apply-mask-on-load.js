/**
 * Aplica máscara de telefone nos campos que já vêm preenchidos
 * ao carregar a página/modal
 */
document.addEventListener('DOMContentLoaded', function() {
    applyPhoneMasks();
});

// Para modais carregados via AJAX, chame manualmente applyPhoneMasks() após carregar o conteúdo
function applyPhoneMasks() {
    // Telefone residencial (10 dígitos): (XX)XXXX-XXXX
    const telResidencial = document.querySelector('input[name="telefone_residencial"]');
    if (telResidencial && telResidencial.value) {
        telResidencial.value = formatPhone(telResidencial.value, 10);
    }
    
    // Telefone celular (11 dígitos): (XX)XXXXX-XXXX
    const telCelular = document.querySelector('input[name="telefone_celular"]');
    if (telCelular && telCelular.value) {
        telCelular.value = formatPhone(telCelular.value, 11);
    }
    
    // Telefone contato (11 dígitos): (XX)XXXXX-XXXX
    const telContato = document.querySelector('input[name="telefone_contato"]');
    if (telContato && telContato.value) {
        telContato.value = formatPhone(telContato.value, 11);
    }
}

function formatPhone(value, expectedLength) {
    // Remove tudo que não é número
    const digits = value.replace(/\D/g, '');
    
    if (digits.length === 10) {
        // (XX)XXXX-XXXX
        return digits.replace(/(\d{2})(\d{4})(\d{4})/, '($1)$2-$3');
    } else if (digits.length === 11) {
        // (XX)XXXXX-XXXX
        return digits.replace(/(\d{2})(\d{5})(\d{4})/, '($1)$2-$3');
    }
    
    return value; // Retorna original se não tiver tamanho esperado
}
