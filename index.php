<?php
// ============================================================
// RekkieMasajes — index.php
// Hosting compartido PHP 8.4 · Sin Composer · Sin frameworks
// ============================================================
session_start();

// Captcha simple: generar suma aleatoria
$n1 = rand(2, 9);
$n2 = rand(1, 8);
$_SESSION['captcha_answer'] = $n1 + $n2;

$msg_ok    = isset($_GET['ok']);
$msg_error = isset($_GET['err']) ? htmlspecialchars(strip_tags($_GET['err'])) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RekkieMasajes | Masajes Profesionales en Santiago de Chile</title>
    <meta name="description" content="Centro de masajes profesionales en Santiago. Relajación, descontracturante, drenaje linfático y reductivo. Reserva sin costo anticipado. Terapeutas certificados.">
    <meta name="keywords" content="masajes santiago, masoterapia santiago, masaje relajacion santiago, masaje descontracturante, drenaje linfatico santiago">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://divinittys.cl/rekkiemasajes/">

    <!-- Open Graph -->
    <meta property="og:title" content="RekkieMasajes | Masajes Profesionales en Santiago">
    <meta property="og:description" content="Terapias certificadas para liberar tensión, reducir dolor y restaurar tu energía. Agenda sin costo anticipado.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://divinittys.cl/rekkiemasajes/">
    <meta property="og:locale" content="es_CL">

    <!-- Schema.org LocalBusiness -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "HealthAndBeautyBusiness",
      "name": "RekkieMasajes",
      "description": "Centro de masajes profesionales en Santiago de Chile",
      "url": "https://divinittys.cl/rekkiemasajes/",
      "telephone": "+56989024643",
      "openingHours": "Mo-Sa 09:00-20:00",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Santiago",
        "addressRegion": "Región Metropolitana",
        "addressCountry": "CL"
      }
    }
    </script>

    <!-- Fonts: Cormorant Garamond (elegante) + DM Sans (lectura) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ══════════════════════════════════════════════ -->
<!--  NAVBAR                                        -->
<!-- ══════════════════════════════════════════════ -->
<header class="nav" id="nav">
    <div class="nav__inner">
        <a href="#inicio" class="nav__logo" aria-label="RekkieMasajes inicio">
            Rekkie<span>Masajes</span>
        </a>
        <nav class="nav__links" id="nav-links" aria-label="Navegación principal">
            <a href="#servicios">Servicios</a>
            <a href="#como-funciona">¿Cómo funciona?</a>
            <a href="#testimonios">Testimonios</a>
            <a href="#reservar" class="btn btn--nav">Agendar hora</a>
        </nav>
        <button class="nav__burger" id="burger" aria-label="Abrir menú" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
    <!-- Menú móvil -->
    <div class="nav__mobile" id="nav-mobile" hidden>
        <a href="#servicios">Servicios</a>
        <a href="#como-funciona">¿Cómo funciona?</a>
        <a href="#testimonios">Testimonios</a>
        <a href="#reservar" class="btn btn--primary">Agendar hora</a>
    </div>
</header>


<!-- ══════════════════════════════════════════════ -->
<!--  HERO                                          -->
<!-- ══════════════════════════════════════════════ -->
<section class="hero" id="inicio">
    <div class="hero__overlay"></div>
    <picture class="hero__img-wrap">
        <img
            src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1600&q=75"
            alt="Ambiente de spa tranquilo con velas y masaje relajante"
            class="hero__img"
            loading="eager"
            decoding="async">
    </picture>
    <div class="hero__content">
        <p class="hero__eyebrow">Masajes profesionales · Santiago</p>
        <h1 class="hero__title">Tu cuerpo<br><em>merece recuperarse.</em></h1>
        <p class="hero__sub">Terapias certificadas para liberar tensión,<br>reducir dolor y restaurar tu energía.</p>
        <div class="hero__ctas">
            <a href="#reservar" class="btn btn--primary btn--lg">Reservar mi hora</a>
            <a href="#bot" class="btn btn--ghost btn--lg">¿Qué masaje necesito?</a>
        </div>
        <ul class="hero__trust" aria-label="Garantías del servicio">
            <li>✓ Terapeutas certificados</li>
            <li>✓ Sin pago anticipado</li>
            <li>✓ Aceites orgánicos</li>
        </ul>
    </div>
    <a href="#servicios" class="hero__scroll" aria-label="Ir a servicios">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
    </a>
