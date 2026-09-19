<?php

require_once __DIR__ . '/../core/model.php';

/**
 * Comment model.
 * Handles the "comments" table and joins users to show who wrote each comment.
 */
class Comment extends Model
{
    /**
     * Save a new comment. date_time is filled by the database default.
     * Returns true on success.
     */
    public function create($photoId, $userId, $comment)
    {
        $sql = "INSERT INTO comments (photo_id, user_id, comment)
                VALUES (:photoId, :userId, :comment)";
        $statement = $this->db->prepare($sql);

        return $statement->execute([
            ':photoId' => $photoId,
            ':userId' => $userId,
            ':comment' => $comment
        ]);
    }

    /**
     * Get all comments for one photo (oldest first) with the writer's name.
     * Returns an array of rows (may be empty).
     */
    public function getByPhotoId($photoId)
    {
        $sql = "SELECT comments.id,
                       comments.comment,
                       comments.date_time,
                       users.first_name,
                       users.last_name
                FROM comments
                INNER JOIN users ON comments.user_id = users.id
                WHERE comments.photo_id = :photoId
                ORDER BY comments.date_time ASC";

        $statement = $this->db->prepare($sql);
        $statement->execute([':photoId' => $photoId]);
        return $statement->fetchAll();
    }

    /**
     * Count all comments (used for the home page stats).
     */
    public function countAll()
    {
        $statement = $this->db->query("SELECT COUNT(*) AS total FROM comments");
        $row = $statement->fetch();
        return $row['total'];
    }
}
