<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/response.php';
require_once __DIR__ . '/../includes/validation.php';

/*
|--------------------------------------------------------------------------
| GET - READ
|--------------------------------------------------------------------------
| GET /api/projects.php
| GET /api/projects.php?id=1
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Get single project
    if (isset($_GET['id'])) {

        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

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
                'Project fetched successfully.',
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

    // Get all projects
    try {

        $stmt = $pdo->query(
            'SELECT id, title, description, image, category, created_at
             FROM projects
             ORDER BY id DESC'
        );

        $projects = $stmt->fetchAll();

        sendResponse(
            true,
            'Projects fetched successfully.',
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
}


/*
|--------------------------------------------------------------------------
| POST - CREATE
|--------------------------------------------------------------------------
| POST /api/projects.php
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = getJsonInput();

    $title = trim($data['title'] ?? '');
    $description = trim($data['description'] ?? '');
    $image = trim($data['image'] ?? '');
    $category = trim($data['category'] ?? '');

    $errors = [];

    // Validate title
    if (!isValidString($title, 2, 150)) {
        $errors['title'] = 'Title must be between 2 and 150 characters.';
    }

    // Validate description
    if (!isValidString($description, 10, 5000)) {
        $errors['description'] = 'Description must be between 10 and 5000 characters.';
    }

    // Validate image if provided
    if ($image !== '' && !isValidString($image, 1, 255)) {
        $errors['image'] = 'Image must not exceed 255 characters.';
    }

    // Validate category if provided
    if ($category !== '' && !isValidString($category, 1, 100)) {
        $errors['category'] = 'Category must not exceed 100 characters.';
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
            'INSERT INTO projects
                (title, description, image, category)
             VALUES
                (:title, :description, :image, :category)'
        );

        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':image' => $image !== '' ? $image : null,
            ':category' => $category !== '' ? $category : null
        ]);

        sendResponse(
            true,
            'Project created successfully.',
            [
                'id' => (int) $pdo->lastInsertId()
            ],
            201
        );

    } catch (PDOException $e) {

        sendResponse(
            false,
            'Failed to create project.',
            null,
            500
        );
    }
}


/*
|--------------------------------------------------------------------------
| PUT / PATCH - UPDATE
|--------------------------------------------------------------------------
| PUT /api/projects.php?id=1
| PATCH /api/projects.php?id=1
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'PUT' ||
    $_SERVER['REQUEST_METHOD'] === 'PATCH'
) {

    if (!isset($_GET['id'])) {

        sendResponse(
            false,
            'Project ID is required.',
            null,
            400
        );
    }

    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id === false || $id <= 0) {

        sendResponse(
            false,
            'Invalid project ID.',
            null,
            400
        );
    }

    $data = getJsonInput();

    $title = trim($data['title'] ?? '');
    $description = trim($data['description'] ?? '');
    $image = trim($data['image'] ?? '');
    $category = trim($data['category'] ?? '');

    $errors = [];

    // Validate title
    if (!isValidString($title, 2, 150)) {
        $errors['title'] = 'Title must be between 2 and 150 characters.';
    }

    // Validate description
    if (!isValidString($description, 10, 5000)) {
        $errors['description'] = 'Description must be between 10 and 5000 characters.';
    }

    // Validate image if provided
    if ($image !== '' && !isValidString($image, 1, 255)) {
        $errors['image'] = 'Image must not exceed 255 characters.';
    }

    // Validate category if provided
    if ($category !== '' && !isValidString($category, 1, 100)) {
        $errors['category'] = 'Category must not exceed 100 characters.';
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

        // Check if project exists
        $checkStmt = $pdo->prepare(
            'SELECT id
             FROM projects
             WHERE id = :id
             LIMIT 1'
        );

        $checkStmt->execute([
            ':id' => $id
        ]);

        if (!$checkStmt->fetch()) {

            sendResponse(
                false,
                'Project not found.',
                null,
                404
            );
        }

        // Update project
        $stmt = $pdo->prepare(
            'UPDATE projects
             SET title = :title,
                 description = :description,
                 image = :image,
                 category = :category
             WHERE id = :id'
        );

        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':image' => $image !== '' ? $image : null,
            ':category' => $category !== '' ? $category : null,
            ':id' => $id
        ]);

        sendResponse(
            true,
            'Project updated successfully.',
            [
                'id' => $id
            ],
            200
        );

    } catch (PDOException $e) {

        sendResponse(
            false,
            'Failed to update project.',
            null,
            500
        );
    }
}


/*
|--------------------------------------------------------------------------
| DELETE - DELETE
|--------------------------------------------------------------------------
| DELETE /api/projects.php?id=1
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    if (!isset($_GET['id'])) {

        sendResponse(
            false,
            'Project ID is required.',
            null,
            400
        );
    }

    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id === false || $id <= 0) {

        sendResponse(
            false,
            'Invalid project ID.',
            null,
            400
        );
    }

    try {

        // Check if project exists
        $checkStmt = $pdo->prepare(
            'SELECT id
             FROM projects
             WHERE id = :id
             LIMIT 1'
        );

        $checkStmt->execute([
            ':id' => $id
        ]);

        if (!$checkStmt->fetch()) {

            sendResponse(
                false,
                'Project not found.',
                null,
                404
            );
        }

        // Delete project
        $stmt = $pdo->prepare(
            'DELETE FROM projects
             WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $id
        ]);

        sendResponse(
            true,
            'Project deleted successfully.',
            [
                'id' => $id
            ],
            200
        );

    } catch (PDOException $e) {

        sendResponse(
            false,
            'Failed to delete project.',
            null,
            500
        );
    }
}


/*
|--------------------------------------------------------------------------
| Unsupported HTTP Method
|--------------------------------------------------------------------------
*/

sendResponse(
    false,
    'Method not allowed.',
    null,
    405
);