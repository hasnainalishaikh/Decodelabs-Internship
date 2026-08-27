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

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$message = trim($data['message'] ?? '');

$errors = [];

if (!isValidString($name, 2, 100)) {
    $errors['name'] = 'Name must be between 2 and 100 characters.';
}

if (!isValidEmail($email)) {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!isValidString($message, 10, 5000)) {
    $errors['message'] = 'Message must be between 10 and 5000 characters.';
}

if (!empty($errors)) {
    sendResponse(
        false,
        'Validation failed.',
        ['errors' => $errors],
        400
    );
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO contact_messages (name, email, message)
         VALUES (:name, :email, :message)'
    );

    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':message' => $message
    ]);

    sendResponse(
        true,
        'Message submitted successfully.',
        [
            'id' => $pdo->lastInsertId()
        ],
        201
    );

} catch (PDOException $e) {

    sendResponse(
        false,
        'Failed to submit message.',
        null,
        500
    );
}