<?php

require_once __DIR__ . '/../config/database.php';

/**
 * Base Model.
 * Every model extends this so it gets a ready PDO connection in $this->db.
 */
class Model
{
    protected $db;

    /**
     * Build the model and open the database connection.
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }
}
