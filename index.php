<?php 

$token = $_SERVER['HTTP_TOKEN'];

$json = ['message' => null, 'token' => $token];

if ($token != 'secret_token') {
    http_response_code(401);
    $json['message'] = 'Unauthorized access!';
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $json['message'] = 'Fetch todos';
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json['message'] = 'Add';
}
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $json['message'] = 'Update';
}
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $json['message'] = 'Delete';
}
else {
    $json['message'] = 'Not yet implemented!';
}

header('Content-Type: application/json');
echo json_encode($json);