</section>


<!-- ══════════════════════════════════════════════ -->
<!--  SERVICIOS                                     -->
<!-- ══════════════════════════════════════════════ -->
<section class="section" id="servicios">
    <div class="container">
        <header class="section__header">
            <p class="section__eyebrow">Lo que ofrecemos</p>
            <h2 class="section__title">Elige tu terapia ideal</h2>
            <p class="section__sub">Si no sabes cuál elegir, usa nuestro <a href="#bot" class="link">orientador gratuito</a> — te recomendamos en 3 preguntas.</p>
        </header>

        <div class="cards-grid">

            <!-- Relajación -->
            <article class="service-card" data-tipo="relajacion">
                <div class="service-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                        <circle cx="24" cy="24" r="20"/>
                        <path d="M16 22c2-3 5-4 8-4s6 1 8 4"/>
                        <path d="M18 31s2 3 6 3 6-3 6-3"/>
                        <path d="M19 18c1-2 3-3 5-3"/>
                    </svg>
                </div>
                <div class="service-card__body">
                    <h3>Masaje Relajación</h3>
                    <p>Movimientos suaves y armónicos que calman el sistema nervioso, reducen el cortisol y restauran la calma interior.</p>
                    <ul class="benefit-list">
                        <li>Reduce estrés y ansiedad</li>
                        <li>Mejora calidad del sueño</li>
                        <li>Libera tensión acumulada</li>
                    </ul>
                </div>
                <div class="service-card__footer">
                    <div class="service-card__price">
                        <strong>$30.000</strong><span>/ 60 min</span>
                    </div>
                    <a href="#reservar" class="btn btn--outline"
                       onclick="prefillServicio('Masaje de Relajación ($30.000)')">
                        Reservar
                    </a>
                </div>
            </article>

            <!-- Descontracturante -->
            <article class="service-card" data-tipo="descontracturante">
                <div class="service-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                        <path d="M24 8v32M14 20l10-6 10 6"/>
                        <path d="M10 32c4-6 8-8 14-8s10 2 14 8"/>
                    </svg>
                </div>
                <div class="service-card__body">
                    <h3>Masaje Descontracturante</h3>
                    <p>Presión profunda sobre nudos musculares crónicos. Elimina contracturas y alivia el dolor cervical y lumbar.</p>
                    <ul class="benefit-list">
                        <li>Elimina contracturas crónicas</li>
                        <li>Alivia cervicales y lumbares</li>
                        <li>Mejora rango de movimiento</li>
                    </ul>
                </div>
                <div class="service-card__footer">
                    <div class="service-card__price">
                        <strong>$40.000</strong><span>/ 60 min</span>
                    </div>
                    <a href="#reservar" class="btn btn--outline"
                       onclick="prefillServicio('Masaje Descontracturante ($40.000)')">
                        Reservar
                    </a>
                </div>
            </article>

            <!-- Drenaje -->
            <article class="service-card" data-tipo="drenaje">
                <div class="service-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                        <path d="M24 8c0 0-14 11-14 22a14 14 0 0028 0C38 19 24 8 24 8z"/>
                        <path d="M24 38V26M19 31l5 5 5-5"/>
                    </svg>
                </div>
                <div class="service-card__body">
                    <h3>Drenaje Linfático</h3>
                    <p>Técnica suave que activa el sistema linfático, elimina toxinas y reduce la retención de líquidos desde la primera sesión.</p>
                    <ul class="benefit-list">
                        <li>Reduce hinchazón y retención</li>
                        <li>Desintoxica el organismo</li>
                        <li>Mejora circulación y textura</li>
                    </ul>
                </div>
                <div class="service-card__footer">
                    <div class="service-card__price">
                        <strong>$50.000</strong><span>/ 75 min</span>
                    </div>
                    <a href="#reservar" class="btn btn--outline"
                       onclick="prefillServicio('Drenaje Linfático ($50.000)')">
                        Reservar
                    </a>
                </div>
            </article>

            <!-- Reductivo — DESTACADO -->
            <article class="service-card service-card--featured" data-tipo="reductivo">
                <div class="service-card__badge">Más solicitado</div>
                <div class="service-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
                        <path d="M12 36l7-14 6 9 5-8 6 13"/>
                        <circle cx="24" cy="18" r="8"/>
                    </svg>
                </div>
                <div class="service-card__body">
                    <h3>Masaje Reductivo</h3>
                    <p>Técnica enérgica para modelar la figura, reducir tejido adiposo y mejorar la tonicidad. Resultados visibles y progresivos.</p>
                    <ul class="benefit-list">
                        <li>Modela y tonifica el cuerpo</li>
                        <li>Reduce celulitis y adiposidad</li>
                        <li>Activa circulación profunda</li>
                    </ul>
                </div>
                <div class="service-card__footer">
                    <div class="service-card__price">
                        <strong>$60.000</strong><span>/ 75 min</span>
                    </div>
                    <a href="#reservar" class="btn btn--primary"
                       onclick="prefillServicio('Masaje Reductivo ($60.000)')">
                        Reservar
                    </a>
                </div>
            </article>

        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════════ -->
