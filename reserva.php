<?php
/**
 * RekkieMasajes — reserva.php
 *
 * Procesa el formulario de reserva:
 * 1. Valida y sanitiza todos los inputs
 * 2. Verifica captcha (en $_SESSION)
 * 3. Verifica honeypot anti-spam
 * 4. Envía email al terapeuta con todos los detalles
 * 5. Envía email de confirmación al cliente (si dejó email)
 * 6. Genera enlace a Google Calendar pre-relleno (Opción A)
 * 7. Redirige con mensaje de éxito o error
 *
 * Hosting compartido PHP 8.4 · Sin Composer · Sin frameworks
 */

declare(strict_types=1);
session_start();

// ── Configuración ──────────────────────────────────────────────────────────

// ⚠️ CAMBIAR ESTOS VALORES antes de subir al servidor
define('EMAIL_TERAPEUTA',   'rekkiemasajes@gmail.com');   // Email donde llegan las reservas
define('EMAIL_REMITENTE',   'noreply@divinittys.cl');     // Email que aparece como remitente
define('NOMBRE_NEGOCIO',    'RekkieMasajes');
define('WHATSAPP_NUMERO',   '56989024643');
define('URL_BASE',          'https://divinittys.cl/rekkiemasajes/');

// Duraciones aproximadas por servicio (para Google Calendar)
const DURACIONES = [
    'Masaje de Relajación ($30.000)'    => 60,
    'Masaje Descontracturante ($40.000)'=> 60,
    'Drenaje Linfático ($50.000)'       => 75,
    'Masaje Reductivo ($60.000)'        => 75,
    'No sé, necesito orientación'       => 60,
];


// ── Solo aceptar POST ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}


// ── Función de redirect seguro ─────────────────────────────────────────────
function redirigir(bool $ok, string $msg = ''): never {
    $url = URL_BASE . 'index.php';
    if ($ok) {
        header("Location: {$url}?ok=1#reservar");
    } else {
        $enc = urlencode($msg);
        header("Location: {$url}?err={$enc}#reservar");
    }
    exit;
}


// ── 1. HONEYPOT anti-spam ──────────────────────────────────────────────────
// Si el campo "hp_website" tiene contenido, es un bot. Simular éxito.
if (!empty($_POST['hp_website'])) {
    // Silenciosamente "éxito" — el bot cree que funcionó
    redirigir(true);
}


// ── 2. RATE LIMITING básico por IP (archivo) ─────────────────────────────
$ip_raw   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$ip_hash  = hash('sha256', $ip_raw); // nunca guardar IP en texto claro
$rl_file  = sys_get_temp_dir() . '/rekkie_rl_' . $ip_hash . '.json';
$ahora    = time();
$limite   = 5;    // máximo N envíos
$ventana  = 3600; // por hora (segundos)

$rl_data = [];
if (is_file($rl_file)) {
    $raw = file_get_contents($rl_file);
    $rl_data = json_decode($raw, true) ?: [];
}
// Limpiar registros viejos
$rl_data = array_filter($rl_data, fn($t) => ($ahora - $t) < $ventana);
if (count($rl_data) >= $limite) {
    redirigir(false, 'Demasiados intentos. Por favor espera un momento o contáctanos por WhatsApp.');
}


