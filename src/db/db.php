<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'mobileapps_2026B_madiba_quansah');

if (! isset($_SERVER['SERVER_NAME'])) {
    if (getenv('PWD') != null && getenv('LOCAL_SYNC') == null) {
        define('DB_USER', 'madiba.quansah');
        define('DB_PASS', 'mads123');
    } else {
        define('DB_USER', 'madiba');
        define('DB_PASS', 'madiba');
    }
} else {
    if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1' || getenv('LOCAL_SYNC') != null) {
        define('DB_USER', 'madiba');
        define('DB_PASS', 'madiba');
    } else {
        define('DB_USER', 'madiba.quansah');
        define('DB_PASS', 'mads123');
    }
}

define('DB_FMT', 'utf8mb4');
define('DB_ATTR', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_FMT);
// Database connection options
define('DB_OPTS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
]);

/**
 * Database connection class
 * Encapsulates a PDO connection
 */
class Database
{
    private ?PDO $conn = null;

    /**
     * Constructor with optional seeding
     */
    public function __construct(bool $seed = true)
    {
        try {
            $this->conn = new PDO(DB_ATTR, DB_USER, DB_PASS, DB_OPTS);
            if ($seed) {
                $setup = file_get_contents(__DIR__ . '/./db.sql');
                $this->conn->exec($setup);
            }
        } catch (PDOException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['err' => $e->getMessage()]);
            exit(1);
        }
    }

    /**
     * Get the connection
     */
    public function Conn(): PDO
    {
        if ($this->conn == null) {
            throw new Exception('Database connection not established');
        }

        return $this->conn;
    }
}

// Establish a connection and export it as a global variable
/** @var PDO $db */
$db = (new Database(false))->Conn();