<!--  CÓMO FUNCIONA                                 -->
<!-- ══════════════════════════════════════════════ -->
<section class="section section--alt" id="como-funciona">
    <div class="container">
        <header class="section__header">
            <p class="section__eyebrow">El proceso</p>
            <h2 class="section__title">Simple y sin complicaciones</h2>
        </header>
        <ol class="steps" aria-label="Pasos para reservar">
            <li class="step">
                <div class="step__num" aria-hidden="true">01</div>
                <div class="step__body">
                    <h3>Elige tu terapia</h3>
                    <p>Revisa los servicios o usa el orientador. Selecciona la que mejor se adapte a lo que sientes.</p>
                </div>
            </li>
            <li class="step">
                <div class="step__num" aria-hidden="true">02</div>
                <div class="step__body">
                    <h3>Completa el formulario</h3>
                    <p>Indica fecha, hora y cualquier molestia específica. No pagás nada por adelantado.</p>
                </div>
            </li>
            <li class="step">
                <div class="step__num" aria-hidden="true">03</div>
                <div class="step__body">
                    <h3>Te confirmamos en 2h</h3>
                    <p>Te contactamos por WhatsApp para confirmar hora y resolver cualquier duda.</p>
                </div>
            </li>
            <li class="step">
                <div class="step__num" aria-hidden="true">04</div>
                <div class="step__body">
                    <h3>Llega y desconéctate</h3>
                    <p>Nosotros nos encargamos del resto. Solo trae ganas de sentirte bien.</p>
                </div>
            </li>
        </ol>
    </div>
</section>