// ── 3. SANITIZACIÓN y VALIDACIÓN ──────────────────────────────────────────
function limpiar(string $val, int $max = 500): string {
    $val = trim($val);
    $val = strip_tags($val);
    $val = htmlspecialchars($val, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return mb_substr($val, 0, $max, 'UTF-8');
}

$errores = [];

// Nombre
$nombre = limpiar($_POST['nombre'] ?? '', 80);
if (mb_strlen($nombre) < 2) {
    $errores[] = 'Nombre inválido';
}

// Teléfono
$telefono = limpiar($_POST['telefono'] ?? '', 20);
if (!preg_match('/^(\+?56\s?)?0?9\s?[9876543]\d{7}$|^\d{7,15}$/', preg_replace('/\s/', '', $telefono))) {
    $errores[] = 'Teléfono inválido';
}

// Email (opcional)
$email_raw = limpiar($_POST['email'] ?? '', 120);
$email     = '';
if ($email_raw !== '') {
    if (filter_var($email_raw, FILTER_VALIDATE_EMAIL)) {
        $email = $email_raw;
    }
    // Si tiene email pero es inválido, no bloqueamos la reserva — simplemente ignoramos
}

// Servicio
$servicios_validos = array_keys(DURACIONES);
$servicio_raw = $_POST['servicio'] ?? '';
if (!in_array($servicio_raw, $servicios_validos, true)) {
    $errores[] = 'Servicio no reconocido';
}
$servicio = $servicio_raw; // Ya validado contra whitelist

// Fecha
$fecha_raw = $_POST['fecha'] ?? '';
$fecha_obj = DateTimeImmutable::createFromFormat('Y-m-d', $fecha_raw);
$hoy       = new DateTimeImmutable('today');
if (!$fecha_obj || $fecha_obj <= $hoy) {
    $errores[] = 'Fecha inválida (debe ser a partir de mañana)';
}
$fecha    = $fecha_obj ? $fecha_obj->format('d/m/Y') : '';
$fecha_gc = $fecha_obj ? $fecha_obj->format('Ymd') : ''; // formato Google Calendar

// Hora
$horas_validas = ['09:00','10:00','11:00','12:00','14:00','15:00','16:00','17:00','18:00','19:00'];
$hora = $_POST['hora'] ?? '';
if (!in_array($hora, $horas_validas, true)) {
    $errores[] = 'Horario inválido';
}

// Notas (opcional)
$notas = limpiar($_POST['notas'] ?? '', 500);

// Captcha
$captcha_respuesta = (int)($_POST['captcha'] ?? -99);
$captcha_correcto  = (int)($_SESSION['captcha_answer'] ?? 0);
if ($captcha_correcto === 0 || $captcha_respuesta !== $captcha_correcto) {
    $errores[] = 'Verificación incorrecta';
}
// Invalidar sesión para evitar replay
unset($_SESSION['captcha_answer']);


// Si hay errores de validación
if (!empty($errores)) {
    redirigir(false, implode('. ', $errores));
}


// ── 4. GOOGLE CALENDAR LINK pre-relleno ──────────────────────────────────
/**
 * Genera enlace a Google Calendar para que el CLIENTE pueda agregar
 * el evento a su propia agenda. No requiere OAuth ni credenciales.
 */
function generarEnlaceGoogleCalendar(
    DateTimeImmutable $fechaObj,
    string $hora,
    string $servicio,
    int $duracionMin
): string {
    // Construir fecha/hora inicio (formato GC: YYYYMMDDTHHmmss)
    [$h, $m] = explode(':', $hora);
    $inicio  = $fechaObj->setTime((int)$h, (int)$m, 0);

    // Fecha fin
    $fin = $inicio->modify("+{$duracionMin} minutes");

    $gcStart = $inicio->format('Ymd') . 'T' . $inicio->format('His');
    $gcEnd   = $fin->format('Ymd')   . 'T' . $fin->format('His');

    $titulo = NOMBRE_NEGOCIO . ' — ' . $servicio;
    $desc   = "Masaje profesional en " . NOMBRE_NEGOCIO . ".\n"
            . "Confirmación por WhatsApp al +{$_ENV['WA_NUM'] ?? WHATSAPP_NUMERO}.\n"
            . "No se requiere pago anticipado.";

    return 'https://calendar.google.com/calendar/r/eventedit?' . http_build_query([
        'text'     => $titulo,
        'dates'    => "{$gcStart}/{$gcEnd}",
        'details'  => $desc,
        'location' => 'Santiago, Chile',
        'sf'       => 'true',
        'output'   => 'xml',
    ]);
}

$duracion    = DURACIONES[$servicio] ?? 60;
$enlace_gc   = generarEnlaceGoogleCalendar($fecha_obj, $hora, $servicio, $duracion);
$enlace_wa   = 'https://wa.me/' . WHATSAPP_NUMERO . '?text=' .
               urlencode("Hola, mi nombre es {$nombre}. Realicé una reserva para {$servicio} el {$fecha} a las {$hora} hrs.");


// ── 5. EMAIL AL TERAPEUTA ─────────────────────────────────────────────────
function enviarEmailTerapeuta(
    string $nombre, string $telefono, string $email,
    string $servicio, string $fecha, string $hora,
    string $notas, string $enlace_gc, string $enlace_wa
): bool {

    $asunto = "🗓️ Nueva reserva: {$servicio} — {$fecha} {$hora}";

    $cuerpo_html = "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>
    <style>
        body  { font-family: 'DM Sans', Arial, sans-serif; background:#f5f5f2; margin:0; padding:0; }
        .wrap { max-width:560px; margin:2rem auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.08); }
        .top  { background:#6B9080; color:#fff; padding:1.5rem 2rem; }
        .top h1 { margin:0; font-size:1.3rem; font-weight:400; }
        .body { padding:1.75rem 2rem; }
        .row  { margin-bottom:1rem; }
        .lbl  { font-size:.75rem; text-transform:uppercase; letter-spacing:.1em; color:#888; margin-bottom:.25rem; }
        .val  { font-size:1rem; color:#2c2c2a; font-weight:500; }
        .nota { background:#faf7f2; border-left:3px solid #6B9080; padding:.75rem 1rem; border-radius:4px; font-size:.93rem; color:#4a4a47; }
        .btn  { display:inline-block; background:#6B9080; color:#fff; padding:.65rem 1.4rem; border-radius:5px; text-decoration:none; font-size:.9rem; margin:.35rem .35rem 0 0; }
        .btn-gc { background:#4285F4; }
        .foot  { background:#f0eae0; padding:1rem 2rem; font-size:.8rem; color:#888; text-align:center; }
    </style></head><body>
    <div class='wrap'>
        <div class='top'><h1>📋 Nueva solicitud de reserva</h1></div>
        <div class='body'>
            <div class='row'><div class='lbl'>Nombre</div><div class='val'>" . htmlspecialchars($nombre) . "</div></div>
            <div class='row'><div class='lbl'>Teléfono / WhatsApp</div><div class='val'>" . htmlspecialchars($telefono) . "</div></div>"
            . ($email ? "<div class='row'><div class='lbl'>Email</div><div class='val'>" . htmlspecialchars($email) . "</div></div>" : "")
            . "<div class='row'><div class='lbl'>Servicio solicitado</div><div class='val'>" . htmlspecialchars($servicio) . "</div></div>
            <div class='row'><div class='lbl'>Fecha preferida</div><div class='val'>" . htmlspecialchars($fecha) . "</div></div>
            <div class='row'><div class='lbl'>Horario preferido</div><div class='val'>" . htmlspecialchars($hora) . " hrs</div></div>"
            . ($notas ? "<div class='row'><div class='lbl'>Notas del cliente</div><div class='nota'>" . nl2br(htmlspecialchars($notas)) . "</div></div>" : "")
            . "<div style='margin-top:1.5rem;'>
                <a href='" . htmlspecialchars($enlace_wa) . "' class='btn'>💬 Confirmar por WhatsApp</a>
                <a href='" . htmlspecialchars($enlace_gc) . "' class='btn btn-gc' target='_blank'>📅 Agregar a Google Calendar</a>
               </div>
        </div>
        <div class='foot'>" . NOMBRE_NEGOCIO . " · Solicitud recibida el " . date('d/m/Y H:i') . "</div>
    </div></body></html>";

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . NOMBRE_NEGOCIO . " <" . EMAIL_REMITENTE . ">\r\n";
    $headers .= "Reply-To: " . htmlspecialchars($telefono) . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    return mail(EMAIL_TERAPEUTA, $asunto, $cuerpo_html, $headers);
}


// ── 6. EMAIL DE CONFIRMACIÓN AL CLIENTE ───────────────────────────────────
function enviarEmailCliente(
    string $email, string $nombre,
    string $servicio, string $fecha, string $hora,
    string $enlace_gc, string $enlace_wa
): bool {

    if (empty($email)) return true; // No hay email, ok

    $asunto = "✅ Solicitud recibida — " . NOMBRE_NEGOCIO;

    $cuerpo_html = "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>
    <style>
        body  { font-family: Arial, sans-serif; background:#f5f5f2; margin:0; padding:0; }
        .wrap { max-width:520px; margin:2rem auto; background:#fff; border-radius:8px; overflow:hidden; }
        .top  { background:#6B9080; color:#fff; padding:2rem; text-align:center; }
        .top h1 { margin:0 0 .5rem; font-size:1.2rem; font-weight:400; }
        .body { padding:2rem; color:#4a4a47; line-height:1.6; }
        .resumen { background:#faf7f2; border-radius:6px; padding:1.25rem; margin:1.25rem 0; }
        .resumen p { margin:.35rem 0; font-size:.93rem; }
        .resumen strong { color:#2c2c2a; }
        .btn  { display:inline-block; background:#6B9080; color:#fff; padding:.65rem 1.4rem; border-radius:5px; text-decoration:none; font-size:.9rem; margin:.35rem .35rem 0 0; }
        .btn-gc { background:#4285F4; }
        .foot  { background:#f0eae0; padding:1rem 2rem; font-size:.78rem; color:#888; text-align:center; }
    </style></head><body>
    <div class='wrap'>
        <div class='top'>
            <h1>¡Solicitud recibida, " . htmlspecialchars($nombre) . "!</h1>
            <p style='margin:0;font-size:.9rem;opacity:.85'>Te confirmaremos por WhatsApp en menos de 2 horas hábiles.</p>
        </div>
        <div class='body'>
            <p>Recibimos tu solicitud de reserva con los siguientes detalles:</p>
            <div class='resumen'>
                <p><strong>Servicio:</strong> " . htmlspecialchars($servicio) . "</p>
                <p><strong>Fecha:</strong> " . htmlspecialchars($fecha) . "</p>
                <p><strong>Horario:</strong> " . htmlspecialchars($hora) . " hrs</p>
            </div>
            <p>Puedes agregar el horario a tu calendario personal mientras confirmamos:</p>
            <a href='" . htmlspecialchars($enlace_gc) . "' class='btn btn-gc' target='_blank'>📅 Agregar a Google Calendar</a>
            <p style='margin-top:1.5rem;font-size:.9rem;color:#888;'>
                ¿Necesitas cambiar o cancelar? Escríbenos por WhatsApp:<br>
                <a href='" . htmlspecialchars($enlace_wa) . "' style='color:#6B9080;'>Contactar por WhatsApp</a>
            </p>
        </div>
        <div class='foot'>" . NOMBRE_NEGOCIO . " · Santiago, Chile · Lun–Sáb 9:00–20:00</div>
    </div></body></html>";

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . NOMBRE_NEGOCIO . " <" . EMAIL_REMITENTE . ">\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    return mail($email, $asunto, $cuerpo_html, $headers);
}


// ── 7. ENVIAR EMAILS ─────────────────────────────────────────────────────
$ok_terapeuta = enviarEmailTerapeuta(
    $nombre, $telefono, $email,
    $servicio, $fecha, $hora,
    $notas, $enlace_gc, $enlace_wa
);

// Email cliente (no bloquear si falla)
if ($email !== '') {
    enviarEmailCliente($email, $nombre, $servicio, $fecha, $hora, $enlace_gc, $enlace_wa);
}


// ── 8. REGISTRAR en rate limiting ─────────────────────────────────────────
$rl_data[] = $ahora;
@file_put_contents($rl_file, json_encode(array_values($rl_data)), LOCK_EX);


// ── 9. LOG mínimo (sin datos personales) ──────────────────────────────────
// Solo registra que llegó una solicitud, sin PII
$log_file = __DIR__ . '/reservas_log.txt';
$log_line = date('Y-m-d H:i:s') . " | servicio=" . substr($servicio, 0, 30) . " | ok=" . ($ok_terapeuta ? '1' : '0') . PHP_EOL;
@file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX);


// ── 10. REDIRECT FINAL ────────────────────────────────────────────────────
if ($ok_terapeuta) {
    redirigir(true);
} else {
    // mail() falló — puede ser config del servidor
    // Redirigir a WhatsApp como fallback
    header('Location: ' . $enlace_wa);
    exit;
}
