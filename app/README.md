# Serdao - Technical Test - Senior PHP Backend Developer

## Overview

This repository contains a Symfony-based User Management application that allows for the addition, listing, and deletion of users. The application connects to a MariaDB database and performs CREATE and DELETE operations on the `user` table.

## Table of Contents

- [Setup](#setup)
- [Usage](#usage)
- [Improvements in UserController](#improvements-in-usercontroller)
- [Notes](#notes)
- [License](#license)

## Setup

### Prerequisites

- Docker
- Docker Compose

### Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/tahiryasin/symfony-user-management.git
    cd symfony-user-management
    ```

2. Build and start the Docker containers:

    ```bash
    docker-compose up -d
    ```

3. Install dependencies inside the Symfony container:

    ```bash
    docker-compose exec symfony composer install
    ```

4. Access the application:

    Open your web browser and go to `http://localhost:8000/user`.

## Usage

### Add a User

- Fill in the "First name", "Last name", and "Address" fields.
- Click the "Add user" button.

### List Users

- The users are listed in a table below the form.

### Delete a User

- Click the "Delete" link next to the user you want to remove.

## Improvements in UserController

The `UserController` has been refactored to follow best practices and ensure it is suitable for production environments. Below are the key improvements:

### Dependency Injection

- **Old Implementation**: The database connection was obtained directly within the `request` method.
- **New Implementation**: The database connection is now injected via the constructor, making it easier to manage and test.

### Request Handling

- **Old Implementation**: The `Request` object was not properly type-hinted, leading to potential issues and lack of clarity.
- **New Implementation**: The `Request` object from Symfony is properly type-hinted and used for handling GET and POST requests.

### Security

- **Old Implementation**: SQL queries were constructed using string concatenation, which is vulnerable to SQL injection attacks.
- **New Implementation**: SQL queries now use prepared statements to prevent SQL injection attacks.

### Database Operations

- **Old Implementation**: Database operations were directly embedded within the `request` method, leading to code duplication and reduced readability.
- **New Implementation**: Methods have been added for database operations (`initializeDatabase`, `handleGetRequest`, `handlePostRequest`, `getConnection`, and `executeRequest`) to encapsulate and reuse the logic.

### Auto-Increment Primary Key

- **Old Implementation**: The `id` column in the `user` table was not set as a primary key with auto-increment, which could lead to duplicate entries and inconsistent data.
- **New Implementation**: The `id` column is now set as a primary key with auto-increment to ensure unique user entries.

### Twig Bundle

- **Old Implementation**: The Twig Bundle was missing, which is required for rendering Twig templates.
- **New Implementation**: The Twig Bundle has been included in the `composer.json` file and installed using `composer require symfony/twig-bundle`.

### Docker Compose Version

- **Old Implementation**: The `docker-compose.yml` file included a `version` declaration which is now considered obsolete.
- **New Implementation**: The `version` declaration has been removed from the `docker-compose.yml` file as it is no longer required and removing it is backward compatible with older versions of Docker Compose.

### Code Structure

- **Old Implementation**: The code was less modular and harder to maintain.
- **New Implementation**: The code is now better organized, with separate methods handling different parts of the logic, making it more maintainable and readable.

## Notes

- Ensure you have the required permissions to execute Docker commands.
- The database connection parameters are configured for development purposes. Update them as needed for your production environment.
- The `composer install` command must be run inside the `symfony` container to properly install the required dependencies.
