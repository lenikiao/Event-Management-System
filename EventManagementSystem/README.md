# Event Management System

## Project Overview

This project is a web application that allows users to create accounts, log in, and manage events they organize. The application is built using PHP, MySQL, and follows an object-oriented approach.

## Table of Contents

1. [Features](#features)
2. [Technologies Used](#technologies-used)
3. [Installation](#installation)
4. [Database Schema](#database-schema)
5. [Usage](#usage)
6. [File Structure](#file-structure)
7. [Classes and Methods](#classes-and-methods)
8. [Security](#security)
9. [Contributing](#contributing)
10. [License](#license)

## Features

- User Registration
- User Login
- Event Creation
- Event Viewing
- User Session Management
- Secure Password Storage

## Technologies Used

- PHP
- MySQL
- HTML
- CSS 

# Class and Method Documentation

## Class: Database
### Methods:
- `conn()`: Establishes a connection to the database.

## Class: Event
### Properties:
- `public $id`
- `public $name`
- `public $description`
- `public $date_time`
- `public $location`
- `public $user_id`

### Methods:
- `__construct($db)`: Constructor to initialize the database connection.
- `create()`: Creates a new event in the database.
- `read()`: Retrieves all events from the database.
- `update()`: Updates an existing event in the database.
- `delete()`: Deletes an event from the database.

## Class: Session
### Methods:
- `start()`: Starts a new session or resumes an existing session.
- `get($key)`: Retrieves a value from the session.
- `destroy()`: Destroys the current session.

## Usage

1. **Login/Register**: Use the login page to access your account or register for a new one.
2. **Create Event**: Use the form on the main page to create new events.
3. **Edit/Delete Event**: Use the edit and delete buttons next to each event to modify or remove them.
