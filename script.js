/**
 * RekkieMasajes — script.js
 * Vanilla JS puro. Sin dependencias externas.
 * Módulos: Navbar scroll, Menú móvil, Bot orientador, Validación formulario.
 */

'use strict';

/* ============================================================
   UTILIDADES
============================================================ */
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

/* ============================================================
   1. NAVBAR — scroll effect + menú móvil
============================================================ */
(function initNav() {
    const nav     = $('#nav');
    const burger  = $('#burger');
    const mobile  = $('#nav-mobile');
    if (!nav || !burger || !mobile) return;

    // Scroll: añadir clase para sombra/borde
    const onScroll = () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // Burger toggle
    burger.addEventListener('click', () => {
        const isOpen = burger.getAttribute('aria-expanded') === 'true';
        burger.setAttribute('aria-expanded', String(!isOpen));
        mobile.hidden = isOpen;
    });

    // Cerrar menú móvil al hacer click en un link
    $$('a', mobile).forEach(link => {
        link.addEventListener('click', () => {
            burger.setAttribute('aria-expanded', 'false');
            mobile.hidden = true;
        });
    });

    // Cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !mobile.hidden) {
            burger.setAttribute('aria-expanded', 'false');
            mobile.hidden = true;
            burger.focus();
        }
    });
})();


/* ============================================================
   2. PREFILL SERVICIO — desde botones de cards
============================================================ */
/**
 * Función global llamada desde onclick en los botones de cada card.
 * Pre-selecciona el servicio en el select del formulario.
 */
window.prefillServicio = function(valor) {
    const select = $('#servicio');
    if (!select) return;
    // Buscar opción que coincida
    Array.from(select.options).forEach(opt => {
        if (opt.value === valor) {
            select.value = valor;
        }
    });
};


