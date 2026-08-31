# NOVA Studio — Database Integration & Backend API

A full-stack digital agency project developed as part of **DecodeLabs Project 3: Database Integration** using PHP, MySQL, PDO, JSON, and REST-style API architecture.

NOVA Studio connects the backend application with a MySQL database to provide permanent data storage, retrieval, validation, and CRUD operations.

---

## 🎯 Project Overview

**Project:** NOVA Studio  
**Training:** DecodeLabs Industrial Training  
**Project:** Project 3 — Database Integration  
**Batch:** 2026  
**Backend:** PHP  
**Database:** MySQL  
**Database Access:** PDO  
**API Format:** JSON  
**Development Environment:** XAMPP

Project 3 focuses on connecting the backend with a database to store and retrieve data permanently.

The backend follows the:

**Input → Validation → Processing → Database → JSON Response**

model.

```text
Client Request
      ↓
Input
      ↓
Validation
      ↓
Business Logic
      ↓
MySQL Database
      ↓
JSON Response

The project follows the principle:

Never Trust the Client

All incoming data is validated before database operations are performed.

📚 Project 3 Objectives

The main objectives of Project 3 are:

Connect the backend application with a database
Design a simple database schema
Store data permanently
Retrieve stored data
Perform CRUD operations
Validate incoming data
Handle invalid requests properly
Use prepared SQL statements
Protect database queries from SQL injection
Return consistent JSON responses
🛠️ Technologies Used
PHP 8+
MySQL
PDO
JSON
REST-style API architecture
XAMPP
Apache
phpMyAdmin
Thunder Client

No external PHP framework or Composer dependency is required.

📁 Project Structure
nova-studio/

│
├── index.html
├── about.html
├── services.html
├── projects.html
├── contact.html
│
├── style.css
├── responsive.css
├── script.js
├── README.md
│
├── api/
│   ├── projects.php
│   ├── services.php
│   ├── contact.php
│   └── subscribe.php
│
├── config/
│   └── database.php
│
├── includes/
│   ├── response.php
│   └── validation.php
│
└── database/
    └── schema.sql
🗄️ Database

Database name:

nova_studio

The application uses MySQL as its permanent data storage system.

The database contains four main tables:

nova_studio
│
├── projects
├── services
├── contact_messages
└── subscribers
📊 Database Schema
projects

Stores NOVA Studio portfolio project information.

Column	Type	Constraint	Description
id	INT UNSIGNED	PRIMARY KEY, AUTO_INCREMENT	Unique project ID
title	VARCHAR(150)	NOT NULL	Project title
description	TEXT	NOT NULL	Project description
image	VARCHAR(255)	NULL	Project image filename
category	VARCHAR(100)	NULL	Project category
created_at	TIMESTAMP	DEFAULT CURRENT_TIMESTAMP	Creation date
services

Stores NOVA Studio services.

Column	Type	Constraint	Description
id	INT UNSIGNED	PRIMARY KEY, AUTO_INCREMENT	Unique service ID
title	VARCHAR(150)	NOT NULL	Service title
description	TEXT	NOT NULL	Service description
icon	VARCHAR(100)	NULL	Service icon
created_at	TIMESTAMP	DEFAULT CURRENT_TIMESTAMP	Creation date
contact_messages

Stores messages submitted through the contact form.

Column	Type	Constraint	Description
id	INT UNSIGNED	PRIMARY KEY, AUTO_INCREMENT	Unique message ID
name	VARCHAR(100)	NOT NULL	Sender name
email	VARCHAR(255)	NOT NULL	Sender email
message	TEXT	NOT NULL	Contact message
created_at	TIMESTAMP	DEFAULT CURRENT_TIMESTAMP	Submission date
subscribers

Stores newsletter subscribers.

Column	Type	Constraint	Description
id	INT UNSIGNED	PRIMARY KEY, AUTO_INCREMENT	Unique subscriber ID
email	VARCHAR(255)	NOT NULL, UNIQUE	Subscriber email
created_at	TIMESTAMP	DEFAULT CURRENT_TIMESTAMP	Subscription date

The UNIQUE constraint prevents duplicate newsletter subscriptions.

🔌 Database Connection

Database connection is handled through:

config/database.php

The project uses PDO:

$pdo = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
);

PDO provides:

Database connectivity
Exception-based error handling
Prepared statements
Associative result fetching
Safer SQL query execution
⚙️ Installation & Setup
1. Install XAMPP

Start the following services:

Apache
MySQL
2. Place the Project

Place the project inside the XAMPP htdocs directory:

htdocs/nova-studio/
3. Create the Database

Open:

http://localhost/phpmyadmin

Import:

database/schema.sql

The SQL file creates:

nova_studio

and the required tables.

4. Configure Database

Database configuration is located at:

config/database.php

Default XAMPP configuration:

Host: localhost
Database: nova_studio
Username: root
Password: empty
🔌 API Base URL

Local development base URL:

http://localhost/nova-studio/api/
📡 API Endpoints

The NOVA Studio backend provides API endpoints for:

Projects
Services
Contact Messages
Newsletter Subscribers
🟢 PROJECT CRUD API

The projects.php endpoint now supports complete CRUD operations.

CREATE → POST
READ   → GET
UPDATE → PUT / PATCH
DELETE → DELETE
1. Get All Projects
Method
GET
Endpoint
/api/projects.php
Full URL
http://localhost/nova-studio/api/projects.php
Successful Response

200 OK

{
    "success": true,
    "message": "Projects fetched successfully.",
    "data": [
        {
            "id": 1,
            "title": "E-Commerce Platform",
            "description": "A modern responsive e-commerce platform.",
            "image": "ecommerce.jpg",
            "category": "Web Development",
            "created_at": "2026-08-27 21:40:17"
        }
    ]
}
2. Get Single Project
Method
GET
Endpoint
/api/projects.php?id={id}
Example
http://localhost/nova-studio/api/projects.php?id=1
Successful Response

200 OK

{
    "success": true,
    "message": "Project fetched successfully.",
    "data": {
        "id": 1,
        "title": "E-Commerce Platform",
        "description": "A modern responsive e-commerce platform.",
        "image": "ecommerce.jpg",
        "category": "Web Development",
        "created_at": "2026-08-27 21:40:17"
    }
}
Project Not Found

404 Not Found

{
    "success": false,
    "message": "Project not found.",
    "data": null
}
Invalid Project ID

Example:

/api/projects.php?id=abc

Response:

400 Bad Request

{
    "success": false,
    "message": "Invalid project ID.",
    "data": null
}
3. Create Project
Method
POST
Endpoint
/api/projects.php
Full URL
http://localhost/nova-studio/api/projects.php
Header
Content-Type: application/json
Request Body
{
    "title": "Mobile App Development",
    "description": "A modern mobile application with a clean and responsive user experience.",
    "image": "mobile-app.jpg",
    "category": "Mobile Development"
}
Successful Response

201 Created

{
    "success": true,
    "message": "Project created successfully.",
    "data": {
        "id": 4
    }
}
4. Update Project
Method
PUT

or:

PATCH
Endpoint
/api/projects.php?id={id}
Example
http://localhost/nova-studio/api/projects.php?id=4
Header
Content-Type: application/json
Request Body
{
    "title": "Mobile App Development - Updated",
    "description": "An updated modern mobile application with a clean and responsive user experience.",
    "image": "mobile-app-updated.jpg",
    "category": "Mobile Development"
}
Successful Response

200 OK

{
    "success": true,
    "message": "Project updated successfully.",
    "data": {
        "id": 4
    }
}

If the project does not exist:

404 Not Found

{
    "success": false,
    "message": "Project not found.",
    "data": null
}
5. Delete Project
Method
DELETE
Endpoint
/api/projects.php?id={id}
Example
http://localhost/nova-studio/api/projects.php?id=4

No request body is required.

Successful Response

200 OK

{
    "success": true,
    "message": "Project deleted successfully.",
    "data": {
        "id": 4
    }
}

If the project does not exist:

404 Not Found

{
    "success": false,
    "message": "Project not found.",
    "data": null
}
🟢 CRUD Summary
Operation	HTTP Method	Database Operation	Endpoint
Create	POST	INSERT	/api/projects.php
Read All	GET	SELECT	/api/projects.php
Read One	GET	SELECT	/api/projects.php?id=1
Update	PUT/PATCH	UPDATE	/api/projects.php?id=1
Delete	DELETE	DELETE	/api/projects.php?id=1
6. Get All Services
Method
GET
Endpoint
/api/services.php
Full URL
http://localhost/nova-studio/api/services.php
Successful Response

200 OK

{
    "success": true,
    "message": "Services fetched successfully",
    "data": [
        {
            "id": 1,
            "title": "Web Development",
            "description": "Modern and responsive websites built with clean code.",
            "icon": "code",
            "created_at": "2026-08-27 21:40:17"
        }
    ]
}
7. Submit Contact Message
Method
POST
Endpoint
/api/contact.php
Full URL
http://localhost/nova-studio/api/contact.php
Header
Content-Type: application/json
Request Body
{
    "name": "Hasnain",
    "email": "test@gmail.com",
    "message": "I need a modern website for my business."
}
Successful Response

201 Created

{
    "success": true,
    "message": "Message submitted successfully.",
    "data": {
        "id": "1"
    }
}
Validation Rules
Field	Requirement
name	Required, 2–100 characters
email	Required, valid email
message	Required, 10–5000 characters
Validation Error

400 Bad Request

{
    "success": false,
    "message": "Validation failed.",
    "data": {
        "errors": {
            "name": "Name must be between 2 and 100 characters.",
            "email": "Please provide a valid email address."
        }
    }
}
8. Subscribe to Newsletter
Method
POST
Endpoint
/api/subscribe.php
Full URL
http://localhost/nova-studio/api/subscribe.php
Request Body
{
    "email": "subscriber@gmail.com"
}
Successful Response

201 Created

{
    "success": true,
    "message": "Successfully subscribed.",
    "data": {
        "id": "1"
    }
}
Invalid Email

400 Bad Request

{
    "success": false,
    "message": "Please provide a valid email address.",
    "data": null
}
Duplicate Email

If the same email is submitted again:

409 Conflict

{
    "success": false,
    "message": "This email is already subscribed.",
    "data": null
}
📊 HTTP Status Codes
Status Code	Meaning	Usage
200	OK	Successful read/update/delete
201	Created	Successfully created data
400	Bad Request	Invalid input or validation failure
404	Not Found	Requested resource does not exist
405	Method Not Allowed	Incorrect HTTP method
409	Conflict	Duplicate subscriber
500	Internal Server Error	Unexpected server/database error
🛡️ Data Validation & Security

The backend follows the:

Never Trust the Client

principle.

Incoming data is validated before database operations.

Validation includes:

Required field checking
String length checking
Email format validation
Integer validation for project IDs
HTTP method validation
JSON input validation
Prepared SQL statements
PDO exception handling
🔐 SQL Injection Protection

Database queries use PDO prepared statements.

Example:

$stmt = $pdo->prepare(
    'SELECT id, title, description, image, category, created_at
     FROM projects
     WHERE id = :id
     LIMIT 1'
);

$stmt->execute([
    ':id' => $id
]);

User-provided values are passed as parameters rather than being directly concatenated into SQL queries.

This helps prevent SQL injection attacks.

🧩 Database Constraints

The database uses constraints to maintain data integrity.

Examples include:

PRIMARY KEY
NOT NULL
UNIQUE
AUTO_INCREMENT
DEFAULT CURRENT_TIMESTAMP

For example, newsletter subscriber emails are unique:

email VARCHAR(255) NOT NULL UNIQUE

This prevents the same email address from being stored multiple times.

🔄 HTTP Method Handling

Each API operation uses the appropriate HTTP method.

Projects
GET     → Read
POST    → Create
PUT     → Update
PATCH   → Update
DELETE  → Delete
Services
GET → Read
Contact
POST → Create
Newsletter
POST → Create

Unsupported HTTP methods return:

405 Method Not Allowed

📦 JSON Response Format

Successful responses follow a consistent structure:

{
    "success": true,
    "message": "Operation successful.",
    "data": {}
}

Error responses:

{
    "success": false,
    "message": "Something went wrong.",
    "data": null
}

This provides a predictable response structure for frontend applications.

🧪 API Testing

The APIs were tested using Thunder Client.

CRUD Tests

The following project operations were tested:

Create project
Get all projects
Get single project
Update project
Delete project
Validation Tests

The following validation scenarios were tested:

Invalid project title
Invalid project description
Invalid project ID
Non-existent project ID
Invalid email
Invalid contact data
Data Integrity Tests

The following database behavior was tested:

Duplicate subscriber email
UNIQUE constraint
Database persistence
Prepared SQL statements
Security Test

SQL injection-style invalid project ID input was tested and safely rejected through input validation.

✅ Verification Results
Test	Result
Database connection	✅ Passed
Create project	✅ Passed
Read all projects	✅ Passed
Read single project	✅ Passed
Update project	✅ Passed
Delete project	✅ Passed
Invalid input validation	✅ Passed
Invalid project ID	✅ Passed
Non-existent project	✅ Passed
Duplicate subscriber	✅ Passed
SQL injection protection test	✅ Passed
HTTP method handling	✅ Passed
🎯 Project 3 Requirements Covered
Requirement	Status
Backend connected with database	✅
Simple database schema	✅
Permanent data storage	✅
Data retrieval	✅
CREATE operation	✅
READ operation	✅
UPDATE operation	✅
DELETE operation	✅
Proper data handling	✅
Input validation	✅
Database constraints	✅
PDO database connection	✅
Prepared statements	✅
SQL injection protection	✅
Error handling	✅
HTTP status codes	✅
API testing	✅
📈 Project 2 → Project 3 Progression

NOVA Studio was originally developed with the backend/API foundation of DecodeLabs Project 2.

Project 3 extends the same NOVA Studio architecture by adding deeper database persistence and complete CRUD functionality.

Project 2
   ↓
Backend API
   ↓
Database Connection
   ↓
Project 3
   ↓
Database Integration
   ↓
Permanent Data Storage
   ↓
Complete CRUD
   ↓
Validation & Data Integrity

The existing NOVA Studio project structure was retained instead of creating a separate project.

🚀 Future Improvements

Possible future enhancements include:

Authentication and authorization
Admin dashboard
CRUD operations for services
Pagination
Search and filtering
Rate limiting
API versioning
Frontend API integration
Additional database relationships
Query optimization
Production deployment
🏆 Project Status
NOVA Studio — DecodeLabs Project 3

Database Integration & State Persistence

Status: COMPLETED ✅
Core Skills Demonstrated
PHP
MySQL
PDO
Database Integration
CRUD
REST-style APIs
JSON
Input Validation
Data Persistence
Database Constraints
Prepared Statements
SQL Injection Protection
API Testing
👨‍💻 Developer

NOVA Studio — DecodeLabs Industrial Training

Project 3: Database Integration

Batch: 2026

Powered by DecodeLabs
