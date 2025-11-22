<?php
namespace App\Database;

use PDO;
use PDOException;

class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct() {

    $host = 'localhost';
    $dbname = 'easyged';
    $user = 'easyged';
    $password = 'easyged';

    try {
        $this->connection = new PDO(
            "pgsql:host=$host;dbname=$dbname",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        echo "✅ Connexion à la base de données réussie !\n";
    } catch (PDOException $e) {
        die("❌ Erreur de connexion : " . $e->getMessage() . "\n");
    }
}
public static function getInstance(): Database {
    if (self::$instance === null) {
        self::$instance = new Database();
    }
    return self::$instance;
}

public function getConnection(): PDO {
    return $this->connection;
}

private function __clone() {}
}