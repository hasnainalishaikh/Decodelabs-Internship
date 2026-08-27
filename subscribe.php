<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/validation.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(
        false,
        'Method not allowed. Use POST.',
        null,
        405
    );
}

$data = getJsonInput();

$email = trim($data['email'] ?? '');

if (!isValidEmail($email)) {
    sendResponse(
        false,
        'Please provide a valid email address.',
        null,
        400
    );
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO subscribers (email)
         VALUES (:email)'
    );

    $stmt->execute([
        ':email' => $email
    ]);

    sendResponse(
        true,
        'Successfully subscribed.',
        [
            'id' => $pdo->lastInsertId()
        ],
        201
    );

} catch (PDOException $e) {

    if ($e->getCode() === '23000') {
        sendResponse(
            false,
            'This email is already subscribed.',
            null,
            409
        );
    }

    sendResponse(
        false,
        'Failed to subscribe.',
        null,
        500
    );
}