/* ============================================================
   3. BOT ORIENTADOR — árbol de decisiones puro
============================================================ */
(function initBot() {
    const widget      = $('#bot-widget');
    if (!widget) return;

    // Estado
    const respuestas  = {};
    let pasoActual    = 1;
    const totalPasos  = 3;

    // Elementos
    const steps       = $$('.bot-step', widget);
    const result      = $('#bot-result');
    const progress    = $('#bot-progress');
    const progressBar = $('#bot-progress-fill');
    const progressLbl = $('#bot-progress-label');
    const btnReset    = $('#bot-reset');

    // Opciones de resultado según respuestas
    const RECOMENDACIONES = {
        // ÁRBOL: zona → frecuencia → experiencia
        // Formato: "[zona]-[frecuencia]-[experiencia]" → recomendación
        // Si no hay match exacto, fallback por zona

        dolor_cronica_primera:    'descontracturante',
        dolor_cronica_esporadico: 'descontracturante',
        dolor_cronica_regular:    'descontracturante',
        dolor_reciente_primera:   'relajacion',        // primera vez: empezar suave
        dolor_reciente_esporadico:'descontracturante',
        dolor_reciente_regular:   'descontracturante',
        dolor_prevencion_primera: 'relajacion',
        dolor_prevencion_esporadico:'relajacion',
        dolor_prevencion_regular: 'descontracturante',

        estres_cronica_primera:   'relajacion',
        estres_cronica_esporadico:'relajacion',
        estres_cronica_regular:   'relajacion',
        estres_reciente_primera:  'relajacion',
        estres_reciente_esporadico:'relajacion',
        estres_reciente_regular:  'relajacion',
        estres_prevencion_primera:'relajacion',
        estres_prevencion_esporadico:'relajacion',
        estres_prevencion_regular:'relajacion',

        figura_cronica_primera:   'reductivo',
        figura_cronica_esporadico:'reductivo',
        figura_cronica_regular:   'reductivo',
        figura_reciente_primera:  'reductivo',
        figura_reciente_esporadico:'reductivo',
        figura_reciente_regular:  'reductivo',
        figura_prevencion_primera:'reductivo',
        figura_prevencion_esporadico:'reductivo',
        figura_prevencion_regular:'reductivo',

        liquidos_cronica_primera:    'drenaje',
        liquidos_cronica_esporadico: 'drenaje',
        liquidos_cronica_regular:    'drenaje',
        liquidos_reciente_primera:   'drenaje',
        liquidos_reciente_esporadico:'drenaje',
        liquidos_reciente_regular:   'drenaje',
        liquidos_prevencion_primera: 'drenaje',
        liquidos_prevencion_esporadico:'drenaje',
        liquidos_prevencion_regular: 'drenaje',
    };

    const INFO_MASAJES = {
        relajacion: {
            nombre: 'Masaje de Relajación',
            desc:   'Perfecto para ti. Movimientos suaves y continuos que calman el sistema nervioso, reducen el cortisol y devuelven la calma. Ideal como punto de partida o como ritual regular de bienestar.',
            precio: '$30.000 / 60 min',
            url:    '#reservar',
            param:  'Masaje de Relajación ($30.000)'
        },
        descontracturante: {
            nombre: 'Masaje Descontracturante',
            desc:   'Exactamente lo que necesitas. Trabajamos con presión profunda sobre los grupos musculares contracturados, liberando los nudos y aliviando el dolor en cuello, hombros y zona lumbar.',
            precio: '$40.000 / 60 min',
            url:    '#reservar',
            param:  'Masaje Descontracturante ($40.000)'
        },
        drenaje: {
            nombre: 'Drenaje Linfático',
            desc:   'La terapia ideal para tu situación. Mediante movimientos suaves y rítmicos activamos el sistema linfático para eliminar toxinas, reducir la hinchazón y mejorar la circulación.',
            precio: '$50.000 / 75 min',
            url:    '#reservar',
            param:  'Drenaje Linfático ($50.000)'
        },
        reductivo: {
            nombre: 'Masaje Reductivo',
            desc:   'La terapia más efectiva para tu objetivo. Técnica de alta intensidad combinada con aceites especiales para modelar la figura, romper tejido adiposo y mejorar tonicidad. Resultados progresivos y reales.',
            precio: '$60.000 / 75 min',
            url:    '#reservar',
            param:  'Masaje Reductivo ($60.000)'
        }
    };

    function calcularRecomendacion() {
        const key = `${respuestas.zona}_${respuestas.frecuencia}_${respuestas.experiencia}`;
        // Match exacto o fallback por zona
        const masajeKey = RECOMENDACIONES[key] || fallbackPorZona(respuestas.zona);
        return INFO_MASAJES[masajeKey] || INFO_MASAJES.relajacion;
    }

    function fallbackPorZona(zona) {
        const mapa = {
            dolor:    'descontracturante',
            estres:   'relajacion',
            figura:   'reductivo',
            liquidos: 'drenaje'
        };
        return mapa[zona] || 'relajacion';
    }

    function irAPaso(num) {
        // Ocultar paso actual
        steps.forEach(s => {
            s.classList.remove('active');
            s.setAttribute('aria-hidden', 'true');
        });
        result.setAttribute('aria-hidden', 'true');
        result.style.display = 'none';

        if (num > totalPasos) {
            // Mostrar resultado
            mostrarResultado();
            return;
        }

        const paso = $(`#bot-step-${num}`, widget);
        if (paso) {
            paso.classList.add('active');
            paso.setAttribute('aria-hidden', 'false');
            pasoActual = num;
            actualizarProgreso(num);
        }
    }

    function actualizarProgreso(paso) {
        const pct = Math.round((paso / totalPasos) * 100);
        progressBar.style.width = `${pct}%`;
        progressLbl.textContent = `Paso ${paso} de ${totalPasos}`;
        progress.removeAttribute('aria-hidden');
    }

    function mostrarResultado() {
        const rec = calcularRecomendacion();

        // Llenar resultado
        $('#bot-result-title').textContent = rec.nombre;
        $('#bot-result-desc').textContent  = rec.desc;
        $('#bot-result-price').textContent = rec.precio;

        const cta = $('#bot-result-cta');
        cta.href = rec.url;
        cta.addEventListener('click', () => {
            prefillServicio(rec.param);
        });

        result.removeAttribute('aria-hidden');
        result.style.display = 'block';

        // Ocultar barra de progreso
        progress.setAttribute('aria-hidden', 'true');

        // Scroll suave al resultado
        setTimeout(() => {
            result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    }

    // Event listeners en opciones del bot
    $$('.bot-option', widget).forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.dataset.key;
            const val = btn.dataset.val;
            const step = parseInt(btn.dataset.step, 10);

            respuestas[key] = val;

            // Feedback visual: seleccionado
            const hermanos = $$('.bot-option', btn.closest('.bot-step'));
            hermanos.forEach(h => h.classList.remove('selected'));
            btn.classList.add('selected');

            // Pequeño delay para ver la selección
            setTimeout(() => irAPaso(step + 1), 250);
        });
    });

    // Reset bot
    btnReset && btnReset.addEventListener('click', () => {
        Object.keys(respuestas).forEach(k => delete respuestas[k]);
        pasoActual = 1;
        $$('.bot-option', widget).forEach(b => b.classList.remove('selected'));
        result.setAttribute('aria-hidden', 'true');
        result.style.display = 'none';
        irAPaso(1);
    });

    // Añadir estilos del estado seleccionado dinámicamente
    const styleEl = document.createElement('style');
    styleEl.textContent = `
        .bot-option.selected {
            border-color: var(--sage);
            background: rgba(107,144,128,.12);
            color: var(--sage-dark);
            font-weight: 500;
        }
    `;
    document.head.appendChild(styleEl);

    // Inicializar
    irAPaso(1);
})();


