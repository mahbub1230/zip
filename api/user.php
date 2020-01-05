<?php
require_once "./classes/user.php";
function deliver_response($response)
{
    // Define HTTP responses
    $http_response_code = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Duplicate',
        202 => 'Income less than $1000',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    // Set HTTP Response
    header('HTTP/1.1 ' . $response['status'] . ' ' . $http_response_code[$response['status']]);
    // Set HTTP Response Content Type
    header('Content-Type: application/json; charset=utf-8');
    // Format data into a JSON response
    $json_response = json_encode($response['data']);
    // Deliver formatted data
    echo $json_response;

    exit;
}

// Set default HTTP response of 'Not Found'
$response['status'] = 404;
$response['data'] = NULL;
$user = new user();
if ($method == 'GET') {
    if ($tableName == "users") {
        if ($id) {
            $response = $user->getUser($id, $tableName);
        } else {
            $response = $user->getAllUsers($tableName);
        }
    } else {
        if ($id) {
            $response = $user->getAccount($id, $tableName);
        } else {
            $response = $user->getAllAccounts($tableName);
        }
    }
} elseif ($method == 'POST') {
    if ($tableName == "users") {
        $response = $user->insertUser($_POST, $id, $tableName);
    } else {
        $response = $user->createAccount($_POST, $id, $tableName);
    }
}
deliver_response($response);
?>
