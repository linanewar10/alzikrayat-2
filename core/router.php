<?php

/**
 * Router.
 * A small hand-written router. Routes are registered with get()/post()
 * and matched inside dispatch() using regular expressions so that dynamic
 * parts like /photo/{id} can be captured and passed to the handler.
 */
class Router
{
    private $routes = [];

    /**
     * Register a GET route.
     * $path    e.g. "/photo/{id}"
     * $handler a function to run when the route matches
     */
    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Register a POST route.
     */
    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Look at the current request, find a matching route and call its handler.
     * Captured URL parameters (like the id in /photo/5) are passed as arguments.
     * Sends a 404 response if nothing matches.
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // remove the app base folder (e.g. /alzikrayat/public) so routes stay clean
        if (defined('BASE_URL') && BASE_URL !== '' && strpos($uri, BASE_URL) === 0) {
            $uri = substr($uri, strlen(BASE_URL));
        }
        if ($uri === '' || $uri === false) {
            $uri = '/';
        }

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            // turn {id} into a regex group that matches anything except a slash
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // drop the full match, keep only the captured params
                return call_user_func_array($handler, $matches);
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }
}
