<?php

/**
 * NOVA Studio
 * API Validation Helpers
 */

/**
 * Check if a value is a non-empty string.
 */
function isValidString(mixed $value, int $minLength = 1, int $maxLength = 255): bool
{
    if (!is_string($value)) {
        return false;
    }

    $value = trim($value);

    $length = mb_strlen($value);

    return $length >= $minLength && $length <= $maxLength;
}

/**
 * Validate an email address.
 */
function isValidEmail(mixed $email): bool
{
    if (!is_string($email)) {
        return false;
    }

    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Get and decode JSON request body.
 */
function getJsonInput(): array
{
    $rawInput = file_get_contents('php://input');

    if ($rawInput === false || trim($rawInput) === '') {
        return [];
    }

    $data = json_decode($rawInput, true);

    if (!is_array($data)) {
        return [];
    }

    return $data;
}