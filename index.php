<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');

$method = $_SERVER['REQUEST_METHOD'];
// echo $method;

$request_uri = $_SERVER['REQUEST_URI'];
 //echo $request_uri;

$tables = ['users','accounts'];
$url = rtrim($request_uri, '/');
$url = filter_var($request_uri, FILTER_SANITIZE_URL);
$url = explode('/', $url);
// print_r($url);
$tableName = (string) $url[3];
// print_r($tableName);
if ($url[4] != null) {
    $id = (int) $url[4];
} else {
    $id = null;
}

if (in_array($tableName, $tables)) {
    // Include that api route
    include_once './classes/Database.php';
    include_once './api/user.php';
} else {
    // Set HTTP Response
	header('HTTP/1.1 404 Not Found');
	// Set HTTP Response Content Type
	header('Content-Type: application/json; charset=utf-8');

    echo json_encode(['message' => 'Page not found']);
}
