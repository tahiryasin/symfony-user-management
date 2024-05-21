# Serdao - Technical Test - Senior PHP Backend Developer

## Overview

This repository contains a Symfony-based User Management application that allows for the addition, listing, and deletion of users. The application connects to a MariaDB database and performs CREATE and DELETE operations on the `user` table.

## Table of Contents

- [Setup](#setup)
- [Usage](#usage)
- [Improvements in UserController](#improvements-in-usercontroller)
- [Improvements in Template](#improvements-in-template)
- [Suggested Improvements](#suggested-improvements)

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
    docker compose up --build
    ```

3. Install dummy data inside the Symfony container:

    ```bash
    docker exec -it <symfony_container_id> bash
    cd /var/www/html
    php bin/console doctrine:fixtures:load
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

- Click the "Delete" link next to the user you want to remove. A confirmation dialog will appear before the deletion.

## Improvements in UserController

The `UserController` has been refactored to follow best practices and ensure it is suitable for production environments. Below are the key improvements:

### Dependency Injection

- **Old Implementation**: The database connection was obtained directly within the `request` method.
- **New Implementation**: The database connection is now injected via the constructor, making it easier to manage and test.

### Request Handling

- The `Request` object from Symfony is properly type-hinted and used for handling GET and POST requests.
- All operations were code in the same method, created separate method for each operation. 

### Security

- **Old Implementation**: SQL queries were constructed using string concatenation, which is vulnerable to SQL injection attacks.
- **New Implementation**: SQL queries now use prepared statements to prevent SQL injection attacks.

### Form Validation

- **Old Implementation**: No input validation was implemented.
- **New Implementation**: Validator component installed and input validation implemented that can be further improved.
    ```bash
    composer require symfony/validator
    ```
	
### Database Migration/Fixture

- **Old Implementation**: User table creation and data initialization were directly embedded within the UserController.
- **New Implementation**: Created migration for user table creation, and a fixure created to initialize user table with three users.

### Auto-Increment Primary Key

- **Old Implementation**: The `id` column in the `user` table was not set as a primary key with auto-increment, which could lead to inconsistent data.
- **New Implementation**: The `id` column is now set as a primary key with auto-increment to ensure unique user entries.

### Twig Bundle

- **Old Implementation**: The Twig Bundle was missing, which is required for rendering Twig templates.
- **New Implementation**: Ensured the twig templates render by installing Symfony Twig Bundle component.
    ```bash
	composer require symfony/twig-bundle
    ```

### Code Structure

- **Old Implementation**: The code was less organized, was not documented and harder to maintain.
- **New Implementation**: The code is now better organized, documented, with separate methods handling different parts of the logic, making it more maintainable and readable.

## Improvements in template

Below are the improvements implemented in base.html.twig

### Delete Operation

	- Implemented separate method for delete operation in the UserController
	- Changed delete request to POST instead of GET
	- Prevented Cross-Site Request Forgery attacks on delete operation by adding CSRF token.
	- To prevent accidental deletions, a confirmation dialog has been added to the delete operation.- 

### CSS Separation

To improve maintainability and organization, the CSS styles have been moved to a separate file. This is done using the Symfony Asset component. Below are the steps taken:

1. **Created a CSS file**:
    - Path: `public/css/styles.css`
    - Moved all the CSS from the Twig template into this file.

2. **Linked the CSS file in the Twig template**:
    - Added a `<link>` tag to include the external CSS file using the `asset` function.

    ```html
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    ```

3. **Installed Symfony Asset component**:
    - Ensured the `asset` function works correctly by installing the Symfony Asset component.
    
    ```bash
    composer require symfony/asset
    ```
### CSRF Protection

To secure the user creation form, a CSRF token has been added. This helps prevent Cross-Site Request Forgery attacks. Below are the steps taken:

1. **Installed Symfony CSRF component**:
    - Ensured the application can handle CSRF tokens by installing the Symfony CSRF component.

    ```bash
    composer require symfony/security-csrf
    ```

2. **Added CSRF token to the form**:
    - Updated the Twig template to include a hidden CSRF token field.


3. **Validated CSRF token in the controller**:
    - Updated the controller to validate the CSRF token before processing the form data.


## Suggested Improvements

Here are some additional improvements that can enhance the functionality and usability of the application:
1. **Code Coverage**:
	- Write a test case for each method separately using PHPUnit.
	- Mock dependencies where necessary (e.g., Connection).
	- Include tests for invalid data and error scenarios.
	- Use Symfony’s WebTestCase to simulate HTTP requests and responses.
	- Test routes, form submissions, and redirections.
	- Test missing or invalid CSRF tokens.
	
1. **User Interface**:
    - Implement pagination for the user list to handle a large number of users efficiently. Symfony provides a bundle called `Pagerfanta` that can be integrated for this purpose.
	- Implement form rendering functions
	- Impelment form validation erros under each input filed.

2. **Database**:
    - Separate columns for firstname, lastname and address

3. **Error Handling**:
    - Show an error message if the delete operation fails. This can be done by catching exceptions during the delete process and setting an error flash message to inform the user.
