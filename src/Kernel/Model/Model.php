<?php

namespace Mvc\Framework\Kernel\Model;

use \PDO;

class Model
{
    private static $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];
        try {
            $this->pdo = new PDO("mysql:dbname=$db;host=$host:$port", $user, $pass);
            $this->pdo->exec("SET CHARACTER SET utf8");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
    }

    public final static function getInstance(): Model
    {
        if (self::$instance === null) {
            self::$instance = new Model;
        }
        return self::$instance;
    }

    public final function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
