<?php
// --- INICIO DE LA CONFIGURACIÓN CORS ---

// 1. Define los dominios de tu frontend que tienen permiso
// NUNCA uses '*' en producción si manejas datos sensibles o sesiones.
$dominiosPermitidos = [
    "https://centinela-frontend.vercel.app", // Tu sitio en Render
    "http://localhost:5173"  // Tu sitio de desarrollo local (si usas React/Vue)
];

// 2. Comprueba si el origen de la petición está en tu lista
$origin = $_SERVER['HTTP_ORIGIN'] ?? ''; // Usa '' como fallback si no hay origen

if (in_array($origin, $dominiosPermitidos)) {
    // Si el origen está permitido, especifícalo
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Opcional: Si quieres ser estricto, podrías bloquear aquí
    // Por ahora, lo dejamos pasar, pero el navegador podría bloquearlo
    // si no está en la lista. Si solo quieres permitir tu lista:
    // header("Access-Control-Allow-Origin: " . $dominiosPermitidos[0]); 
    // O mejor, manejar el error.
    
    // Para el ejemplo, usaremos el origen si está en la lista.
    // Si no está, el navegador lo bloqueará si no pones nada.
    // Pongamos el primero de la lista como "default" si no coincide,
    // o simplemente no pongas el header si no quieres.
    // Lo más seguro es:
    if (!in_array($origin, $dominiosPermitidos)) {
         // Si el origen no está en la lista, no envíes el header y sal.
         // Opcional: puedes registrar el intento no autorizado
         // exit('Origen no permitido'); 
         // Lo más simple es solo enviar el header del origen que SÍ está permitido:
         header("Access-Control-Allow-Origin: " . $dominiosPermitidos[0]);
    }
}


// 3. Define los métodos HTTP permitidos (GET, POST, etc.)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// 4. Define los headers que el frontend puede enviar (ej. para auth o tipo de contenido)
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// 5. Permite que el navegador envíe credenciales (cookies, sesiones)
header("Access-Control-Allow-Credentials: true");

// 6. Responde a la solicitud 'preflight' (OPTIONS)
// El navegador envía una solicitud OPTIONS antes de un PUT, DELETE, o POST con JSON
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // 204 No Content
    exit(); // Termina el script, la respuesta preflight está completa
}

// --- FIN DE LA CONFIGURACIÓN CORS ---

// ... Aquí continúa el resto de tu aplicación PHP
// Por ejemplo: include 'database.php'; $controller->handle(); etc.

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
