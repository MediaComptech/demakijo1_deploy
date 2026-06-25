<?php

namespace App\Core;

/**
 * Kelas Router
 * 
 * Sistem routing sederhana yang memetakan URL ke Controller dan Method.
 */
class Router
{
    private static $routes = [];

    /**
     * Mendaftarkan route GET
     */
    public static function get($uri, $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    /**
     * Mendaftarkan route POST
     */
    public static function post($uri, $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    /**
     * Menjalankan routing dengan mencocokkan URI yang diakses
     */
    public static function dispatch()
    {
        // Mendapatkan URL saat ini (tanpa query string)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Menghapus basepath jika aplikasi tidak berada di root folder
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && $scriptName !== '\\') {
            $uri = str_replace($scriptName, '', $uri);
        }
        
        $uri = rtrim($uri, '/');
        if (empty($uri)) $uri = '/';

        $method = $_SERVER['REQUEST_METHOD'];

        // Cek apakah method spoofing digunakan (misal: untuk PUT/DELETE via POST)
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofed = strtoupper($_POST['_method']);
            if (isset(self::$routes[$spoofed])) {
                $method = $spoofed;
            }
        }

        // Cari route yang cocok
        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $routeUri => $action) {
                // Konversi parameter route (misal: {id} menjadi regex) — dukung huruf, angka, underscore, dan hyphen
                $routeRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_\-]+)', $routeUri);
                $routeRegex = "#^" . $routeRegex . "$#";

                if (preg_match($routeRegex, $uri, $matches)) {
                    array_shift($matches); // Hapus hasil match lengkap, sisakan parameter

                    // Action bisa berupa fungsi anonymous (closure) atau string Controller@method
                    if (is_callable($action)) {
                        http_response_code(200);
                        call_user_func_array($action, $matches);
                        return;
                    }

                    if (is_string($action)) {
                        list($controller, $function) = explode('@', $action);
                        $controllerClass = "\\App\\Controllers\\" . $controller;

                        if (class_exists($controllerClass)) {
                            $instance = new $controllerClass();
                            if (method_exists($instance, $function)) {
                                // Dependency Injection untuk Request
                                $reflection = new \ReflectionMethod($instance, $function);
                                $params = $reflection->getParameters();
                                $finalArgs = [];
                                $matchIndex = 0;
                                
                                foreach ($params as $param) {
                                    $type = $param->getType();
                                    $typeName = $type ? $type->getName() : '';
                                    if ($typeName === 'App\Core\Request' || $typeName === 'Illuminate\Http\Request') {
                                        $finalArgs[] = new \App\Core\Request();
                                    } else {
                                        $finalArgs[] = $matches[$matchIndex] ?? null;
                                        $matchIndex++;
                                    }
                                }
                                
                                http_response_code(200);
                                call_user_func_array([$instance, $function], $finalArgs);
                                return;
                            }
                        }
                    }
                }
            }
        }

        // Jika tidak ada route yang cocok, tampilkan 404
        self::abort(404);
    }

    /**
     * Menampilkan halaman error
     */
    public static function abort($code = 404)
    {
        http_response_code($code);
        if ($code == 404) {
            echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
        } else {
            echo "<h1>{$code} - Terjadi Kesalahan</h1>";
        }
        exit;
    }
}
