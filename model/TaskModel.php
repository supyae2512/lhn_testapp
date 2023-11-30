<?php
namespace model;

class TaskModel extends DBConnection {
    private $table = "tasks";

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll() {
        $query = "
        SELECT * FROM $this->table
        ";
        try {

            $statement = $this->connection->query($query);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;

        } catch (\PDOException $e) {
            return false;
        }
    }

    public function get($id) {
        $query = "
            SELECT * FROM $this->table WHERE id= :id ;";

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute(["id"=> $id]);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else { 
                return false;
            }

        } catch (\PDOException $e) {
            return false;
        }
    }

    public function createTask($input) {

        try {
            $query = "
            INSERT INTO $this->table 
                (title, description, assignee, status)
            VALUES
                (:title, :description, :assignee, :status);";
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ':title' => $input['title'],
                ':description' => $input['description'],
                ':assignee' => $input['assignee'],
                ':status' => isset($input['status']) ? (int) $input['status'] : 0,
            ]);

            $lastId = $this->connection->lastInsertId();
            return $lastId;

        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateTask($input) {
        try {
            $query = "
                UPDATE $this->table 
            SET 
                title = :title,
                assignee = :assignee,
                description = :description,
                status = :status
            WHERE id = :id";
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ':title' => $input['title'],
                ':description' => $input['description'],
                ':assignee' => $input['assignee'],
                ':status' => isset($input['status']) ? (int) $input['status'] : 0,
                ':id' => $input['id']
            ]);
            return $this->get($input['id']);

        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}

?>