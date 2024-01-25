<?php
// Database stuff
require_once "inc/conf/class-database.php";
require_once "inc/conf/class-table-creation.php";

$classDatabase                 = new ClassDatabase();
$classDatabaseConnectionObject = $classDatabase->getConnection();
$createTable                   = new ClassTableCreation($classDatabaseConnectionObject);

$createTable->createDatabaseTables();
// End of Database stuff

// REST API stuff
$token = isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : null;

$json = ['message' => null, 'token' => $token];

if ($token != 'secret_token')
{
    http_response_code(401);
    $json['message'] = 'Unauthorized access!';
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $json['message'] = 'Fetch todos';
    $json['todos']   = $classDatabase->getTodos();
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $json['message'] = 'Add';
    // Get the json data from request body
    $requestedData = json_decode(file_get_contents('php://input'), true);

    // Add new todo
    $json['todos'] = $classDatabase->addTodo($requestedData);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $json['message'] = 'Update';
    // Get the json data from request body
    $requestedData = json_decode(file_get_contents('php://input'), true);

    // Update todo
    $json['todos'] = $classDatabase->updateTodo($requestedData);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    $json['message'] = 'Delete';
    // Get the json data from request body
    $requestedData = json_decode(file_get_contents('php://input'), true);

    // Delete todo
    $json['todos']   = $classDatabase->deleteTodo($requestedData['id']);
}
else
{
    $json['message'] = 'Not yet implemented!';
}

header('Content-Type: application/json');
echo json_encode($json);