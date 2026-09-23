<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/comment.php';

/**
 * Comment controller.
 * Stores a new comment on a photo. Only logged in users may comment.
 */
class CommentController extends Controller
{
    /**
     * Save a comment for photo $photoId, then go back to that photo page
     * so the new comment shows up right away.
     */
    public function store($photoId)
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $commentText = trim($_POST['comment'] ?? '');

        // server side check: a comment can't be empty
        if ($commentText === '') {
            $this->redirect('/photo/' . $photoId);
        }

        $commentModel = new Comment();
        $commentModel->create($photoId, $_SESSION['user_id'], $commentText);

        $this->redirect('/photo/' . $photoId);
    }
}
