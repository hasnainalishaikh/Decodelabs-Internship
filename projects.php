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

// Check if a project ID was provided
if (isset($_GET['id'])) {

    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    // Validate ID
    if ($id === false || $id <= 0) {
        sendResponse(
            false,
            'Invalid project ID.',
            null,
            400
        );
    }

    try {
        $stmt = $pdo->prepare(
            'SELECT id, title, description, image, category, created_at
             FROM projects
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $project = $stmt->fetch();

        // Project not found
        if (!$project) {
            sendResponse(
                false,
                'Project not found.',
                null,
                404
            );
        }

        sendResponse(
            true,
            'Project fetched successfully',
            $project,
            200
        );

    } catch (PDOException $e) {

        sendResponse(
            false,
            'Failed to fetch project.',
            null,
            500
        );
    }
}

// No ID → fetch all projects

try {
    $stmt = $pdo->query(
        'SELECT id, title, description, image, category, created_at
         FROM projects
         ORDER BY id DESC'
    );

    $projects = $stmt->fetchAll();

    sendResponse(
        true,
        'Projects fetched successfully',
        $projects,
        200
    );

} catch (PDOException $e) {

    sendResponse(
        false,
        'Failed to fetch projects.',
        null,
        500
    );
}