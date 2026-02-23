<?php
namespace Models;  
class Database {
    private $host = "localhost";      // hôte
    private $dbname; // nom de la base
    private $username;       // utilisateur MySQL
    private $password;           // mot de passe MySQL
    private $port;               // port MySQL
    private $conn;                    // objet PDO
    private $config = []; 

 
    public function __construct()
    {
        // Charger la configuration
        $this->config = require dirname(__DIR__). DIRECTORY_SEPARATOR .'config'. DIRECTORY_SEPARATOR.'config.php';
    }

    /**
     * Connexion à la base de données
     */

    public function connect() {
        // Réutiliser la connexion existante si elle existe
        if ($this->conn !== null) {
            return $this->conn;
        }
       
        $this->dbname = $this->config['DB_NAME'];
        $this->username = $this->config['DB_USER'];
        $this->password = $this->config['DB_PASSWORD'];
        $this->port = $this->config['DB_PORT'];
        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname . ";charset=utf8mb4";
            $this->conn = new \PDO($dsn, $this->username, $this->password);
            // Options de sécurité et performance
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
        return $this->conn;
    }

    /**
     * Fermer la connexion
     */
    public function disconnect() {
        $this->conn = null;
    }

    /**
     * Exécuter une requête avec paramètres (SELECT, INSERT, UPDATE, DELETE)
     */
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Récupérer plusieurs enregistrements
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un seul enregistrement
     */
    public function fetchOne($sql, $params = []) {
       
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Exécuter une requête INSERT/UPDATE/DELETE
     */
    public function execute($sql, $params = []) {
        try {
            // S'assurer que la connexion est établie
            if ($this->conn === null) {
                $this->connect();
            }
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (\PDOException $e) {
            error_log('Erreur PDO execute: ' . $e->getMessage() . ' - SQL: ' . $sql);
            throw $e;
        }
    }
    
    /**
     * Obtenir la connexion PDO (pour les transactions)
     */
    public function getConnection() {
        if ($this->conn === null) {
            $this->connect();
        }
        return $this->conn;
    }

    /**
     * Récupérer le dernier ID inséré
     */
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}
?>