(function () {
    // Configuración global
    const LOADER_ID = 'custom-loader';
    const NO_SCROLL_CLASS = 'no-scroll';
    const TRANSITION_DURATION = 500;

    // Crear loader si no existe
    function createLoader() {
        if (document.getElementById(LOADER_ID)) return;

        const loader = document.createElement('div');
        loader.id = LOADER_ID;
        loader.innerHTML = `
            <div class="loader">
                <div>
                    <ul>
                        <li><svg fill="currentColor" viewBox="0 0 90 120"><path d="M90,0 L90,120 L11,120 C4.92486775,120 0,115.075132 0,109 L0,11 C0,4.92486775 4.92486775,0 11,0 L90,0 Z M71.5,81 L18.5,81 C17.1192881,81 16,82.1192881 16,83.5 C16,84.8254834 17.0315359,85.9100387 18.3356243,85.9946823 L18.5,86 L71.5,86 C72.8807119,86 74,84.8807119 74,83.5 C74,82.1745166 72.9684641,81.0899613 71.6643757,81.0053177 L71.5,81 Z M71.5,57 L18.5,57 C17.1192881,57 16,58.1192881 16,59.5 C16,60.8254834 17.0315359,61.9100387 18.3356243,61.9946823 L18.5,62 L71.5,62 C72.8807119,62 74,60.8807119 74,59.5 C74,58.1192881 72.8807119,57 71.5,57 Z M71.5,33 L18.5,33 C17.1192881,33 16,34.1192881 16,35.5 C16,36.8254834 17.0315359,37.9100387 18.3356243,37.9946823 L18.5,38 L71.5,38 C72.8807119,38 74,36.8807119 74,35.5 C74,34.1192881 72.8807119,33 71.5,33 Z"></path></svg></li>
                        <li><svg fill="currentColor" viewBox="0 0 90 120"><path d="M90,0 L90,120 L11,120 C4.92486775,120 0,115.075132 0,109 L0,11 C0,4.92486775 4.92486775,0 11,0 L90,0 Z M71.5,81 L18.5,81 C17.1192881,81 16,82.1192881 16,83.5 C16,84.8254834 17.0315359,85.9100387 18.3356243,85.9946823 L18.5,86 L71.5,86 C72.8807119,86 74,84.8807119 74,83.5 C74,82.1745166 72.9684641,81.0899613 71.6643757,81.0053177 L71.5,81 Z M71.5,57 L18.5,57 C17.1192881,57 16,58.1192881 16,59.5 C16,60.8254834 17.0315359,61.9100387 18.3356243,61.9946823 L18.5,62 L71.5,62 C72.8807119,62 74,60.8807119 74,59.5 C74,58.1192881 72.8807119,57 71.5,57 Z M71.5,33 L18.5,33 C17.1192881,33 16,34.1192881 16,35.5 C16,36.8254834 17.0315359,37.9100387 18.3356243,37.9946823 L18.5,38 L71.5,38 C72.8807119,38 74,36.8807119 74,35.5 C74,34.1192881 72.8807119,33 71.5,33 Z"></path></svg></li>
                        <li><svg fill="currentColor" viewBox="0 0 90 120"><path d="M90,0 L90,120 L11,120 C4.92486775,120 0,115.075132 0,109 L0,11 C0,4.92486775 4.92486775,0 11,0 L90,0 Z M71.5,81 L18.5,81 C17.1192881,81 16,82.1192881 16,83.5 C16,84.8254834 17.0315359,85.9100387 18.3356243,85.9946823 L18.5,86 L71.5,86 C72.8807119,86 74,84.8807119 74,83.5 C74,82.1745166 72.9684641,81.0899613 71.6643757,81.0053177 L71.5,81 Z M71.5,57 L18.5,57 C17.1192881,57 16,58.1192881 16,59.5 C16,60.8254834 17.0315359,61.9100387 18.3356243,61.9946823 L18.5,62 L71.5,62 C72.8807119,62 74,60.8807119 74,59.5 C74,58.1192881 72.8807119,57 71.5,57 Z M71.5,33 L18.5,33 C17.1192881,33 16,34.1192881 16,35.5 C16,36.8254834 17.0315359,37.9100387 18.3356243,37.9946823 L18.5,38 L71.5,38 C72.8807119,38 74,36.8807119 74,35.5 C74,34.1192881 72.8807119,33 71.5,33 Z"></path></svg></li>
                    </ul>
                </div>
                <span>Loading</span>
            </div>
        `;
        document.body.appendChild(loader);
    }

    // Mostrar loader con animación
    function showLoader() {
        const loader = document.getElementById(LOADER_ID);
        if (!loader) return;

        loader.style.display = 'flex';
        loader.offsetHeight; // Forzar reflow para animación
        loader.style.opacity = '1';
        document.body.classList.add(NO_SCROLL_CLASS);
    }

    // Ocultar loader con animación
    function hideLoader() {
        const loader = document.getElementById(LOADER_ID);
        if (!loader) return;

        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
        }, TRANSITION_DURATION);

        document.body.classList.remove(NO_SCROLL_CLASS);
    }

    // Inicializar loader al cargar DOM
    function initLoaderOnLoad() {
        if (document.readyState === 'complete') {
            hideLoader();
        } else {
            window.addEventListener('load', () => {
                hideLoader();
            });
        }
    }

    // Manejar navegaciones internas
    function handleNavigationClicks() {
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');

            if (
                link &&
                !link.target &&
                !link.href.startsWith('javascript:') &&
                !link.href.startsWith('mailto:') &&
                !link.href.startsWith('tel:') &&
                !link.dataset.noload &&
                link.hostname === window.location.hostname &&
                !link.href.includes('#')
            ) {
                e.preventDefault();
                showLoader();

                setTimeout(() => {
                    window.location.href = link.href;
                }, 50);
            }
        });
    }

    // Manejar navegación mediante botones de navegador
    function handlePageShowEvents() {
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                hideLoader();
            }
        });
    }

    // Ejecución principal
    createLoader();
    showLoader(); // Mostrar inmediatamente
    initLoaderOnLoad();
    handleNavigationClicks();
    handlePageShowEvents();
})();