<!-- ══════════════════════════════════════════════ -->
<!--  BOT ORIENTADOR                                -->
<!-- ══════════════════════════════════════════════ -->
<section class="section" id="bot">
    <div class="container container--narrow">
        <header class="section__header">
            <p class="section__eyebrow">Orientador personalizado</p>
            <h2 class="section__title">¿No sabes qué masaje elegir?</h2>
            <p class="section__sub">Responde 3 preguntas y te recomendamos la terapia ideal para ti, gratis.</p>
        </header>

        <div class="bot-widget" id="bot-widget" role="region" aria-label="Orientador de servicios" aria-live="polite">

            <!-- STEPS -->
            <div class="bot-step active" id="bot-step-1" aria-hidden="false">
                <p class="bot-question">¿Cuál describe mejor tu situación ahora mismo?</p>
                <div class="bot-options">
                    <button class="bot-option" data-step="1" data-key="zona" data-val="dolor">
                        <span class="bot-option__icon">🔴</span>
                        Tengo dolor o contracturas musculares
                    </button>
                    <button class="bot-option" data-step="1" data-key="zona" data-val="estres">
                        <span class="bot-option__icon">😮‍💨</span>
                        Estoy muy estresado/a o ansioso/a
                    </button>
                    <button class="bot-option" data-step="1" data-key="zona" data-val="figura">
                        <span class="bot-option__icon">✨</span>
                        Quiero mejorar mi figura o tonicidad
                    </button>
                    <button class="bot-option" data-step="1" data-key="zona" data-val="liquidos">
                        <span class="bot-option__icon">💧</span>
                        Siento hinchazón o retención de líquidos
                    </button>
                </div>
            </div>

            <div class="bot-step" id="bot-step-2" aria-hidden="true">
                <p class="bot-question">¿Con qué frecuencia tienes esta molestia?</p>
                <div class="bot-options">
                    <button class="bot-option" data-step="2" data-key="frecuencia" data-val="cronica">
                        <span class="bot-option__icon">📅</span>
                        Hace meses o años (crónico)
                    </button>
                    <button class="bot-option" data-step="2" data-key="frecuencia" data-val="reciente">
                        <span class="bot-option__icon">🕐</span>
                        Desde hace días o semanas
                    </button>
                    <button class="bot-option" data-step="2" data-key="frecuencia" data-val="prevencion">
                        <span class="bot-option__icon">🛡️</span>
                        Estoy bien, quiero prevenir / mantenerme
                    </button>
                </div>
            </div>

            <div class="bot-step" id="bot-step-3" aria-hidden="true">
                <p class="bot-question">¿Has recibido masajes profesionales antes?</p>
                <div class="bot-options">
                    <button class="bot-option" data-step="3" data-key="experiencia" data-val="primera">
                        <span class="bot-option__icon">🌱</span>
                        No, es mi primera vez
                    </button>
                    <button class="bot-option" data-step="3" data-key="experiencia" data-val="esporadico">
                        <span class="bot-option__icon">🌿</span>
                        He ido algunas veces
                    </button>
                    <button class="bot-option" data-step="3" data-key="experiencia" data-val="regular">
                        <span class="bot-option__icon">🌳</span>
                        Soy cliente habitual
                    </button>
                </div>
            </div>

            <!-- RESULTADO -->
            <div class="bot-result" id="bot-result" aria-hidden="true">
                <div class="bot-result__check" aria-hidden="true">✓</div>
                <p class="bot-result__label">Tu terapia recomendada</p>
                <h3 class="bot-result__title" id="bot-result-title"></h3>
                <p class="bot-result__desc" id="bot-result-desc"></p>
                <p class="bot-result__price" id="bot-result-price"></p>
                <div class="bot-result__actions">
                    <a href="#reservar" class="btn btn--primary" id="bot-result-cta">Reservar esta terapia</a>
                    <button class="btn btn--ghost btn--sm" id="bot-reset">Empezar de nuevo</button>
                </div>
            </div>

            <!-- PROGRESO -->
            <div class="bot-progress" id="bot-progress" aria-hidden="true">
                <div class="bot-progress__bar">
                    <div class="bot-progress__fill" id="bot-progress-fill" style="width:33%"></div>
                </div>
                <span class="bot-progress__label" id="bot-progress-label">Paso 1 de 3</span>
            </div>

        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════════ -->
<!--  TESTIMONIOS                                   -->
<!-- ══════════════════════════════════════════════ -->
<section class="section section--alt" id="testimonios">
    <div class="container">
        <header class="section__header">
            <p class="section__eyebrow">Lo que dicen nuestras clientas</p>
            <h2 class="section__title">Resultados reales</h2>
        </header>
        <div class="testimonials-grid">

            <figure class="testimonial">
                <div class="testimonial__stars" aria-label="5 estrellas">★★★★★</div>
                <blockquote>
                    <p>"Llevaba tres meses con contractura en el cuello. Después de la primera sesión descontracturante quedé como nueva. El ambiente es increíblemente tranquilo y profesional."</p>
                </blockquote>
                <figcaption>
                    <div class="testimonial__avatar" aria-hidden="true">M</div>
                    <div>
                        <strong>María José R.</strong>
                        <span>Masaje Descontracturante</span>
                    </div>
                </figcaption>
            </figure>

            <figure class="testimonial">
                <div class="testimonial__stars" aria-label="5 estrellas">★★★★★</div>
                <blockquote>
                    <p>"Hice el drenaje linfático antes de un evento importante y la diferencia fue inmediata. Muy profesionales y el trato es súper personalizado. Definitivamente vuelvo."</p>
                </blockquote>
                <figcaption>
                    <div class="testimonial__avatar" aria-hidden="true">C</div>
                    <div>
                        <strong>Catalina V.</strong>
                        <span>Drenaje Linfático</span>
                    </div>
                </figcaption>
            </figure>

            <figure class="testimonial">
                <div class="testimonial__stars" aria-label="5 estrellas">★★★★★</div>
                <blockquote>
                    <p>"Vengo cada dos semanas al masaje reductivo. Los resultados son consistentes y saben exactamente qué necesitas sin que tengas que explicar mucho. Muy recomendable."</p>
                </blockquote>
                <figcaption>
                    <div class="testimonial__avatar" aria-hidden="true">A</div>
                    <div>
                        <strong>Alejandra M.</strong>
                        <span>Masaje Reductivo</span>
                    </div>
                </figcaption>
            </figure>

        </div>
    </div>
