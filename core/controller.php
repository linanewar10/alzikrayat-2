<?php

/**
 * Base Controller.
 * Gives child controllers two small helpers: view() to render a page inside
 * the shared header/footer layout, and redirect() to send the browser
 * to another route.
 */
class Controller
{
    /**
     * Render a view file wrapped by the common layout.
     * $view  path under views/ without the extension, e.g. "photos/index"
     * $data  associative array; each key becomes a variable inside the view
     */
    protected function view($view, $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Redirect the browser to a path relative to the app base URL.
     * Stops the script right after so nothing else runs.
     */
    protected function redirect($path)
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }
}
