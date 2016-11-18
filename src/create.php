<?php

class create
{
    private $pdo;

    function __construct()
    {
        $data = json_decode(file_get_contents("config/config.json"));
        $this->pdo = new PDO(
            'mysql:host=' . $data->dbhost . ';dbname=' . $data->dbname,
            $data->dbuser,
            $data->dbpassword
        );
    }

    public function createDatabase($database)
    {
        $request = $this->pdo->prepare("CREATE DATABASE IF NOT EXISTS " . $database);
        $request->execute();
        if ($request->errorInfo()) throw new Exception('Error ' . $request->errorInfo()[1] . ' : ' . $request->errorInfo()[2]);
        return true;
    }

    private function createTable($table)
    {
        $request = $this->pdo->prepare("CREATE TABLE IF NOT EXISTS " . $table . "(id INTEGER);");
        $request->execute();
        if ($request->errorInfo()) throw new Exception('Error ' . $request->errorInfo()[1] . ' : ' . $request->errorInfo()[2]);
        return true;
    }

    private function createColumn($table, $column, $columnType)
    {
        $request = $this->pdo->prepare("ALTER TABLE " . $table . " ADD " . $column . $columnType);
        $request->execute();
        if ($request->errorInfo()) throw new Exception('Error ' . $request->errorInfo()[1] . ' : ' . $request->errorInfo()[2]);
        return true;
    }

    private function createEntity($class, $fields)
    {
        $content = '<?php\n class ' . $class . ' extends Entities{\n';
        foreach ($fields as $field) {
            $content .= 'private $' . $field . ';\n public function get' . ucfirst($field) . '(){\n'
                . 'return $this->' . $field . ';\n } \n'
                . 'public function set' . ucfirst($field) . '($' . $field . '){\n'
                . '$this->' . $field . ' = ' . $field . ';\n }\n';

        }
        $content .= '}\n ?>';
        file_put_contents("Entities/".ucfirst($class).".php", $content);
    }
}