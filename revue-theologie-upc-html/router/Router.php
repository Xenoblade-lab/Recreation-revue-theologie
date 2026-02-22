<?php
namespace Router;

/**
 * Routeur simple (sans dépendance Composer).
 * Correspondance URL → callback ou [Controller::class, 'method'].
 */
class Router
{
    private static array $routes = [];
    private static ?string $basePath = null;

    public static function setBasePath(string $path): void
    {
        self::$basePath = rtrim($path, '/');
    }

    public static function get(string $pattern, $target, string $name = ''): void
    {
        self::add('GET', $pattern, $target, $name);
    }

    public static function post(string $pattern, $target, string $name = ''): void
    {
        self::add('POST', $pattern, $target, $name);
    }

    private static function add(string $method, string $pattern, $target, string $name): void
    {
        self::$routes[] = [
            'method'   => $method,
            'pattern'  => $pattern,
            'target'   => $target,
            'name'     => $name,
        ];
    }

    /**
     * Retourne l'URI actuelle (sans base path).
     */
    public static function getCurrentUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        if (($q = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $q);
        }
        $uri = '/' . trim($uri, '/');
        if (self::$basePath !== null && self::$basePath !== '' && strpos($uri, self::$basePath) === 0) {
            $uri = substr($uri, strlen(self::$basePath)) ?: '/';
        }
        return $uri;
    }

    /**
     * Convertit un pattern du type /article/[i:id] en regex et extrait les paramètres.
     */
    private static function matchPattern(string $pattern, string $uri): ?array
    {
        // Remplacer [i:id] et [s:slug] avant preg_quote
        $regex = preg_replace('#\[i:(\w+)\]#', '<<INT:$1>>', $pattern);
        $regex = preg_replace('#\[s:(\w+)\]#', '<<STR:$1>>', $regex);
        $regex = preg_quote($regex, '#');
        $regex = preg_replace('#<<INT:(\w+)>>#', '(?P<$1>\d+)', $regex);
        $regex = preg_replace('#<<STR:(\w+)>>#', '(?P<$1>[^/]+)', $regex);
        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $m)) {
            $params = [];
            foreach ($m as $k => $v) {
                if (!is_int($k)) {
                    $params[$k] = $v;
                }
            }
            return $params;
        }
        return null;
    }

    public static function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri    = self::getCurrentUri();

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $params = self::matchPattern($route['pattern'], $uri);
            if ($params !== null) {
                $target = $route['target'];
                if (is_callable($target)) {
                    $target($params);
                    return;
                }
                if (is_array($target) && count($target) === 2) {
                    [$class, $methodName] = $target;
                    $ctrl = new $class();
                    $ctrl->$methodName($params);
                    return;
                }
            }
        }

        http_response_code(404);
        if (self::wantsJson()) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Route introuvable']);
            return;
        }
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>404</title></head><body><h1>Page introuvable</h1></body></html>';
    }

    private static function wantsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return strpos($accept, 'application/json') !== false;
    }

    /**
     * Génère une URL pour une route (pour les vues).
     */
    public static function url(string $path): string
    {
        $path = '/' . ltrim($path, '/');
        if (self::$basePath !== null && self::$basePath !== '') {
            return self::$basePath . $path;
        }
        return $path;
    }
}
