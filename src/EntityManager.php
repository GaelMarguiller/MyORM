<?php
namespace EntityManager;


abstract class Entity
{

    private $pdo;
    private $query = "";

    function __construct()
    {
        $data = json_decode(file_get_contents("/config/config.json"));
        $this->pdo = new \PDO (
            'mysql:host=' . $data->dbhost . ';dbname=' . $data->dbname,
            $data->dbuser,
            $data->dbpassword
        );
    }

    public function select($params)
    {
        $i = 0;
        foreach ($params as $value) {
            if ($i > 0) $this->query .= ", ";
            $i++;
            $this->query .= $value;
        }
        if ($this->query == "") {
            $this->query = "SELECT * ";
        } else {
            $this->query = "SELECT " . $this->query;
        }
    }

    public function from($table)
    {
        $this->query .= "FROM " . $table;
    }

    public function where($params)
    {
        if (!empty($params)) {
            $i = 0;
            foreach ($params as $value) {
                if ($i > 0) $this->query .= " AND ";
                $i++;
                $this->query .= $value;
            }
        } else {
            $this->query .= " 1 ";
        }

    }

    public function limit($limit, $offset = 0)
    {
        $this->query .= " LIMIT " . $offset . $limit;
    }

    public function orderBy($param, $type = 'ASC')
    {
        $this->query .= $param . $type;
    }

    public function find()
    {
        $resultPdo = $this->pdo->exec($this->query);
        $result = [];
        foreach ($resultPdo->fetchAll() as $value) {
            array_push($result, $value);
        }
        $this->query = "";
        return $result;
    }

    public function count()
    {
        $result = $this->find();
        return count($result);
    }

    public function save()
    {
        $array = $this->getProperties();
        $table = strtolower(get_class($this));
        $set = "";
        $id = $this->getId();
        if ($id == null) {
            $resultPdo = $this->pdo->exec("INSERT INTO $table (id) VALUES (DEFAULT);");
            $id = $this->pdo->lastInsertId();
        }
        $set = "";
        $i = 0;
        foreach ($array as $key => $value) {
            $key = str_replace("*", "", $key);
            if ($value != null) {
                if ($i > 0) $set .= ",";
                $set .= " $key = '$value'";
                $i++;
            }
        }
        $resultPdo = $this->pdo->exec("UPDATE $table SET $set WHERE id = '$id';");
        return $id;
    }

    public function delete()
    {
        $id = $this->getId();
        $table = strtolower(get_class($this));
        $resultPdo = $this->pdo->exec("DELETE FROM $table WHERE id = $id");
        return $id;
    }

    public function getProperties()
    {
        return get_object_vars($this);
    }
}