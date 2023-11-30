<?php
use model\DBConnection;

require_once "bootstrap.php";

$DBConnection = new DBConnection();

$statement = "
    CREATE TABLE tasks (
        id INT NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        description text NULL,
        assignee VARCHAR(100) DEFAULT NULL,
        status boolean DEFAULT false COMMENT '1: completed, 0: undone',
        PRIMARY KEY (id)
    ) ENGINE=INNODB;

    INSERT INTO tasks
        (title, assignee, description)
    VALUES
        ('test 1', 'John', 'test 1 descp'),
        ('test 2', 'Mary', 'test 2 descp'),
        ('test 3', 'Rose', 'test 3 descp'),
        ('test 4', 'Diana', 'test 4 descp'),
        ('test 5', 'Amy', 'test 5 descp'); ";

try {
    
    $connection = $DBConnection->connection();
    $createTable = $connection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}


?>