/* ============================================================
   4. VALIDACIÓN FORMULARIO RESERVA
============================================================ */
(function initForm() {
    const form = $('#reserva-form');
    if (!form) return;

    const reglas = {
        nombre:   { min: 2, msg: 'Ingresa tu nombre (mínimo 2 caracteres)' },
        telefono: { pattern: /^(\+?56\s?)?0?9\s?[9876543]\d{7}$|^\d{7,15}$/, msg: 'Ingresa un teléfono válido (ej: +569 8902 4643)' },
        email:    { pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, msg: 'Ingresa un email válido', optional: true },
        servicio: { msg: 'Selecciona un servicio' },
        fecha:    { msg: 'Selecciona una fecha' },
        hora:     { msg: 'Selecciona un horario' },
        captcha:  { msg: 'Ingresa el resultado de la operación' }
    };

    function mostrarError(campo, msg) {
        const err = $(`#err-${campo}`);
        const input = $(`#${campo}`, form);
        if (err) err.textContent = msg;
        if (input) {
            input.classList.add('input--error');
            input.setAttribute('aria-invalid', 'true');
        }
    }

    function limpiarError(campo) {
        const err = $(`#err-${campo}`);
        const input = $(`#${campo}`, form);
        if (err) err.textContent = '';
        if (input) {
            input.classList.remove('input--error');
            input.removeAttribute('aria-invalid');
        }
    }

    function validarCampo(campo) {
        const regla = reglas[campo];
        if (!regla) return true;

        const input = $(`#${campo}`, form);
        if (!input) return true;
        const valor = input.value.trim();

        if (regla.optional && valor === '') {
            limpiarError(campo);
            return true;
        }

        if (!regla.optional && valor === '') {
            mostrarError(campo, regla.msg || 'Este campo es requerido');
            return false;
        }

        if (regla.min && valor.length < regla.min) {
            mostrarError(campo, regla.msg);
            return false;
        }

        if (regla.pattern && !regla.pattern.test(valor)) {
            mostrarError(campo, regla.msg);
            return false;
        }

        limpiarError(campo);
        return true;
    }

    // Validación on blur para cada campo
    Object.keys(reglas).forEach(campo => {
        const input = $(`#${campo}`, form);
        if (!input) return;
        input.addEventListener('blur', () => validarCampo(campo));
        input.addEventListener('input', () => {
            if (input.classList.contains('input--error')) validarCampo(campo);
        });
    });

    // Validación fecha: no puede ser pasada
    const campoFecha = $('#fecha', form);
    if (campoFecha) {
        campoFecha.addEventListener('change', () => {
            const hoy = new Date();
            hoy.setHours(0,0,0,0);
            const elegida = new Date(campoFecha.value + 'T00:00:00');
            if (elegida <= hoy) {
                mostrarError('fecha', 'La fecha debe ser a partir de mañana');
            } else {
                limpiarError('fecha');
            }
        });
    }

    // Submit
    form.addEventListener('submit', (e) => {
        let valido = true;
        Object.keys(reglas).forEach(campo => {
            if (!validarCampo(campo)) valido = false;
        });

        if (!valido) {
            e.preventDefault();
            // Scroll al primer error
            const primerError = form.querySelector('.input--error');
            if (primerError) {
                primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                primerError.focus();
            }
            return;
        }

        // Deshabilitar botón para evitar doble envío
        const btn = $('#btn-submit', form);
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Enviando...';
        }
    });

    // Estilo error input
    const styleErr = document.createElement('style');
    styleErr.textContent = `
        .input--error {
            border-color: var(--red-err) !important;
            background: rgba(192,57,43,.04) !important;
        }
    `;
    document.head.appendChild(styleErr);
})();


/* ============================================================
   5. SMOOTH SCROLL para anchors (fallback cross-browser)
============================================================ */
document.addEventListener('click', (e) => {
    const a = e.target.closest('a[href^="#"]');
    if (!a) return;
    const id = a.getAttribute('href').slice(1);
    if (!id) return;
    const target = document.getElementById(id);
    if (!target) return;

    e.preventDefault();
    const navH = 72; // altura navbar
    const top  = target.getBoundingClientRect().top + window.scrollY - navH;
    window.scrollTo({ top, behavior: 'smooth' });
    // Actualizar URL sin reload
    history.pushState(null, '', `#${id}`);
});


/* ============================================================
   6. ANIMATE ON SCROLL — reveal suave al hacer scroll
============================================================ */
(function initReveal() {
    if (!('IntersectionObserver' in window)) return;

    const estilo = document.createElement('style');
    estilo.textContent = `
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity .55s ease, transform .55s ease; }
        .reveal.visible { opacity: 1; transform: none; }
    `;
    document.head.appendChild(estilo);

    // Aplicar clase a los elementos objetivo
    const selectores = [
        '.service-card', '.step', '.testimonial',
        '.section__header', '.bot-widget', '.reserva-form'
    ];
    selectores.forEach(sel => {
        $$(sel).forEach((el, i) => {
            el.classList.add('reveal');
            el.style.transitionDelay = `${(i % 4) * 0.08}s`;
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    $$('.reveal').forEach(el => observer.observe(el));
})();
