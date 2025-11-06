/**
 * Atualiza o relógio na sidebar
 */
function updateLiveDateTime() {
    // Encontra os elementos
    const elSidebar = document.getElementById('live-datetime-sidebar');
    const elTop = document.getElementById('live-datetime-top');
    // Calcula data/hora uma vez e atualiza onde houver elemento
    const now = new Date();
    // Formata a data (ex: Terça, 04/11/2025)
    const optionsDate = { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric' };
    const dateStr = new Intl.DateTimeFormat('pt-BR', optionsDate).format(now);
    // Formata a hora (ex: 11:30:05)
    const timeStr = now.toLocaleTimeString('pt-BR');
    // Capitaliza o dia da semana
    const finalStr = dateStr.charAt(0).toUpperCase() + dateStr.slice(1);

    if (elSidebar) {
        elSidebar.innerHTML = `${finalStr} | ${timeStr}`;
    }

    if (elTop) {
        elTop.innerHTML = `${finalStr} | ${timeStr}`;
    }
}

// Atualiza o relógio a cada segundo
setInterval(updateLiveDateTime, 1000);

// Chama imediatamente ao carregar a página
document.addEventListener('DOMContentLoaded', updateLiveDateTime);
