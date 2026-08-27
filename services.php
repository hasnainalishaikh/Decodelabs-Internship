<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(
        false,
        'Method not allowed. Use GET.',
        null,
        405
    );
}

try {
    $stmt = $pdo->query(
        'SELECT id, title, description, icon, created_at
         FROM services
         ORDER BY id DESC'
    );

    $services = $stmt->fetchAll();

    sendResponse(
        true,
        'Services fetched successfully',
        $services,
        200
    );

} catch (PDOException $e) {

    sendResponse(
        false,
        'Failed to fetch services',
        null,
        500
    );
}