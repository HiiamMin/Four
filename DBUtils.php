<?php
include "./database.php";

class DBUtils
{
    private $connection = null;

    function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    public function select($sql, $params = [])
    {
        // Loại bỏ các dòng gỡ lỗi
        // var_dump($sql);
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        // echo "<pre>";
        // var_dump($stmt);
        // echo "</pre>";
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    public function delete($table, $where)
    {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->connection->prepare($sql);
        // Bind parameters if $where contains user input
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $fields = implode(",", $keys);
        $placeholder = ":" . implode(",:", $keys);
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholder)";
        $stmt = $this->connection->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $this->connection->lastInsertId();
    }
    public function update($table, $data, $condition)
    {
        $setClause = "";
        $params = [];
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
            $params[":$key"] = $value;
        }
        $setClause = rtrim($setClause, ", ");
        $sql = "UPDATE $table SET $setClause WHERE $condition";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
}
