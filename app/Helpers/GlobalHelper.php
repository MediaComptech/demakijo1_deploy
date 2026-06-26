<?php

/**
 * Global helper functions untuk menggantikan Laravel helpers.
 * File ini di-require otomatis via composer autoload.
 */

if (!function_exists('view')) {
    function view(string $template, array $data = [])
    {
        return \App\Core\View::render($template, $data);
    }
}

if (!function_exists('resolveDotNotationRoute')) {
    function resolveDotNotationRoute(string $name, $param = null): string
    {
        // Jika sudah berupa path langsung (mulai dengan / atau http/https), kembalikan apa adanya
        if (strpos($name, '/') === 0 || strpos($name, 'http://') === 0 || strpos($name, 'https://') === 0) {
            return $name;
        }

        // Pecah berdasarkan dot
        $parts = explode('.', $name);

        if ($parts[0] === 'admin' && count($parts) >= 3) {
            $resource = $parts[1]; // e.g. 'siswa', 'guru', 'ppdb', 'alumni'
            $action   = $parts[2]; // e.g. 'index', 'create', 'store', 'edit', 'update', 'destroy', 'delete'

            // Dapatkan ID
            $id = '';
            if ($param !== null) {
                if (is_object($param)) {
                    $id = $param->id ?? ($param->getKey() ?? '');
                } elseif (is_array($param)) {
                    $id = $param['id'] ?? reset($param);
                } else {
                    $id = $param;
                }
            }

            switch ($action) {
                case 'index':
                    return "/admin/{$resource}";
                case 'create':
                    return "/admin/{$resource}/create";
                case 'store':
                    return "/admin/{$resource}";
                case 'show':
                    return "/admin/{$resource}/{$id}";
                case 'edit':
                    return "/admin/{$resource}/{$id}/edit";
                case 'update':
                    return "/admin/{$resource}/{$id}/update";
                case 'destroy':
                case 'delete':
                    return "/admin/{$resource}/{$id}/delete";
                default:
                    break;
            }
        }

        // Fallback jika bukan dot notation admin CRUD standar
        $path = '/' . str_replace('.', '/', $name);
        $path = preg_replace('/\/index$/', '', $path);
        
        if ($param !== null) {
            $id = is_object($param) ? ($param->id ?? $param->getKey() ?? '') : (is_array($param) ? ($param['id'] ?? reset($param)) : $param);
            if ($id !== '') {
                $path .= '/' . $id;
            }
        }
        return $path;
    }
}

if (!function_exists('url')) {
    function url(string $path = '', $param = null): string
    {
        $resolved = resolveDotNotationRoute($path, $param);
        $base = rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/');
        return $base . '/' . ltrim($resolved, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url)
    {
        // support for route dot notation (admin.berita.index -> /admin/berita)
        if (strpos($url, '.') !== false && strpos($url, '/') === false) {
             $url = '/' . str_replace('.', '/', $url);
             $url = preg_replace('/\/index$/', '', $url);
        }

        return new class($url) {
            private $url;
            public function __construct($url) { $this->url = $url; }
            public function route($name) {
                 $this->url = '/' . str_replace('.', '/', $name);
                 $this->url = preg_replace('/\/index$/', '', $this->url);
                 return $this;
            }
            public function with($key, $value = null) {
                \App\Core\Session::setFlash($key, $value);
                return $this;
            }
            public function __destruct() {
                // Prevent duplicate headers during testing or multiple calls
                if (!headers_sent()) {
                    header('Location: ' . $this->url);
                    exit;
                }
            }
        };
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $base = rtrim($_ENV['APP_URL'] ?? 'http://localhost:8000', '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . \App\Core\Security::generateCsrfToken() . '">';
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return \App\Core\Security::generateCsrfToken();
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '')
    {
        return \App\Core\Session::getFlash('old_' . $key) ?? $default;
    }
}

if (!function_exists('session')) {
    function session($key = null, $default = null)
    {
        if ($key === null) {
            return \App\Core\Session::class;
        }
        // Support array: session(['key' => 'value'])
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                \App\Core\Session::set($k, $v);
            }
            return;
        }
        return \App\Core\Session::get($key, $default);
    }
}

if (!function_exists('auth')) {
    function auth()
    {
        return new class {
            public function check() { return \App\Core\Auth::check(); }
            public function user()  { return \App\Core\Auth::user(); }
            public function id()    { return \App\Core\Session::get('user_id'); }
        };
    }
}

if (!function_exists('back')) {
    function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        return new class($referer) {
            private $url;
            public function __construct($url) { $this->url = $url; }
            public function with($key, $value = null) {
                \App\Core\Session::setFlash($key, $value);
                return $this;
            }
            public function __destruct() {
                header('Location: ' . $this->url);
                exit;
            }
        };
    }
}

if (!function_exists('now')) {
    function now(): string
    {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('compact')) {
    // PHP already has compact() built-in, no override needed
}

if (!function_exists('abort')) {
    function abort(int $code = 404, string $message = '')
    {
        http_response_code($code);
        $messages = [
            404 => '404 - Halaman Tidak Ditemukan',
            403 => '403 - Akses Ditolak',
            500 => '500 - Kesalahan Server',
        ];
        die($messages[$code] ?? $message ?: 'Error ' . $code);
    }
}

if (!function_exists('route')) {
    function route(string $name, $param = null): string
    {
        return resolveDotNotationRoute($name, $param);
    }
}

if (!function_exists('request')) {
    function request($key = null, $default = null)
    {
        if ($key === null) return new \App\Core\Request();
        return (new \App\Core\Request())->input($key, $default);
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        // Minimal config helper
        $parts = explode('.', $key, 2);
        $file  = $parts[0];
        $subkey = $parts[1] ?? null;

        $configPath = __DIR__ . '/../../config/' . $file . '.php';
        if (!file_exists($configPath)) return $default;

        $config = require $configPath;
        if ($subkey === null) return $config;
        return $config[$subkey] ?? $default;
    }
}

if (!function_exists('unique_slug')) {
    /**
     * Generate slug unik untuk model tertentu.
     * Jika slug sudah ada, tambahkan sufiks -1, -2, dst.
     *
     * @param  string  $text       Teks sumber slug (misal: judul berita)
     * @param  string  $modelClass Nama kelas model Eloquent (misal: \App\Models\Berita::class)
     * @param  string  $column     Nama kolom slug di database (default: 'slug')
     * @param  int|null $ignoreId  ID record yang diabaikan saat update (agar slug milik sendiri tidak dianggap duplikat)
     * @return string
     */
    function unique_slug(string $text, string $modelClass, string $column = 'slug', $ignoreId = null): string
    {
        $slug = \Illuminate\Support\Str::slug($text);
        if (empty($slug)) {
            $slug = substr(md5(uniqid('', true)), 0, 8);
        }
        $original = $slug;
        $i = 1;
        while (
            $modelClass::where($column, $slug)
                ->when($ignoreId !== null, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}

