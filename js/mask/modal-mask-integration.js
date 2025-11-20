/**
 * Integração de Máscaras com Sistema de Modais AJAX
 * Este arquivo garante que as máscaras sejam aplicadas em conteúdo carregado dinamicamente
 */

(function() {
    'use strict';

    /**
     * Observa mudanças no DOM para aplicar máscaras em elementos adicionados dinamicamente
     */
    function initMaskObserver() {
        if (window.MutationObserver) {
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === Node.ELEMENT_NODE) {
                                // Se existe a função global de reaplicar máscaras, usa ela
                                if (window.reaplicarMascaras) {
                                    setTimeout(function() {
                                        window.reaplicarMascaras(node);
                                    }, 100);
                                }
                            }
                        });
                    }
                });
            });

            // Observa mudanças em todo o documento
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }

    /**
     * Hook personalizado para quando conteúdo AJAX é carregado
     */
    function setupAjaxHooks() {
        // Override do axios para interceptar resposta e reaplicar máscaras
        if (window.axios && window.axios.interceptors) {
            window.axios.interceptors.response.use(function (response) {
                // Se a resposta contém HTML e há uma função de reaplicar máscaras
                if (response.data && typeof response.data === 'string' && 
                    response.data.includes('<') && window.reaplicarMascaras) {
                    // Aguarda um pouco para o DOM ser atualizado antes de reaplicar
                    setTimeout(function() {
                        if (window.reaplicarMascaras) {
                            window.reaplicarMascaras(document);
                        }
                    }, 200);
                }
                return response;
            }, function (error) {
                return Promise.reject(error);
            });
        }
    }

    /**
     * Evento específico para quando modais são mostrados
     */
    function setupModalMaskHandlers() {
        document.addEventListener('shown.bs.modal', function(event) {
            var modal = event.target;
            if (modal && window.reaplicarMascaras) {
                // Aguarda um pouco para garantir que o conteúdo foi carregado
                setTimeout(function() {
                    window.reaplicarMascaras(modal);
                }, 150);
            }
        });

        // Também intercepta quando o conteúdo do modal é alterado
        document.addEventListener('DOMContentLoaded', function() {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                // Observa mudanças especificamente no conteúdo do modal
                if (window.MutationObserver) {
                    var modalObserver = new MutationObserver(function(mutations) {
                        var hasContentChanges = false;
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'childList' || 
                                (mutation.type === 'attributes' && mutation.attributeName === 'class')) {
                                hasContentChanges = true;
                            }
                        });

                        if (hasContentChanges && window.reaplicarMascaras) {
                            setTimeout(function() {
                                window.reaplicarMascaras(modal);
                            }, 100);
                        }
                    });

                    var modalContent = modal.querySelector('.modal-content');
                    if (modalContent) {
                        modalObserver.observe(modalContent, {
                            childList: true,
                            subtree: true,
                            attributes: true,
                            attributeFilter: ['class', 'style']
                        });
                    }
                }
            });
        });
    }

    /**
     * Função de inicialização principal
     */
    function init() {
        // Aguarda o DOM estar pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initMaskObserver();
                setupAjaxHooks();
                setupModalMaskHandlers();
            });
        } else {
            // DOM já está pronto
            initMaskObserver();
            setupAjaxHooks();
            setupModalMaskHandlers();
        }
    }

    // Inicia o sistema
    init();

    // Função global de utilidade para forçar reaplicação de máscaras
    window.forceReapplyMasks = function(container) {
        if (window.reaplicarMascaras) {
            window.reaplicarMascaras(container || document);
        }
    };

})();