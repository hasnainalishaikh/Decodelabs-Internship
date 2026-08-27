<?php

header('Content-Type: application/json; charset=UTF-8');

/**
 * Send JSON API response.
 *
 * @param bool   $success
 * @param string $message
 * @param mixed  $data
 * @param int    $statusCode
 */
function sendResponse(
    bool $success,
    string $message = '',
    mixed $data = null,
    int $statusCode = 200
): never {

    http_response_code($statusCode);

    $response = [
        'success' => $success,
        'message' => $message
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }

    echo json_encode(
        $response,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    exit;
}