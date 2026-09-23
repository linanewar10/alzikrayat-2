<?php

/**
 * Front controller (single entry point).
 * Every request comes through here. We start the session, set up the base
 * URL, register all the routes and then let the Router dispatch the request.
 */

session_start();

require_once __DIR__ . '/../core/router.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/photoController.php';
require_once __DIR__ . '/../controllers/commentController.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/photo.php';
require_once __DIR__ . '/../models/comment.php';

/*
 * Work out the base folder the app is running from (e.g. /alzikrayat/public).
 * Doing it automatically means the links keep working whether the project is
 * opened through XAMPP or through the PHP built-in server.
 */
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

/**
 * Return the logged in user row, or null if nobody is logged in.
 * The navbar uses this to decide what to show.
 */
function current_user()
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $userModel = new User();
    return $userModel->findById($_SESSION['user_id']);
}

$router = new Router();

// ---- Home ----
$router->get('/', function () {
    // small statistics for the landing page
    $stats = [
        'users' => (new User())->countAll(),
        'photos' => (new Photo())->countAll(),
        'comments' => (new Comment())->countAll()
    ];
    // a few of the newest photos to show on the home page
    $recentPhotos = array_slice((new Photo())->getAll(), 0, 3);

    require __DIR__ . '/../views/layout/header.php';
    require __DIR__ . '/../views/home.php';
    require __DIR__ . '/../views/layout/footer.php';
});

// ---- Auth ----
$router->get('/register', function () {
    (new AuthController())->showRegister();
});
$router->post('/register', function () {
    (new AuthController())->register($_POST);
});
$router->get('/login', function () {
    (new AuthController())->showLogin();
});
$router->post('/login', function () {
    (new AuthController())->login($_POST);
});
$router->get('/logout', function () {
    (new AuthController())->logout();
});

// ---- Photos ----
$router->get('/photos', function () {
    (new PhotoController())->index();
});
$router->get('/photo/create', function () {
    (new PhotoController())->create();
});
$router->post('/photo/store', function () {
    (new PhotoController())->store();
});
$router->get('/photo/{id}', function ($id) {
    (new PhotoController())->show($id);
});
$router->get('/photo/{id}/delete', function ($id) {
    (new PhotoController())->delete($id);
});

// ---- Comments ----
$router->post('/photo/{id}/comment', function ($id) {
    (new CommentController())->store($id);
});

$router->dispatch();