</section>


<!-- ══════════════════════════════════════════════ -->
<!--  FORMULARIO RESERVA                            -->
<!-- ══════════════════════════════════════════════ -->
<section class="section" id="reservar">
    <div class="container container--narrow">
        <header class="section__header">
            <p class="section__eyebrow">Agenda tu hora</p>
            <h2 class="section__title">Reserva sin costo previo</h2>
            <p class="section__sub">Completa el formulario y te confirmamos por WhatsApp en menos de 2 horas hábiles.</p>
        </header>

        <?php if ($msg_ok): ?>
        <div class="alert alert--ok" role="alert">
            <strong>¡Reserva recibida!</strong> Te contactaremos pronto para confirmar tu hora. ¡Nos vemos pronto!
        </div>
        <?php elseif ($msg_error): ?>
        <div class="alert alert--error" role="alert">
            <strong>Hubo un problema:</strong> <?= $msg_error ?>. Por favor intenta de nuevo o escríbenos por <a href="https://wa.me/56989024643">WhatsApp</a>.
        </div>
        <?php endif; ?>

        <form class="reserva-form" id="reserva-form" action="reserva.php" method="POST" novalidate>
            <!-- Honeypot anti-spam (los bots lo llenan, los humanos no lo ven) -->
            <div class="hp-field" aria-hidden="true">
                <label for="hp_website">Sitio web (dejar vacío)</label>
                <input type="text" id="hp_website" name="hp_website" tabindex="-1" autocomplete="off" value="">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre completo <abbr title="requerido">*</abbr></label>
                    <input type="text" id="nombre" name="nombre" required
                        minlength="2" maxlength="80" placeholder="Tu nombre">
                    <span class="form-err" id="err-nombre" role="alert"></span>
                </div>
                <div class="form-group">
                    <label for="telefono">WhatsApp / Teléfono <abbr title="requerido">*</abbr></label>
                    <input type="tel" id="telefono" name="telefono" required
                        maxlength="20" placeholder="+569 XXXX XXXX">
                    <span class="form-err" id="err-telefono" role="alert"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email (opcional, para recordatorio)</label>
                    <input type="email" id="email" name="email"
                        maxlength="120" placeholder="tu@email.com">
                </div>
                <div class="form-group">
                    <label for="servicio">Servicio deseado <abbr title="requerido">*</abbr></label>
                    <select id="servicio" name="servicio" required>
                        <option value="">Selecciona...</option>
                        <option value="Masaje de Relajación ($30.000)">Masaje de Relajación — $30.000</option>
                        <option value="Masaje Descontracturante ($40.000)">Masaje Descontracturante — $40.000</option>
                        <option value="Drenaje Linfático ($50.000)">Drenaje Linfático — $50.000</option>
                        <option value="Masaje Reductivo ($60.000)">Masaje Reductivo — $60.000</option>
                        <option value="No sé, necesito orientación">No sé, necesito orientación</option>
                    </select>
                    <span class="form-err" id="err-servicio" role="alert"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="fecha">Fecha preferida <abbr title="requerido">*</abbr></label>
                    <input type="date" id="fecha" name="fecha" required
                        min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                    <span class="form-err" id="err-fecha" role="alert"></span>
                </div>
                <div class="form-group">
                    <label for="hora">Horario preferido <abbr title="requerido">*</abbr></label>
                    <select id="hora" name="hora" required>
                        <option value="">Selecciona...</option>
                        <option value="09:00">09:00 hrs</option>
                        <option value="10:00">10:00 hrs</option>
                        <option value="11:00">11:00 hrs</option>
                        <option value="12:00">12:00 hrs</option>
                        <option value="14:00">14:00 hrs</option>
                        <option value="15:00">15:00 hrs</option>
                        <option value="16:00">16:00 hrs</option>
                        <option value="17:00">17:00 hrs</option>
                        <option value="18:00">18:00 hrs</option>
                        <option value="19:00">19:00 hrs</option>
                    </select>
                    <span class="form-err" id="err-hora" role="alert"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="notas">¿Algo que debamos saber? (lesiones, alergias, zonas sensibles)</label>
                <textarea id="notas" name="notas" rows="3" maxlength="500"
                    placeholder="Ej: tengo dolor lumbar, alergia a ciertos aceites..."></textarea>
            </div>

            <!-- Captcha matemático simple -->
            <div class="form-group form-group--captcha">
                <label for="captcha">
                    Verificación de seguridad: ¿Cuánto es <strong><?= $n1 ?> + <?= $n2 ?></strong>?
                    <abbr title="requerido">*</abbr>
                </label>
                <input type="number" id="captcha" name="captcha" required
                    min="2" max="18" placeholder="Resultado" class="input--sm">
                <span class="form-err" id="err-captcha" role="alert"></span>
            </div>

            <div class="form-submit">
                <button type="submit" class="btn btn--primary btn--lg btn--full" id="btn-submit">
                    Enviar solicitud de reserva
                </button>
                <p class="form-note">Sin cobro anticipado · Te confirmamos en &lt; 2 horas hábiles</p>
            </div>
        </form>

        <div class="reserva-alt">
            <p>¿Prefieres contacto directo?</p>
            <a href="https://wa.me/56989024643?text=Hola%2C%20me%20gustar%C3%ADa%20agendar%20una%20hora"
               class="btn btn--whatsapp" target="_blank" rel="noopener noreferrer"
               aria-label="Contactar por WhatsApp">
                <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Escribir por WhatsApp
            </a>
        </div>

    </div>
