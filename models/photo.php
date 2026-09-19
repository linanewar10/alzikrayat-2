<?php

require_once __DIR__ . '/../core/model.php';

/**
 * Photo model.
 * Handles the "photos" table. Also joins the users table when we need
 * the author's name for the gallery / detail pages.
 */
class Photo extends Model
{
    /**
     * Save a new photo record. date_time is filled by the database default.
     * Returns true on success.
     */
    public function create($userId, $fileName, $title, $description)
    {
        $sql = "INSERT INTO photos (user_id, file_name, title, description)
                VALUES (:userId, :fileName, :title, :description)";
        $statement = $this->db->prepare($sql);

        return $statement->execute([
            ':userId' => $userId,
            ':fileName' => $fileName,
            ':title' => $title,
            ':description' => $description
        ]);
    }

    /**
     * Get every photo with its author's name, newest first.
     * Returns an array of rows (may be empty).
     */
    public function getAll()
    {
        $sql = "SELECT photos.id,
                       photos.user_id,
                       photos.file_name,
                       photos.title,
                       photos.description,
                       photos.date_time,
                       users.first_name,
                       users.last_name
                FROM photos
                INNER JOIN users ON photos.user_id = users.id
                ORDER BY photos.date_time DESC";

        $statement = $this->db->query($sql);
        return $statement->fetchAll();
    }

    /**
     * Get a single photo (with author name) by its id.
     * Returns the row or false if not found.
     */
    public function findById($id)
    {
        $sql = "SELECT photos.id,
                       photos.user_id,
                       photos.file_name,
                       photos.title,
                       photos.description,
                       photos.date_time,
                       users.first_name,
                       users.last_name
                FROM photos
                INNER JOIN users ON photos.user_id = users.id
                WHERE photos.id = :id";

        $statement = $this->db->prepare($sql);
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    /**
     * Delete a photo, but only if it belongs to the given user.
     * The user_id check makes sure people can't delete other people's photos.
     * Returns true if a row was deleted.
     */
    public function delete($photoId, $userId)
    {
        $sql = "DELETE FROM photos WHERE id = :photoId AND user_id = :userId";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            ':photoId' => $photoId,
            ':userId' => $userId
        ]);

        // rowCount tells us if the delete actually removed a row
        return $statement->rowCount() > 0;
    }

    /**
     * Count all photos (used for the home page stats).
     */
    public function countAll()
    {
        $statement = $this->db->query("SELECT COUNT(*) AS total FROM photos");
        $row = $statement->fetch();
        return $row['total'];
    }
}
