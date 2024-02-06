<?php

class Database {
    
    public $db;
    public $statement;

    public function __construct($config) {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
        $this->db = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function query($sql, $params = []) {
        $this->statement = $this->db->prepare($sql);
        $this->statement->execute($params);
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($sql, $params = []) {
        $this->statement = $this->db->prepare($sql);
        return $this->statement->execute($params);
    }

    public function update($sql, $params = []) {
        $this->statement = $this->db->prepare($sql);
        return $this->statement->execute($params);
    }

    /* public function verify($sql, $params = []) {
        $this->statement = $this->db->prepare($sql);
        $this->statement->execute($params);
        return $this->statement->fetchColumn();
    } */
}
