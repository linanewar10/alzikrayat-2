<?php

require_once __DIR__ . '/../core/model.php';

/**
 * User model.
 * Handles reading and writing rows in the "users" table.
 */
class User extends Model
{
    /**
     * Insert a new user.
     * The password given here must already be hashed.
     * Returns true on success, false on failure.
     */
    public function create(
        $firstName,
        $lastName,
        $email,
        $password,
        $location = null,
        $description = null,
        $occupation = null
    ) {
        $sql = "INSERT INTO users
                (first_name, last_name, email, password, location, description, occupation)
                VALUES
                (:firstName, :lastName, :email, :password, :location, :description, :occupation)";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':email' => $email,
            ':password' => $password,
            ':location' => $location,
            ':description' => $description,
            ':occupation' => $occupation
        ]);
    }

    /**
     * Find a single user by email. Used at login.
     * Returns the row (associative array) or false if not found.
     */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $statement = $this->db->prepare($sql);
        $statement->execute([':email' => $email]);
        return $statement->fetch();
    }

    /**
     * Find a single user by id.
     * Returns the row or false if not found.
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    /**
     * Count how many users are registered (used for the home page stats).
     */
    public function countAll()
    {
        $statement = $this->db->query("SELECT COUNT(*) AS total FROM users");
        $row = $statement->fetch();
        return $row['total'];
    }
}
