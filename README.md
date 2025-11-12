# Project Management System - Backend

## Setup
1. clone repo
2. composer install
3. cp .env.example .env
4. set DB and mail credentials
5. php artisan key:generate
6. php artisan migrate --seed
7. php artisan serve

## Queue worker
QUEUE_CONNECTION=database
php artisan queue:work

## Tests
php artisan test

## API
- POST /api/register
- POST /api/login
- GET /api/projects
...
