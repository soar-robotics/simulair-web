# WARNING: !! DEPRECATED !!
This project is deprecated and the platform implements aws services as a serverless back-end.

# Simulair-web - Web Service
Backend solution for Simulair-web project.

## Components and Features

- REST API
- 
-

## Prerequisites

**Running with Docker containers:**
 - Docker (preferably version 19.x+)
 - Docker Compose

## Setup

 1. Copy environment file

        cp .env.example .env
2. Set/modify environment values if necessary

       nano .env
3. Build and run containers

       docker-compose up -d
4. Once built and ran, execute following commands to configure the application

       docker exec app composer install
       docker exec app php artisan key:generate
       docker exec -it app php artisan jwt:secret
       docker exec app php artisan config:cache
       docker exec app php artisan migrate --path=database/migrations/mongodb
       docker exec app php artisan db:seed

## Usage

When installation completes, database is seeded with sandbox data. Use following application user for experimental purposes:

    u: test@lubarto.com
    p: password
Email address is set to verified for this user.

**Example API requests:**

Authenticate

    POST /auth/token
    Host: http://localhost:88/api
    Content-Type: application/x-www-form-urlencoded
    email=test@lubarto.com
    password=password

Get authenticated user data

    GET /me
    Host: http://localhost:88/api
    Authorization: Bearer <JWT Access Token>

## API Specification
*To be added*
