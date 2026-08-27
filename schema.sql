CREATE DATABASE IF NOT EXISTS nova_studio
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE nova_studio;

-- =========================================
-- PROJECTS
-- =========================================

CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    category VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- SERVICES
-- =========================================

CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- CONTACT MESSAGES
-- =========================================

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- NEWSLETTER SUBSCRIBERS
-- =========================================

CREATE TABLE IF NOT EXISTS subscribers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- SAMPLE PROJECTS
-- =========================================

INSERT INTO projects (title, description, image, category) VALUES
(
    'E-Commerce Platform',
    'A modern responsive e-commerce platform.',
    'ecommerce.jpg',
    'Web Development'
),
(
    'Brand Identity',
    'A complete modern brand identity project.',
    'branding.jpg',
    'Branding'
),
(
    'Creative Portfolio',
    'A premium portfolio website for a creative professional.',
    'portfolio.jpg',
    'Web Design'
);

-- =========================================
-- SAMPLE SERVICES
-- =========================================

INSERT INTO services (title, description, icon) VALUES
(
    'Web Development',
    'Modern and responsive websites built with clean code.',
    'code'
),
(
    'UI/UX Design',
    'User-focused interfaces with modern visual experiences.',
    'palette'
),
(
    'Branding',
    'Professional visual identities for modern businesses.',
    'sparkles'
),
(
    'Digital Marketing',
    'Digital strategies to help businesses grow online.',
    'chart'
);