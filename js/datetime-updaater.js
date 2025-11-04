/**
 * Atualiza o relógio na sidebar
 */
function updateLiveDateTime() {
    // Encontra os elementos
    const elSidebar = document.getElementById('live-datetime-sidebar');
    
    // Se o elemento da sidebar existir, atualiza
    if (elSidebar) {
        const now = new Date();
        
        // Formata a data (ex: Terça, 04/11/2025)
        const optionsDate = { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric' };
        // Usando 'pt-BR' para garantir o formato em português
        const dateStr = new Intl.DateTimeFormat('pt-BR', optionsDate).format(now);
        
        // Formata a hora (ex: 11:30:05)
        const timeStr = now.toLocaleTimeString('pt-BR');
        
        // Coloca no HTML (Capitaliza o dia da semana)
        const finalStr = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);
        elSidebar.innerHTML = `${finalStr} | ${timeStr}`;
    }
}

// Atualiza o relógio a cada segundo
setInterval(updateLiveDateTime, 1000);

// Chama imediatamente ao carregar a página
document.addEventListener('DOMContentLoaded', updateLiveDateTime);
