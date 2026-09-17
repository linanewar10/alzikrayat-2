<?php

/**
 * Database connection class.
 * Creates one PDO connection to the MySQL database using the settings below.
 * The default values match a normal XAMPP setup (user "root", empty password).
 */
class Database
{
    private $host = 'localhost';
    private $dbName = 'alzikrayat';
    private $username = 'root';
    private $password = '';
    private $connection;

    /**
     * Open the PDO connection and return it.
     * Throws a PDOException (caught here) if the database is unreachable.
     */
    public function connect()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
                $this->username,
                $this->password
            );

            // make PDO throw exceptions so we notice SQL errors
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            // return rows as associative arrays by default
            $this->connection->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        return $this->connection;
    }
}
