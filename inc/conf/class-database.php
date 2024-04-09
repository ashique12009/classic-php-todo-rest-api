<?php

class ClassDatabase
{
    private $host          = "localhost";
    private $database_name = "local-php-todo-crud";
    private $username      = "root";
    private $password      = "password";
    public $connection;

    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
        }
        catch (PDOException $exception)
        {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->connection;
    }

    public function getTodos()
    {
        $query     = "SELECT * FROM todos";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTodo($requestedData)
    {
        $title     = isset($requestedData['title']) ? $requestedData['title'] : 'No title';
        $query     = "INSERT INTO todos SET title=:title";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':title', $title);

        if ($statement->execute())
        {
            return $this->getTodos();
        }
        else
        {
            // Output the error information for debugging
            print_r($statement->errorInfo());
            return false; // Return false to indicate an error
        }
    }

    public function updateTodo($requestedData)
    {
        $title     = isset($requestedData['title']) ? $requestedData['title'] : 'No title';
        $status    = isset($requestedData['status']) ? $requestedData['status'] : 0;
        $id        = $requestedData['id'];

        if (!is_numeric($id) || $id <= 0 || $id == '') {
            return $this->getTodos();
        }

        // Check if id is valid
        $query     = "SELECT * FROM todos WHERE id=:id";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $id_data = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (count($id_data) == 0) {
            return $this->getTodos();
        }

        if (($status != 0 && $status != 1) || !is_numeric($status)) {
            $status = 0;
        }

        $query     = "UPDATE todos SET title=:title, status=:status WHERE id=:id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':status', $status);
        $statement->bindParam(':id', $requestedData['id']);
        $statement->execute();
        return $this->getTodos();
    }

    public function deleteTodo($id)
    {
        // Check if id is valid
        $query     = "SELECT * FROM todos WHERE id=:id";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $id_data = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (count($id_data) == 0) {
            return $this->getTodos();
        }
        
        $query     = "DELETE FROM todos WHERE id=:id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $this->getTodos();
    }
}