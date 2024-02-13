<?php

class Database
{

    public $db;
    public $statement;

    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
        $this->db = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function query($sql, $params = [])
    {
        $this->statement = $this->db->prepare($sql);
        $this->statement->execute($params);
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function queryAll($sql, $params = [])
    {
        $this->statement = $this->db->prepare($sql);
        $this->statement->execute($params);
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($sql, $params = [])
    {
        $this->statement = $this->db->prepare($sql);
        $result = $this->statement->execute($params);
        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($sql, $params = [])
    {
        $this->statement = $this->db->prepare($sql);
        $result = $this->statement->execute($params);
        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function delete($sql, $params = [])
    {
        $this->statement = $this->db->prepare($sql);
        $result = $this->statement->execute($params);
        return $result;
    }

    public function updateQr($sql, $params = [])
    {
        $this->statement = $this->db->prepare($sql);
        $result = $this->statement->execute($params);
        return $result;
    }
}
