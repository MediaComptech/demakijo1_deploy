<?php

namespace App\Core;

use eftec\bladeone\BladeOne;

/**
 * Kelas ViewMessageBag untuk mensimulasikan MessageBag Laravel di View.
 */
class ViewMessageBag
{
    private array $messages = [];

    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }

    public function any(): bool
    {
        return count($this->messages) > 0;
    }

    public function all(): array
    {
        $flat = [];
        foreach ($this->messages as $field => $msgs) {
            if (is_array($msgs)) {
                foreach ($msgs as $m) {
                    $flat[] = $m;
                }
            } else {
                $flat[] = $msgs;
            }
        }
        return $flat;
    }

    public function has(string $key): bool
    {
        return isset($this->messages[$key]) && !empty($this->messages[$key]);
    }

    public function first(string $key): string
    {
        if ($this->has($key)) {
            $msgs = $this->messages[$key];
            return is_array($msgs) ? ($msgs[0] ?? '') : $msgs;
        }
        return '';
    }

    public function get(string $key): array
    {
        if ($this->has($key)) {
            $msgs = $this->messages[$key];
            return is_array($msgs) ? $msgs : [$msgs];
        }
        return [];
    }

    public function isNotEmpty(): bool
    {
        return $this->any();
    }

    /**
     * Magic getter untuk mendukung named error bags (e.g. $errors->updatePassword->get(...))
     */
    public function __get(string $name)
    {
        return $this;
    }
}

/**
 * Kelas View
 * 
 * Wrapper untuk Template Engine BladeOne.
 */
class View
{
    private static $blade;

    /**
     * Inisialisasi BladeOne
     */
    public static function init()
    {
        $viewsPath = __DIR__ . '/../Views';
        $cachePath = __DIR__ . '/../../storage/cache';

        // Buat folder cache jika belum ada
        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0777, true);
        }

        // Mode pengembangan: MODE_DEBUG, Mode produksi: MODE_AUTO
        $mode = ($_ENV['APP_ENV'] ?? 'local') === 'production' ? BladeOne::MODE_AUTO : BladeOne::MODE_DEBUG;

        self::$blade = new BladeOne($viewsPath, $cachePath, $mode);
    }

    /**
     * Render template
     * 
     * @param string $view Nama file view (contoh: 'home.index')
     * @param array $data Data untuk dikirim ke view
     */
    public static function render($view, $data = [])
    {
        try {
            // Ambil errors dari session flash jika ada
            $sessionErrors = \App\Core\Session::getFlash('errors') ?? [];
            if (!is_array($sessionErrors)) {
                $sessionErrors = [$sessionErrors];
            }

            if (!isset($data['errors'])) {
                $data['errors'] = new ViewMessageBag($sessionErrors);
            }

            echo self::$blade->run($view, $data);
        } catch (\Exception $e) {
            echo "Error rendering view [{$view}]: " . $e->getMessage();
        }
    }
}
