<?php

namespace controllers;
use Exception;
use Model\TaskModel;

class TaskController {

    private $model;

    public function __construct() {
        $this->model = new TaskModel();
        $this->initRequest();

    }

    private function initRequest() {
        $request = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        switch ($requestMethod) {
            case 'GET':
                if (isset($_GET['id'])) $this->getTask($_GET['id']);
                $this->viewTask();
                break;
            case 'POST':
                return $this->createTask();
            case 'PUT':
                return $this->updateTask();
            default: 
            return $this->viewTask();   
        }
    }

    private function viewTask() {

        $tasks = $this->model->getAll();
        return $this->response($tasks);
    }

    private function getTask($id) {

        $tasks = $this->model->get($id);
        return $this->response($tasks);
    }

    private function createTask() {

        try {
            $title = htmlspecialchars(trim($_POST["title"]));
            $description = htmlspecialchars(trim($_POST["description"]));
            $assignee = htmlspecialchars(trim($_POST["assignee"]));
            $input = [
                "title"=> $title,
                "description"=> $description,
                "assignee" => $assignee,
                "status" => $_POST["status"]
            ];
            $this->validate($input);
            $task = $this->model->createTask($input);
            return $this->response($task, 201);
        } catch (Exception $e) {
            return $this->response($e->getMessage(), $e->getCode());
        }
    }

    private function updateTask() {

        try {
            $putData = file_get_contents('php://input');
            $requestData = json_decode($putData, true);
            $input = [];
            foreach ($requestData as $key => $value) {
                $input[$key] =  $value;
            }
            $this->validate($input);
            $task = $this->model->updateTask($input);
            return $this->response($task, 200);
        } catch (Exception $e) {
            return $this->response($e->getMessage(), $e->getCode());
        }
    }   

    private function deleteTask() {}

    private function response($data, $status = 200) {

        http_response_code($status);
        header('Content-Type: application/json');
        $data = [
            'statusCode'=> $status,
            'data'=> $data
        ];
        echo json_encode($data);
        exit();
    }

    private function validate($data) {

        $required = ['title','assignee'];
        foreach ($data as $key => $val) {
            if (in_array($key, $required) && empty($val)) throw new Exception('Invalid Request', 400);
        }
    }

}

?>