<?php

namespace Illuminate\Support\Facades;

use Illuminate\Database\Capsule\Manager as Capsule;

class Storage
{
    private static function getBase(): string
    {
        return __DIR__ . '/../../../public/storage';
    }

    public static function disk($name): static
    {
        return new static();
    }

    public static function delete($path): bool
    {
        $fullPath = self::getBase() . '/' . ltrim((string)$path, '/');
        if (file_exists($fullPath)) {
            return (bool)@unlink($fullPath);
        }
        return false;
    }

    public static function put($path, $contents): bool
    {
        $fullPath = self::getBase() . '/' . ltrim((string)$path, '/');
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return file_put_contents($fullPath, $contents) !== false;
    }

    public static function exists($path): bool
    {
        $fullPath = self::getBase() . '/' . ltrim((string)$path, '/');
        return file_exists($fullPath);
    }

    public static function url($path): string
    {
        $base = rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/');
        return $base . '/storage/' . ltrim((string)$path, '/');
    }
}

class Cache
{
    public static function remember($key, $ttl, $callback)
    {
        return $callback();
    }

    public static function forget($key): bool
    {
        return true;
    }

    public static function get($key, $default = null)
    {
        return $default;
    }
}

class Log
{
    public static function error($message, $context = []): void
    {
        error_log('[ERROR] ' . $message . (!empty($context) ? ' ' . json_encode($context) : ''));
    }

    public static function info($message, $context = []): void
    {
        error_log('[INFO] ' . $message . (!empty($context) ? ' ' . json_encode($context) : ''));
    }

    public static function warning($message, $context = []): void
    {
        error_log('[WARNING] ' . $message . (!empty($context) ? ' ' . json_encode($context) : ''));
    }
}

class DB extends Capsule
{
    // Inherits all static Capsule methods: table(), beginTransaction(), etc.
}

class File
{
    public static function exists($path): bool
    {
        return file_exists($path);
    }

    public static function delete($path): bool
    {
        if (file_exists($path)) {
            return (bool)@unlink($path);
        }
        return false;
    }

    public static function put($path, $contents): bool
    {
        return file_put_contents($path, $contents) !== false;
    }
}

class Hash
{
    public static function make(string $value, array $options = []): string
    {
        $cost = $options['rounds'] ?? 10;
        return password_hash($value, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    public static function check(string $value, string $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
    }

    public static function needsRehash(string $hashedValue, array $options = []): bool
    {
        $cost = $options['rounds'] ?? 10;
        return password_needs_rehash($hashedValue, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
}