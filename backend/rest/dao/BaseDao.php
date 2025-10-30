<?php
require_once __DIR__ . "/../config.php";

class BaseDao
{
    protected $connection;
    protected $table_name;
    protected $id_column;

    public function __construct($table_name, $id_column)
    {
        $this->table_name = $table_name;
        $this->id_column = $id_column;
        try {
            $this->connection = new PDO(
                "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT(),
                Config::DB_USER(),
                Config::DB_PASSWORD(),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->table_name);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->table_name . " WHERE {$this->id_column} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function add($entity) {
        $columns = array_keys($entity);
        $fields = implode(', ', $columns);
        $placeholders = ':' . implode(', :', $columns);
        $query = "INSERT INTO {$this->table_name} ($fields) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);
        $entity[$this->id_column] = $this->connection->lastInsertId();
        return $entity;
    }
    public function update($entity, $id) {
        $setClause = '';
        foreach (array_keys($entity) as $col) {
            $setClause .= "$col = :$col, ";
        }
        $setClause = rtrim($setClause, ', ');
        $query = "UPDATE {$this->table_name} SET $setClause WHERE {$this->id_column} = :id";
        $stmt = $this->connection->prepare($query);
        $entity['id'] = $id;
        $stmt->execute($entity);
        return $entity;
    }
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM {$this->table_name} WHERE {$this->id_column} = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}