</section>


<!-- ══════════════════════════════════════════════ -->
<!--  FOOTER                                        -->
<!-- ══════════════════════════════════════════════ -->
<footer class="footer" id="contacto">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__brand">
                <p class="footer__logo">Rekkie<span>Masajes</span></p>
                <p>Masajes profesionales en Santiago.<br>Tu bienestar, nuestra pasión.</p>
                <div class="footer__social" aria-label="Redes sociales">
                    <a href="#" aria-label="Instagram" rel="noopener">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                    </a>
                    <a href="#" aria-label="Facebook" rel="noopener">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="https://wa.me/56989024643" aria-label="WhatsApp" rel="noopener">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                </div>
            </div>
            <nav class="footer__nav" aria-label="Servicios">
                <h4>Servicios</h4>
                <ul>
                    <li><a href="#servicios">Masaje de Relajación</a></li>
                    <li><a href="#servicios">Masaje Descontracturante</a></li>
                    <li><a href="#servicios">Drenaje Linfático</a></li>
                    <li><a href="#servicios">Masaje Reductivo</a></li>
                </ul>
            </nav>
            <div class="footer__contact">
                <h4>Contacto</h4>
                <ul>
                    <li><a href="tel:+56989024643">+56 9 8902 4643</a></li>
                    <li><a href="https://wa.me/56989024643" rel="noopener">WhatsApp directo</a></li>
                    <li>Santiago, Chile</li>
                    <li>Lun – Sáb: 9:00 – 20:00</li>
                </ul>
            </div>
        </div>
        <div class="footer__bottom">
            <p>© <?= date('Y') ?> RekkieMasajes · Santiago de Chile · Todos los derechos reservados</p>
        </div>
    </div>
</footer>

<!-- Botón flotante WhatsApp -->
<a href="https://wa.me/56989024643?text=Hola%2C%20quiero%20agendar%20una%20sesi%C3%B3n"
   class="wa-float"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Contactar por WhatsApp">
    <svg viewBox="0 0 24 24" fill="currentColor" width="28" height="28" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
</a>

<script src="script.js"></script>
</body>
</html>
