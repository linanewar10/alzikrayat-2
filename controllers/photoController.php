<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/photo.php';
require_once __DIR__ . '/../models/comment.php';

/**
 * Photo controller.
 * Handles the gallery, the upload form, storing an upload, showing one photo
 * with its comments, and deleting a photo (owner only).
 */
class PhotoController extends Controller
{
    // where uploaded images are saved on disk
    private $uploadDirectory;

    public function __construct()
    {
        $this->uploadDirectory = __DIR__ . '/../public/images/uploads/';
    }

    /**
     * Show the gallery. Reads an optional ?grid= value so the user can
     * switch between display styles (3 columns, 4 columns or a list).
     */
    public function index()
    {
        $photoModel = new Photo();
        $photos = $photoModel->getAll();

        // only allow the styles we actually support, default to 3 columns
        $grid = $_GET['grid'] ?? 'grid-3';
        if (!in_array($grid, ['grid-3', 'grid-4', 'list'])) {
            $grid = 'grid-3';
        }

        $this->view('photos/index', [
            'photos' => $photos,
            'grid' => $grid
        ]);
    }

    /**
     * Show the upload form. Only logged in users may upload.
     */
    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $this->view('photos/create', ['errors' => [], 'old' => []]);
    }

    /**
     * Handle the uploaded file: validate it, move it into the uploads folder
     * with a unique name, then save the metadata row.
     */
    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $file = $_FILES['photo'] ?? null;

        $errors = [];

        if ($title === '' || strlen($title) > 200) {
            $errors[] = "Title is required (max 200 characters).";
        }

        // make sure a file was actually uploaded without an error
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Please choose an image to upload.";
        } else {
            // getimagesize returns false if the file is not a real image
            $imageInfo = getimagesize($file['tmp_name']);
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if ($imageInfo === false || !in_array($imageInfo['mime'], $allowed)) {
                $errors[] = "The file must be a JPG, PNG, GIF or WEBP image.";
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $errors[] = "The image is too large (max 5 MB).";
            }
        }

        if (!empty($errors)) {
            $this->view('photos/create', [
                'errors' => $errors,
                'old' => ['title' => $title, 'description' => $description]
            ]);
            return;
        }

        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0777, true);
        }

        // build a unique file name so two uploads never overwrite each other
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = 'photo_' . time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
        $filePath = $this->uploadDirectory . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $this->view('photos/create', [
                'errors' => ["Could not save the file, please try again."],
                'old' => ['title' => $title, 'description' => $description]
            ]);
            return;
        }

        $photoModel = new Photo();
        $photoModel->create(
            $_SESSION['user_id'],
            $fileName,
            $title,
            $description !== '' ? $description : null
        );

        $this->redirect('/photos');
    }

    /**
     * Show one photo in detail together with its comments.
     */
    public function show($id)
    {
        $photoModel = new Photo();
        $photo = $photoModel->findById($id);

        if (!$photo) {
            http_response_code(404);
            $this->view('photos/not_found');
            return;
        }

        $commentModel = new Comment();
        $comments = $commentModel->getByPhotoId($id);

        $this->view('photos/show', [
            'photo' => $photo,
            'comments' => $comments
        ]);
    }

    /**
     * Delete a photo. Only the owner is allowed, so we check the photo's
     * user_id against the logged in user before deleting the row and the file.
     */
    public function delete($id)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $photoModel = new Photo();
        $photo = $photoModel->findById($id);

        // photo must exist AND belong to the current user
        if (!$photo || $photo['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "You are not allowed to delete this photo.";
            return;
        }

        // delete the database row first (with the ownership check inside the query too)
        $deleted = $photoModel->delete($id, $_SESSION['user_id']);

        // then remove the physical file so it doesn't stay on the server
        $filePath = $this->uploadDirectory . $photo['file_name'];
        if ($deleted && file_exists($filePath)) {
            unlink($filePath);
        }

        $this->redirect('/photos');
    }
}
